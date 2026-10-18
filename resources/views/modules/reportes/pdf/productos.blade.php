<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Productos</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-left {
            float: left;
            width: 60%;
        }

        .header-right {
            float: right;
            width: 35%;
            text-align: right;
            font-size: 11px;
            color: #555;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header p {
            margin: 3px 0 0;
            color: #777;
        }

        .clearfix {
            clear: both;
        }

        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f7fa;
            border: 1px solid #dfe4ea;
        }

        .filters table {
            width: 100%;
            border-collapse: collapse;
        }

        .filters td {
            padding: 4px;
            border: none;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead th {
            padding: 10px;
            background: #34495e;
            color: #fff;
            font-size: 11px;
            text-align: left;
        }

        .table tbody td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .estado-ok {
            color: #16a34a;
            font-weight: bold;
        }

        .estado-bad {
            color: #dc2626;
            font-weight: bold;
        }

        .totales {
            width: 260px;
            margin-left: auto;
            margin-top: 20px;
        }

        .totales table {
            width: 100%;
            border-collapse: collapse;
        }

        .totales td {
            padding: 8px 10px;
            border: 1px solid #dfe4ea;
        }

        .total-final {
            background: #34495e;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <h1>Reporte de Productos</h1>
            <p>Resumen de productos del sistema</p>
        </div>

        <div class="header-right">
            <div>
                <strong>Usuario:</strong>
                {{ auth()->user()->name ?? 'N/D' }}
            </div>

            <div>
                <strong>Fecha:</strong>
                {{ $fecha }}
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="filters">
        <table>
            <tr>
                <td>
                    <strong>Estado:</strong>
                    @if(request('estado') === '1')
                        Activo
                    @elseif(request('estado') === '0')
                        Inactivo
                    @else
                        Todos
                    @endif
                </td>

                <td>
                    <strong>Nombre:</strong>
                    {{ request('nombre') ?? 'Todos' }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Precio mínimo:</strong>
                    {{ request('precio_min') ? 'Bs ' . number_format(request('precio_min'), 2) : 'Todos' }}
                </td>

                <td>
                    <strong>Precio máximo:</strong>
                    {{ request('precio_max') ? 'Bs ' . number_format(request('precio_max'), 2) : 'Todos' }}
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th class="text-right">Precio</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($productos as $index => $producto)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                    <td class="text-right">
                        Bs {{ number_format($producto->precio, 2) }}
                    </td>
                    <td>
                        @if($producto->estado)
                            <span class="estado-ok">Activo</span>
                        @else
                            <span class="estado-bad">Inactivo</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:15px;">
                        No existen registros.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totales">
        <table> 
            <tr>
                <td>Activos</td>
                <td class="text-right">{{ $activos }}</td>
            </tr>

            <tr>
                <td>Inactivos</td>
                <td class="text-right">{{ $inactivos }}</td>
            </tr>

            <tr class="total-final">
                <td>Total de Registros</td>
                <td class="text-right">{{ $total }}</td>
            </tr>
        </table>
    </div>

</body>
</html>
