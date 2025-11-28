<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f7f3ef;
            font-family: Arial, Helvetica, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .header {
            background: linear-gradient(45deg, #FF6A00, #FF8C42);
            padding: 25px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
        }

        .content {
            padding: 25px;
            color: #333;
        }

        .content h2 {
            font-size: 22px;
            color: #FF6A00;
            margin-bottom: 10px;
        }

        .content p {
            line-height: 1.6;
            font-size: 16px;
        }

        .order-box {
            background: #fff7e6;
            border-left: 6px solid #FF6A00;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .order-box strong {
            color: #FF6A00;
        }

        .cta {
            margin-top: 30px;
            text-align: center;
        }

        .cta a {
            background: #FF6A00;
            padding: 12px 20px;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
        }

        .footer {
            margin-top: 20px;
            padding: 15px;
            background: #f2f2f2;
            font-size: 13px;
            text-align: center;
            color: #555;
        }
    </style>

</head>
<body>

<div class="email-container">

    <!-- HEADER -->
    <div class="header">
        <h1>🍕 ¡Tu pedido está en camino!</h1>
        <p style="margin-top:5px;">Gracias por confiar en nuestra pizzería</p>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h2>Hola {{ $cliente->nombre ?? 'Cliente' }},</h2>

       <p>Hemos registrado tu pedido con éxito. Nuestro equipo ya comenzó a prepararlo para servirlo en tu mesa.</p>

        <div class="order-box">
            <p><strong>Número de Pedido:</strong> #{{ $venta->id ?? '---' }}</p>
            <p><strong>Total:</strong> Bs {{ number_format($venta->total ?? 0, 2) }}</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <p>
            En unos momentos te avisaremos cuando tu pedido esté listo para disfrutarlo.  
            ¡Gracias por elegir nuestra pizzería!
        </p>


    </div>

    <!-- FOOTER -->
    <div class="footer">
        © {{ date('Y') }} Pizzería Yuneth – Todos los derechos reservados.<br>
        Este es un correo automático, por favor no responder.
    </div>

</div>

</body>
</html>
