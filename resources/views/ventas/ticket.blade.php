<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->numero_venta }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
            width: 80mm; /* Formato ticket térmico */
        }
        .ticket-container {
            padding: 5mm;
            max-width: 80mm;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .divider-solid {
            border-top: 1px solid #000;
            margin: 10px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 2px 0;
            vertical-align: top;
        }
        .col-qty { width: 15%; }
        .col-desc { width: 50%; }
        .col-price { width: 35%; text-align: right; }
        
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: 16px; margin-bottom: 5px; }
        
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #f59e0b;
            color: white;
            text-align: center;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .ticket-container { padding: 0; }
        }
    </style>
</head>
<body>

<div class="ticket-container">
    <div class="text-center mb-2">
        <h1 class="bold">{{ $config->nombre_negocio ?? 'BOCADITOS YAQUELIN' }}</h1>
        @if($config->eslogan)
            <p class="mb-1">{{ $config->eslogan }}</p>
        @endif
        <p>{{ $config->direccion ?? 'Av. Banzer' }}</p>
        <p>Tel: {{ $config->codigo_pais ?? '591' }} {{ $config->whatsapp ?? '70000000' }}</p>
    </div>

    <div class="divider"></div>

    <div class="mb-2">
        <p><span class="bold">TICKET N°:</span> {{ $venta->numero_venta }}</p>
        <p><span class="bold">FECHA:</span> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
        <p><span class="bold">CAJERO:</span> {{ $venta->usuario->name ?? 'Admin' }}</p>
        <p><span class="bold">MÉTODO PAGO:</span> {{ $venta->metodoPago->nombre }}</p>
    </div>

    <div class="divider-solid"></div>

    <table>
        <thead>
            <tr>
                <th class="col-qty text-left">CANT</th>
                <th class="col-desc text-left">DESCRIPCIÓN</th>
                <th class="col-price text-right">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td class="col-qty">{{ $detalle->cantidad }}</td>
                <td class="col-desc">{{ $detalle->nombre_producto }}</td>
                <td class="col-price">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider-solid"></div>

    <table class="mb-2">
        @if($venta->descuento > 0)
        <tr>
            <td class="text-right">SUBTOTAL:</td>
            <td class="text-right bold">Bs. {{ number_format($venta->total + $venta->descuento, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">DESCUENTO:</td>
            <td class="text-right bold">Bs. -{{ number_format($venta->descuento, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td class="text-right"><h3 class="bold">TOTAL A PAGAR:</h3></td>
            <td class="text-right"><h3 class="bold">Bs. {{ number_format($venta->total, 2) }}</h3></td>
        </tr>
    </table>

    <div class="divider"></div>
    
    <div class="text-center mt-2 mb-2">
        <p class="bold mb-1">¡GRACIAS POR SU COMPRA!</p>
        <p>Visítenos pronto</p>
    </div>
    
    <div class="text-center mt-1" style="font-size: 10px; color: #666;">
        Desarrollado por el Sistema POS
    </div>
    
    <button class="btn-print no-print" onclick="window.print()">
        🖨️ Imprimir Ticket
    </button>
</div>

<script>
    // Imprimir automáticamente al abrir (opcional, comentado por defecto para ver la vista previa)
    // window.onload = function() { window.print(); }
</script>
</body>
</html>
