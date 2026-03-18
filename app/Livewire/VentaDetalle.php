<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Promocion;
use App\Models\DetalleVenta;
use App\Models\TipoPago;
use App\Mail\SaleDone;
use Illuminate\Support\Facades\Mail;


class VentaDetalle extends Component
{
    public $producto_id = '';
    public $cantidad = 1;
    public $productosDisponibles = [];

    public $promocion_id = null; // para el descuento/promoción seleccionado
    public $detalle = []; // opcional, para mantener localmente la lista de productos
    public $descuento = 0;
    public $total = 0;     // total de la venta

    public $tipo_pago_id = null; // ← AGREGAR ESTO

    public function mount($productos)
    {
        $this->productosDisponibles = $productos;

        if (!Session::has('productos_venta')) {
            Session::put('productos_venta', []);
        }
    }

    public function updatedProductoId()
    {
        $this->dispatch('validarBoton');
    }

    public function updatedCantidad()
    {
        $this->dispatch('validarBoton');
    }

    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required',
            'cantidad' => 'required|integer|min:1',
        ], [
            'producto_id.required' => 'Debe seleccionar un producto primero.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.min' => 'La cantidad debe ser mayor a 0.',
        ]);

        $productoId = intval($this->producto_id);
        $productos = Session::get('productos_venta');
        $producto = $this->buscarProducto($productoId);

        if (!$producto) return;

        if (isset($productos[$productoId])) {
            $productos[$productoId]['cantidad'] += $this->cantidad;
        } else {
            $productos[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'cantidad' => $this->cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal' => $producto->precio * $this->cantidad,
            ];
        }

        // Recalcular subtotal
        $productos[$productoId]['subtotal'] =
            $productos[$productoId]['cantidad'] *
            $productos[$productoId]['precio_unitario'];

        Session::put('productos_venta', $productos);

        // Limpia inputs
        $this->producto_id = '';
        $this->cantidad = 1;

        $this->dispatch('validarBoton');
    }

    public function eliminarProducto($id)
    {
        $productos = Session::get('productos_venta');
        unset($productos[$id]);
        Session::put('productos_venta', $productos);
    }

    public function calcularTotal()
    {
        return collect(Session::get('productos_venta'))->sum('subtotal');
    }

    private function buscarProducto($id)
    {
        return collect($this->productosDisponibles)->firstWhere('id', $id);
    }

    public function updatedPromocionId()
    {
        $this->detalle = Session::get('productos_venta', []);
        $this->total = $this->calcularTotalConDescuento();
    }


    public function cancelarVenta()
    {
        Session::forget('cliente');
        Session::forget('productos_venta');
        $this->detalle = [];
        $this->total = 0;

        return redirect()->route('ventas.create');
    }

    public function confirmarVenta()
    {
        // Validación básica
        // if (count($this->detalle) === 0) {
        //     $this->dispatch('alerta', 'Debes agregar al menos un producto.');
        //     return;
        // }

        $clienteSession = Session::get('cliente');
        $cliente_id = $clienteSession['id'] ?? null;
        // Calcular subtotal y descuento
        $subtotal = $this->calcularTotal();
        $descuento = 0;
        
        if ($this->promocion_id) {
            if ($this->promocion_id === 'descuento' && isset($clienteSession['descuento'])) {
                $descuento = $clienteSession['descuento'];
            } else {
                $promocion = Promocion::find($this->promocion_id);
                $descuento = $promocion ? $promocion->descuento : 0;
            }
        }
        
        $total = max($subtotal - $descuento, 0);

        // Guardar la venta
        $venta = Venta::create([
            'usuario_id' => auth()->id(),
            'cliente_id' => $cliente_id,
            'tipo_pago_id' => $this->tipo_pago_id,
            'promocion_id' => $this->promocion_id === 'descuento' || $this->promocion_id === '' ? 1 : $this->promocion_id,
            'puntos' => 0,
            'fecha' => now(),
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'total' => $total,
            'estado' => 1,
        ]);

        // Calcular puntos ganados
        $puntos = floor($total / 20);

        // Actualizar puntos y descuento del cliente
        if ($cliente_id) {
            $cliente = Cliente::find($cliente_id);
            if ($cliente) {
                $cliente->puntos += $puntos;
                $cliente->descuento = max(($cliente->descuento ?? 0) - $descuento, 0);
                $cliente->save();
            }
        }

        // Guardar detalles de venta
        foreach ($this->detalle as $producto) {
            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'subtotal' => $producto['subtotal'],
            ]);
        }
        Mail::to($clienteSession['correo'])->send(new SaleDone($venta));

        // Limpiar sesión y resetear variables
        Session::forget('productos_venta');
        Session::forget('cliente');
        $this->detalle = [];
        $this->tipo_pago_id = null;
        $this->promocion_id = null;
        $this->total = 0;

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Detalle de venta creado correctamente.');
    }

    public function calcularTotalConDescuento()
    {
        $total = collect(Session::get('productos_venta', []))->sum('subtotal');

        // 🔹 Si no hay promoción → no tocar el total
        if (!$this->promocion_id) {
            return $total;
        }

        // 🔹 Si la opción es "descuento" de cliente (tu caso especial)
        if ($this->promocion_id === 'descuento'
            && session('cliente')
            && isset(session('cliente')['descuento'])
        ) {
            $this->descuento = session('cliente')['descuento'];
            $total -= session('cliente')['descuento'];
            return max($total, 0);
        }

        // 🔹 Si es una promoción normal
        $promocion = \App\Models\Promocion::find($this->promocion_id);
        if ($promocion) {
            $this->descuento = $promocion->descuento;
            $total -= $promocion->descuento;
        }

        return max($total, 0);
    }

    public function render()
    {
        $this->detalle = Session::get('productos_venta', []);
        $this->total = $this->calcularTotalConDescuento();

        return view('livewire.venta-detalle', [
            'detalle' => $this->detalle,
            'total' => $this->total,
            'tipo_pagos' => \App\Models\TipoPago::where('estado', 1)->get(),
            'promociones' => \App\Models\Promocion::where('estado', 1)->get(),
        ]);
    }

}
