<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Envío de Propuesta</title>
</head>
<body>
    <p>El usuario {{ $user->name }} ha enviado una propuesta de plano para el edificio: {{ $building_name }}</p>
    <img src="file://{{ $image }}"   data-auto-embed alt="Imagen Propuesta {{ $building_name }}">
</body>
</html>