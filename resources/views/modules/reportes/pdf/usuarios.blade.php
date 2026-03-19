<!DOCTYPE html>
<html>
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
        <h1>Reporte de Usuarios</h1>
        <p>Fecha de generación: {{ $fecha }}</p>
    </div>

    <!-- Filtros -->
    <div class="info">
        <span><strong>Estado:</strong> 
            @if(request('estado') === '1') Activos
            @elseif(request('estado') === '0') Inactivos
            @else Todos
            @endif
        </span>
    </div>

    <!-- Resumen -->
    <div class="summary">
        <div>
            Total Usuarios
            <strong>{{ $total }}</strong>
        </div>

        <div>
            Activos
            <strong>{{ $activos }}</strong>
        </div>

        <div>
            Inactivos
            <strong>{{ $inactivos }}</strong>
        </div>
    </div>

    <!-- Tabla -->
    <table>
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
