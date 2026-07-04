<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Promocion;
use App\Models\Descuento;
use App\Models\DetalleVenta;
use App\Models\HistorialCanje;
use App\Models\TipoPago;
use App\Mail\SaleDone;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class VentaDetalle extends Component
{
    public $producto_id  = '';
    public $cantidad     = 1;
    public $productosDisponibles = [];

    public $promocion_id  = null;
    public $usar_puntos   = false;
    public $detalle       = [];
    public $descuento     = 0;
    public $total         = 0;
    public $tipo_pago_id  = null;

    public function mount($productos)
    {
        $this->productosDisponibles = $productos;

        if (!Session::has('productos_venta')) {
            Session::put('productos_venta', []);
        }
    }

    public function updatedProductoId()
    {
        $this->resetErrorBag('cantidad');
        $this->dispatch('validarBoton');
    }

    public function updatedCantidad()
    {
        $this->resetErrorBag('cantidad');

        if ($this->producto_id && $this->cantidad >= 1) {
            $this->validarStock(intval($this->producto_id), intval($this->cantidad));
        }

        $this->dispatch('validarBoton');
    }

    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required',
            'cantidad'    => 'required|integer|min:1',
        ], [
            'producto_id.required' => 'Debe seleccionar un producto primero.',
            'cantidad.required'    => 'Debe ingresar una cantidad.',
            'cantidad.min'         => 'La cantidad debe ser mayor a 0.',
        ]);

        $productoId = intval($this->producto_id);
        $productos  = Session::get('productos_venta');
        $producto   = $this->buscarProducto($productoId);

        if (!$producto) {
            $this->addError('producto_id', 'Producto no encontrado.');
            return;
        }

        if (!$this->validarStock($productoId, intval($this->cantidad))) {
            return;
        }

        if (isset($productos[$productoId])) {
            $productos[$productoId]['cantidad'] += $this->cantidad;
        } else {
            $productos[$productoId] = [
                'id'             => $producto['id'],
                'nombre'         => $producto['nombre'],
                'cantidad'       => $this->cantidad,
                'precio_unitario'=> $producto['precio'],
                'subtotal'       => $producto['precio'] * $this->cantidad,
            ];
        }

        $productos[$productoId]['subtotal'] =
            $productos[$productoId]['cantidad'] * $productos[$productoId]['precio_unitario'];

        Session::put('productos_venta', $productos);

        $this->producto_id = '';
        $this->cantidad    = 1;
        $this->dispatch('validarBoton');
    }

    public function eliminarProducto($id)
    {
        $productos = Session::get('productos_venta');
        unset($productos[$id]);
        Session::put('productos_venta', $productos);
    }

    public function calcularSubtotal(): float
    {
        return collect(Session::get('productos_venta'))->sum('subtotal');
    }

    public function calcularTotalConDescuento(): float
    {
        $subtotal        = $this->calcularSubtotal();
        $this->descuento = 0;

        if ($this->promocion_id) {
            $promocion = Promocion::find($this->promocion_id);
            if ($promocion) {
                $this->descuento += ($subtotal * $promocion->descuento) / 100;
            }
        }

        if ($this->usar_puntos) {
            $clienteSession = Session::get('cliente');
            if ($clienteSession) {
                $cliente        = Cliente::find($clienteSession['id']);
                $descuentoCanje = Descuento::where('estado', 1)->first();
                if ($cliente && $descuentoCanje && $cliente->saldo_puntos >= $descuentoCanje->puntos) {
                    $this->descuento += $descuentoCanje->descuento;
                } else {
                    $this->usar_puntos = false;
                }
            }
        }

        return max($subtotal - $this->descuento, 0);
    }

    public function updatedPromocionId()  { $this->total = $this->calcularTotalConDescuento(); }
    public function updatedUsarPuntos()   { $this->total = $this->calcularTotalConDescuento(); }

    public function cancelarVenta()
    {
        Session::forget(['cliente', 'productos_venta']);
        $this->detalle       = [];
        $this->total         = 0;
        $this->usar_puntos   = false;
        $this->promocion_id  = null;

        return redirect()->route('ventas.create');
    }

    public function confirmarVenta()
    {
        $this->detalle = Session::get('productos_venta', []);

        foreach ($this->detalle as $item) {
            $producto = Producto::with('inventario')->find($item['id']);
            $stock    = $producto?->inventario?->cantidad ?? 0;

            if ($item['cantidad'] > $stock) {
                $this->addError('cantidad', "Stock insuficiente para {$item['nombre']}. Solo hay {$stock} disponible(s).");
                return;
            }
        }

        $clienteSession = Session::get('cliente');
        $cliente        = $clienteSession ? Cliente::find($clienteSession['id']) : null;
        $subtotal       = $this->calcularSubtotal();
        $this->descuento = 0;

        $promocion = $this->promocion_id ? Promocion::find($this->promocion_id) : null;
        if ($promocion) {
            $this->descuento += ($subtotal * $promocion->descuento) / 100;
            $promocion->decrement('limite_uso');
        }

        $descuentoCanje = null;
        if ($this->usar_puntos && $cliente) {
            $descuentoCanje = Descuento::where('estado', 1)->first();
            if ($descuentoCanje && $cliente->saldo_puntos >= $descuentoCanje->puntos) {
                $this->descuento += $descuentoCanje->descuento;
            } else {
                $descuentoCanje = null;
            }
        }

        $cliente_anonimo = Cliente::validateClienteAnonimo($cliente);
        $total  = max($subtotal - $this->descuento, 0);

        $puntos = Cliente::calcularPuntos($total);

        $venta = Venta::create([
            'usuario_id'   => auth()->id(),
            'cliente_id'   => $cliente?->id,
            'tipo_pago_id' => $this->tipo_pago_id,
            'promocion_id' => $promocion?->id,
            'puntos'       => $cliente_anonimo ? 0 : $puntos,
            'subtotal'     => $subtotal,
            'descuento'    => $this->descuento,
            'total'        => $total,
            'estado'       => 1,
        ]);

        if ($descuentoCanje && $cliente) {
            HistorialCanje::create([
                'cliente_id'   => $cliente->id,
                'descuento_id' => $descuentoCanje->id,
                'venta_id'     => $venta->id,
                'puntos'       => $descuentoCanje->puntos,
                'fecha'        => now()->toDateString(),
                'estado'       => 1,
            ]);
        }


        foreach ($this->detalle as $producto) {
            DetalleVenta::create([
                'venta_id'        => $venta->id,
                'producto_id'     => $producto['id'],
                'cantidad'        => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'subtotal'        => $producto['subtotal'],
            ]);
            $_producto = Producto::with('inventario')->find($producto['id']);
            $_producto->inventario->decrementarCantidad(intval($producto['cantidad']));
        }

        if ($cliente && !empty($cliente->correo) && config('app.send_mail')) {
            Mail::to($cliente->correo)->send(new SaleDone($venta));
        }

        Session::forget(['productos_venta', 'cliente']);
        $this->detalle      = [];
        $this->tipo_pago_id = null;
        $this->promocion_id = null;
        $this->usar_puntos  = false;
        $this->total        = 0;

        return redirect()->route('ventas.show', $venta->id);
    }

    private function buscarProducto($id)
    {
        return collect($this->productosDisponibles)->firstWhere('id', $id);
    }

    private function validarStock(int $productoId, int $cantidad): bool
    {
        $producto = $this->buscarProducto($productoId);

        if (!$producto) {
            $this->addError('producto_id', 'Producto no encontrado.');
            return false;
        }

        $stock             = (int) ($producto['stock'] ?? 0);
        $productos         = Session::get('productos_venta', []);
        $cantidadEnCarrito = (int) ($productos[$productoId]['cantidad'] ?? 0);
        $cantidadTotal     = $cantidadEnCarrito + $cantidad;

        if ($stock <= 0) {
            $this->addError('cantidad', 'No hay stock disponible para este producto.');
            return false;
        }

        if ($cantidadTotal > $stock) {
            $disponible = max($stock - $cantidadEnCarrito, 0);
            $mensaje    = $cantidadEnCarrito > 0
                ? "Stock insuficiente. Hay {$stock} en inventario y ya tienes {$cantidadEnCarrito} en la venta. Puedes agregar {$disponible} más."
                : "Stock insuficiente. Solo hay {$stock} disponible(s).";

            $this->addError('cantidad', $mensaje);
            return false;
        }

        return true;
    }

    public function render()
    {
        $this->detalle = Session::get('productos_venta', []);
        $this->total   = $this->calcularTotalConDescuento();

        $clienteSession = Session::get('cliente');
        $cliente        = $clienteSession ? Cliente::find($clienteSession['id']) : null;
        $descuentoCanje = Descuento::where('estado', 1)->first();

        return view('livewire.venta-detalle', [
            'detalle'        => $this->detalle,
            'total'          => $this->total,
            'tipo_pagos'     => TipoPago::where('estado', 1)->get(),
            'promociones'    => Promocion::where('estado', 1)->where('fecha_inicio', '<=', Carbon::now()->format('Y-m-d'))->where('fecha_fin', '>=', Carbon::now()->format('Y-m-d'))->get(),
            'saldo_puntos'   => $cliente?->saldo_puntos ?? 0,
            'puede_canjear'  => $cliente && $descuentoCanje && $cliente->saldo_puntos >= $descuentoCanje->puntos,
            'descuento_canje'=> $descuentoCanje?->descuento ?? 0,
            'cliente'        => $cliente,
        ]);
    }
}