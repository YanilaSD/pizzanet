<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios</title>

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
            <h1>Reporte de Usuarios</h1>
            <p>Resumen de usuarios del sistema</p>
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

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha Registro</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($usuarios as $index => $u)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($u->estado == 1)
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
                <td>Total de Registros</td>
                <td class="text-right">{{ $inactivos }}</td>
            </tr>

            <tr>
                <td>Activos</td>
                <td class="text-right">{{ $activos }}</td>
            </tr>

            <tr class="total-final">
            <td>Total de Registros</td>
                <td class="text-right">{{ $total }}</td>
            </tr>
        </table>
    </div>

</body>
</html>
