<?php

namespace App\Http\Controllers;

use App\Models\CanjePunto;
use App\Models\Cliente;
use App\Models\HistorialCanje;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Cliente $cliente)
    {
        $movimientos = $cliente->movimientosPuntos();

        return view('modules.clientes.canjes', compact('cliente', 'movimientos'));
    }

    public function canjear(Request $request, Cliente $cliente)
    {
        $descuento = CanjePunto::findOrFail($request->descuento_id);

        if ($cliente->saldo_puntos < $descuento->puntos) {
            return back()->withErrors(['puntos' => 'El cliente no tiene suficientes puntos.']);
        }

        HistorialCanje::create([
            'cliente_id'   => $cliente->id,
            'canje_punto_id' => $descuento->id,
            'puntos'       => $descuento->puntos,
            'fecha'        => now(),
            'estado'       => 1,
        ]);

        return back()->with([
            'descuento_aplicado' => $descuento->descuento,
            'mensaje' => "Se canjearon {$descuento->puntos} puntos por Bs. {$descuento->descuento} de descuento."
        ]);
    }
}
