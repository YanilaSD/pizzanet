<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
            padding: 10px;
            background: #f4f6f8;
            border-radius: 6px;
        }

        .info span {
            display: inline-block;
            margin-right: 15px;
        }

        .summary {
            margin-bottom: 15px;
        }

        .summary div {
            display: inline-block;
            width: 32%;
            background: #f9fafb;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
        }

        .summary strong {
            display: block;
            font-size: 14px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2c3e50;
            color: #fff;
            padding: 8px;
            font-size: 11px;
        }

        td {
            padding: 7px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .estado-ok {
            color: green;
            font-weight: bold;
        }

        .estado-bad {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Reporte de Ventas</h1>
        <p>Fecha de generación: {{ $fecha }}</p>
    </div>

    <!-- Filtros -->
    <div class="info">
        <span><strong>Desde:</strong> {{ request('desde') ?? 'Todos' }}</span>
        <span><strong>Hasta:</strong> {{ request('hasta') ?? 'Todos' }}</span>
        <span><strong>Tipo Pago:</strong> {{ request('tipo_pago_id') ?? 'Todos' }}</span>
        <span><strong>Estado:</strong> 
            @if(request('estado') === '1') Completada
            @elseif(request('estado') === '0') Anulada
            @else Todos
            @endif
        </span>
    </div>

    <!-- Resumen -->
    <div class="summary">
        <div>
            Total Vendido
            <strong>Bs {{ number_format($total, 2) }}</strong>
        </div>

        <div>
            Descuentos
            <strong>- Bs {{ number_format($descuentos, 2) }}</strong>
        </div>

        <div>
            Cantidad
            <strong>{{ count($ventas) }}</strong>
        </div>
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Tipo Pago</th>
                <th>Fecha</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Descuento</th>
                <th class="text-right">Total</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($ventas as $index => $venta)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $venta->cliente->nombre ?? '-' }}</td>
                    <td>{{ $venta->tipoPago->nombre ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>

                    <td class="text-right">
                        Bs {{ number_format($venta->subtotal, 2) }}
                    </td>

                    <td class="text-right">
                        - Bs {{ number_format($venta->descuento, 2) }}
                    </td>

                    <td class="text-right">
                        Bs {{ number_format($venta->total, 2) }}
                    </td>

                    <td>
                        @if($venta->estado == 1)
                            <span class="estado-ok">Completada</span>
                        @else
                            <span class="estado-bad">Anulada</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:15px;">
                        No existen registros
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Generado automáticamente por el sistema • {{ date('Y') }}
    </div>

</body>
</html>
