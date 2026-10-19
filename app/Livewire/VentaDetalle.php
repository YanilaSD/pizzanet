<?php

namespace App\Livewire;

use App\Mail\SaleDone;
use App\Models\CanjePunto;
use App\Notifications\VentaRegistradaPush;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\HistorialCanje;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\TipoPago;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class VentaDetalle extends Component
{
    public $producto_id = '';
    public $cantidad = 1;
    public $productosDisponibles = [];

    public $promocion_id = null;
    public $usar_puntos = false;

    public $detalle = [];
    public $descuento = 0;
    public $total = 0;
    public $tipo_pago_id = null;

    public function mount($productos)
    {
        $this->productosDisponibles = $productos;

        if (! Session::has('productos_venta')) {
            Session::put('productos_venta', []);
        }

        $this->actualizarTotales();
    }

    public function updatedProductoId()
    {
        $this->resetErrorBag(['cantidad', 'producto_id']);

        if ($this->producto_id && (int) $this->cantidad >= 1) {
            $this->validarStock((int) $this->producto_id, (int) $this->cantidad);
        }
    }

    public function updatedCantidad()
    {
        $this->resetErrorBag('cantidad');

        if ($this->producto_id && (int) $this->cantidad >= 1) {
            $this->validarStock((int) $this->producto_id, (int) $this->cantidad);
        }
    }

    public function updatedPromocionId($value): void
    {
        $this->promocion_id = $this->normalizarId($value);

        if ($this->promocionSeleccionada()) {
            $this->usar_puntos = false;
        }

        $this->resetErrorBag('promocion_id');
        $this->actualizarTotales();
    }

    public function updatedUsarPuntos($value)
    {
        if ($value) {
            $this->promocion_id = null;
        }

        $this->actualizarTotales();
    }

    public function puedeAgregarProducto(): bool
    {
        if (! $this->producto_id || (int) $this->cantidad < 1) {
            return false;
        }

        return $this->stockRestante((int) $this->producto_id) >= (int) $this->cantidad;
    }

    public function stockRestante(int $productoId): int
    {
        $producto = $this->buscarProducto($productoId);
        $stock = (int) ($producto?->inventario?->cantidad ?? 0);
        $enCarrito = (int) (Session::get('productos_venta', [])[$productoId]['cantidad'] ?? 0);

        return max($stock - $enCarrito, 0);
    }

    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ], [
            'producto_id.required' => 'Debe seleccionar un producto primero.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.min' => 'La cantidad debe ser mayor a 0.',
        ]);

        $productoId = (int) $this->producto_id;
        $productos = Session::get('productos_venta', []);
        $producto = $this->buscarProducto($productoId);

        if (! $producto || ! $producto->inventario) {
            $this->addError('producto_id', 'Producto no encontrado o sin inventario.');

            return;
        }

        if (! (bool) $producto->estado) {
            $this->addError('producto_id', 'El producto no está disponible.');

            return;
        }

        if (! $this->validarStock($productoId, (int) $this->cantidad)) {
            return;
        }

        if (isset($productos[$productoId])) {
            $productos[$productoId]['cantidad'] += (int) $this->cantidad;
        } else {
            $productos[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'cantidad' => (int) $this->cantidad,
                'precio_unitario' => (float) $producto->precio,
                'subtotal' => (float) $producto->precio * (int) $this->cantidad,
            ];
        }

        $productos[$productoId]['subtotal'] =
            $productos[$productoId]['cantidad']
            * $productos[$productoId]['precio_unitario'];

        Session::put('productos_venta', $productos);

        $this->producto_id = '';
        $this->cantidad = 1;
        $this->resetErrorBag(['cantidad', 'producto_id']);
        $this->actualizarTotales();
    }

    public function eliminarProducto($id)
    {
        $productos = Session::get('productos_venta', []);
        unset($productos[$id]);
        Session::put('productos_venta', $productos);

        $this->actualizarTotales();
    }

    public function calcularSubtotal(): float
    {
        return round((float) collect(Session::get('productos_venta', []))->sum('subtotal'), 2);
    }

    public function calcularTotalConDescuento(): float
    {
        $subtotal = $this->calcularSubtotal();
        $this->descuento = $this->calcularDescuento($subtotal);

        return round(max($subtotal - $this->descuento, 0), 2);
    }

    public function cancelarVenta()
    {
        Session::forget(['cliente', 'productos_venta']);

        $this->detalle = [];
        $this->total = 0;
        $this->descuento = 0;
        $this->usar_puntos = false;
        $this->promocion_id = null;
        $this->tipo_pago_id = null;
        $this->producto_id = '';
        $this->cantidad = 1;

        return redirect()->route('ventas.create');
    }

    public function confirmarVenta()
    {
        $this->validate([
            'tipo_pago_id' => 'required|integer|exists:tipo_pagos,id',
        ], [
            'tipo_pago_id.required' => 'Debe seleccionar un método de pago.',
            'tipo_pago_id.exists' => 'El método de pago seleccionado no existe.',
        ]);

        $tipoPago = TipoPago::whereKey($this->tipo_pago_id)
            ->where('estado', 1)
            ->where('activo', 1)
            ->first();

        if (! $tipoPago) {
            $this->addError('tipo_pago_id', 'El método de pago seleccionado no está activo.');

            return;
        }

        if ($this->promocionSeleccionada() && $this->usar_puntos) {
            $this->addError(
                'promocion_id',
                'Solo puedes aplicar una promoción o un canje de puntos, no ambos.'
            );

            return;
        }

        $this->detalle = Session::get('productos_venta', []);

        if (empty($this->detalle)) {
            $this->addError('cantidad', 'Debes agregar al menos un producto a la venta.');

            return;
        }

        $clienteSession = Session::get('cliente');
        $puntosCanjeados = 0;

        try {
            $venta = DB::transaction(function () use ($clienteSession, &$puntosCanjeados) {
                $subtotal = $this->calcularSubtotal();

                $cliente = $clienteSession
                    ? Cliente::whereKey($clienteSession['id'])->lockForUpdate()->first()
                    : null;

                if ($clienteSession && ! $cliente) {
                    throw ValidationException::withMessages([
                        'cliente' => 'El cliente seleccionado ya no existe.',
                    ]);
                }

                $promocion = null;
                $descuentoCanje = null;
                $this->descuento = 0;

                if ($this->promocionSeleccionada()) {
                    $promocion = $this->promocionActiva((int) $this->promocion_id, true);

                    if (! $promocion) {
                        throw ValidationException::withMessages([
                            'promocion_id' => 'La promoción seleccionada ya no está disponible.',
                        ]);
                    }

                    if (
                        $promocion->limite_uso !== null &&
                        (int) $promocion->limite_uso <= 0
                    ) {
                        throw ValidationException::withMessages([
                            'promocion_id' => 'La promoción ya no tiene usos disponibles.',
                        ]);
                    }

                    if ((float) $promocion->compra_minima > $subtotal) {
                        throw ValidationException::withMessages([
                            'promocion_id' => 'El subtotal no alcanza la compra mínima de la promoción.',
                        ]);
                    }

                    $this->descuento = $this->descuentoPorPromocion($subtotal, $promocion);

                    if ($promocion->limite_uso !== null) {
                        $promocion->decrement('limite_uso');
                        $promocion->refresh();
                        $promocion->marcarCompletadaSiAgotada();
                    }
                } elseif ($this->usar_puntos) {
                    if (! $cliente || $cliente->esAnonimo()) {
                        throw ValidationException::withMessages([
                            'usar_puntos' => 'El cliente seleccionado no puede canjear puntos.',
                        ]);
                    }

                    $reglaCanje = CanjePunto::where('estado', 1)
                        ->lockForUpdate()
                        ->first();

                    $canjeAplicado = $reglaCanje
                        ? $reglaCanje->canjeParaSubtotal((int) $cliente->saldo_puntos, $subtotal)
                        : ['bloques' => 0, 'puntos' => 0, 'descuento' => 0.0];

                    if ($canjeAplicado['bloques'] < 1) {
                        throw ValidationException::withMessages([
                            'usar_puntos' => 'El cliente no tiene puntos suficientes para el canje.',
                        ]);
                    }

                    $descuentoCanje = $reglaCanje;
                    $puntosCanjeados = $canjeAplicado['puntos'];
                    $this->descuento = $canjeAplicado['descuento'];
                }

                $this->descuento = min(max((float) $this->descuento, 0), $subtotal);
                $total = round(max($subtotal - $this->descuento, 0), 2);

                $productosVenta = [];

                foreach ($this->detalle as $item) {
                    $producto = Producto::whereKey($item['id'])->lockForUpdate()->first();

                    if (! $producto) {
                        throw ValidationException::withMessages([
                            'cantidad' => 'Un producto ya no está disponible.',
                        ]);
                    }

                    $inventario = $producto->inventario()->lockForUpdate()->first();
                    $stock = (int) ($inventario?->cantidad ?? 0);

                    if (! $inventario || (int) $item['cantidad'] > $stock) {
                        throw ValidationException::withMessages([
                            'cantidad' => "Stock insuficiente para {$item['nombre']}. Disponible: {$stock}.",
                        ]);
                    }

                    $productosVenta[] = [
                        'item' => $item,
                        'inventario' => $inventario,
                    ];
                }

                $puntos = ($cliente && ! $cliente->esAnonimo())
                    ? Cliente::calcularPuntos($total)
                    : 0;

                $venta = Venta::create([
                    'usuario_id' => auth()->id(),
                    'cliente_id' => $cliente?->id,
                    'tipo_pago_id' => $this->tipo_pago_id,
                    'promocion_id' => $promocion?->id,
                    'puntos' => $puntos,
                    'subtotal' => $subtotal,
                    'descuento' => $this->descuento,
                    'total' => $total,
                    'estado' => 1,
                ]);

                if ($descuentoCanje && $cliente && $puntosCanjeados > 0) {
                    HistorialCanje::create([
                        'cliente_id' => $cliente->id,
                        'canje_punto_id' => $descuentoCanje->id,
                        'venta_id' => $venta->id,
                        'puntos' => $puntosCanjeados,
                        'fecha' => now()->toDateString(),
                        'estado' => 1,
                    ]);
                }

                foreach ($productosVenta as $registro) {
                    $item = $registro['item'];
                    $inventario = $registro['inventario'];

                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $item['id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $inventario->decrementarCantidad((int) $item['cantidad']);
                }

                return $venta;
            });
        } catch (ValidationException $e) {
            foreach ($e->errors() as $campo => $mensajes) {
                foreach ($mensajes as $mensaje) {
                    $this->addError($campo, $mensaje);
                }
            }

            return;
        }

        $cliente = $clienteSession
            ? Cliente::find($clienteSession['id'])
            : null;

        if ($cliente && ! empty($cliente->correo) && config('app.send_mail')) {
            Mail::to($cliente->correo)->send(new SaleDone($venta));
        }

        if ($cliente && ! $cliente->esAnonimo() && $cliente->pushSubscriptions()->exists()) {
            try {
                $cliente->notify(new VentaRegistradaPush($venta));
            } catch (\Throwable $e) {
                Log::warning('No se pudo enviar la notificación push de la venta.', [
                    'venta_id' => $venta->id,
                    'cliente_id' => $cliente->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Session::forget(['productos_venta', 'cliente']);

        $this->detalle = [];
        $this->tipo_pago_id = null;
        $this->promocion_id = null;
        $this->usar_puntos = false;
        $this->total = 0;
        $this->descuento = 0;

        return redirect()->route('ventas.show', $venta->id);
    }

    public function render()
    {
        $this->detalle = Session::get('productos_venta', []);
        $this->total = $this->calcularTotalConDescuento();

        $cliente = $this->clienteActual();
        $descuentoCanje = CanjePunto::where('estado', 1)->first();
        $saldoPuntos = (int) ($cliente?->saldo_puntos ?? 0);
        $sinCanje = ['bloques' => 0, 'puntos' => 0, 'descuento' => 0.0];
        $canjeDisponible = ($cliente && ! $cliente->esAnonimo() && $descuentoCanje)
            ? $descuentoCanje->canjeDisponible($saldoPuntos)
            : $sinCanje;
        $puedeCanjear = $canjeDisponible['bloques'] >= 1;

        $promociones = $this->promocionesActivas();
        $promocionActual = $this->promocionSeleccionada()
            ? $promociones->firstWhere('id', (int) $this->promocion_id)
            : null;
        $subtotal = $this->calcularSubtotal();
        $canjeAplicado = ($this->usar_puntos && $puedeCanjear && $descuentoCanje)
            ? $descuentoCanje->canjeParaSubtotal($saldoPuntos, $subtotal)
            : $sinCanje;

        return view('livewire.venta-detalle', [
            'detalle' => $this->detalle,
            'total' => $this->total,
            'tipo_pagos' => $this->tiposPagoActivos(),
            'promociones' => $promociones,
            'promocion_actual' => $promocionActual,
            'alerta_compra_minima' => $promocionActual && (float) $promocionActual->compra_minima > $subtotal
                ? 'El subtotal no alcanza la compra mínima de Bs '.number_format((float) $promocionActual->compra_minima, 2).'.'
                : null,
            'saldo_puntos' => $saldoPuntos,
            'puede_canjear' => $puedeCanjear,
            'canje_punto' => $descuentoCanje,
            'puntos_canje' => $canjeDisponible['puntos'],
            'descuento_canje' => $canjeDisponible['descuento'],
            'puntos_canjeados' => $canjeAplicado['puntos'],
            'cliente' => $cliente,
        ]);
    }

    private function actualizarTotales(): void
    {
        $this->detalle = Session::get('productos_venta', []);
        $this->total = $this->calcularTotalConDescuento();
    }

    private function promocionSeleccionada(): bool
    {
        return filled($this->promocion_id) && (int) $this->promocion_id > 0;
    }

    private function normalizarId($value): ?int
    {
        if ($value === '' || $value === null || (int) $value === 0) {
            return null;
        }

        return (int) $value;
    }

    private function calcularDescuento(float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0;
        }

        if ($this->promocionSeleccionada()) {
            $promocion = $this->promocionActiva((int) $this->promocion_id);

            if (! $promocion) {
                return 0;
            }

            if ((float) $promocion->compra_minima > $subtotal) {
                return 0;
            }

            return $this->descuentoPorPromocion($subtotal, $promocion);
        }

        if ($this->usar_puntos) {
            $cliente = $this->clienteActual();
            $canje = CanjePunto::where('estado', 1)->first();
            $aplicado = ($cliente && ! $cliente->esAnonimo() && $canje)
                ? $canje->canjeParaSubtotal((int) $cliente->saldo_puntos, $subtotal)
                : ['bloques' => 0, 'puntos' => 0, 'descuento' => 0.0];

            if ($aplicado['bloques'] >= 1) {
                return $aplicado['descuento'];
            }

            $this->usar_puntos = false;
        }

        return 0;
    }

    private function descuentoPorPromocion(float $subtotal, Promocion $promocion): float
    {
        $porcentaje = (float) $promocion->descuento;
        $descuento = round($subtotal * ($porcentaje / 100), 2);

        return min(max($descuento, 0), $subtotal);
    }

    private function promocionActiva(int $id, bool $bloquear = false): ?Promocion
    {
        Promocion::sincronizarCompletadas();

        $query = Promocion::query()
            ->activas()
            ->conFestividadActiva()
            ->whereKey($id)
            ->whereDate('fecha_inicio', '<=', Carbon::today())
            ->whereDate('fecha_fin', '>=', Carbon::today())
            ->where(function ($query) {
                $query->whereNull('limite_uso')
                    ->orWhere('limite_uso', '>', 0);
            });

        if ($bloquear) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    private function promocionesActivas()
    {
        Promocion::sincronizarCompletadas();

        return Promocion::query()
            ->activas()
            ->conFestividadActiva()
            ->whereDate('fecha_inicio', '<=', Carbon::today())
            ->whereDate('fecha_fin', '>=', Carbon::today())
            ->where(function ($query) {
                $query->whereNull('limite_uso')
                    ->orWhere('limite_uso', '>', 0);
            })
            ->orderBy('nombre')
            ->get();
    }

    private function tiposPagoActivos()
    {
        return TipoPago::where('estado', 1)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();
    }

    private function clienteActual(): ?Cliente
    {
        $clienteSession = Session::get('cliente');

        return $clienteSession
            ? Cliente::find($clienteSession['id'])
            : null;
    }

    private function buscarProducto($id): ?Producto
    {
        return Producto::with('inventario')->find($id);
    }

    private function validarStock(int $productoId, int $cantidad): bool
    {
        $producto = $this->buscarProducto($productoId);

        if (! $producto || ! $producto->inventario) {
            $this->addError('producto_id', 'Producto no encontrado o sin inventario.');

            return false;
        }

        $stock = (int) $producto->inventario->cantidad;
        $productos = Session::get('productos_venta', []);
        $cantidadEnCarrito = (int) ($productos[$productoId]['cantidad'] ?? 0);
        $cantidadTotal = $cantidadEnCarrito + $cantidad;

        if ($stock <= 0) {
            $this->addError('cantidad', 'No hay stock disponible para este producto.');

            return false;
        }

        if ($cantidadTotal > $stock) {
            $disponible = max($stock - $cantidadEnCarrito, 0);

            $mensaje = $cantidadEnCarrito > 0
                ? "Stock insuficiente. Hay {$stock} en inventario y ya tienes {$cantidadEnCarrito} en la venta. Puedes agregar {$disponible} más."
                : "Stock insuficiente. Solo hay {$stock} disponible(s).";

            $this->addError('cantidad', $mensaje);

            return false;
        }

        return true;
    }
}
