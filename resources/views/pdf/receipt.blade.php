<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #eee; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SERVICIO TÉCNICO IPHONE</h1>
        <p>Orden de Reparación #{{ $device->id }}</p>
    </div>
    <h3>Cliente: {{ $device->customer?->name }}</h3>
    <h3>Equipo: {{ $device->model }} (IMEI: {{ $device->imei }})</h3>
    <p>Estado al recibir: {{ $device->condition_notes }}</p>
    <br><br><br>
    <p style="text-align: center">__________________________<br>Firma</p>
</body>
</html>