<!DOCTYPE html>
<html>
<head>
    <title>Auditoría de Materiales</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #0064a0;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0064a0;
        }
        thead {
        color: #fff;
        }
        .background {
        background-position: center;
        background-image: url('data:image/jpg;base64,{!! base64_encode(file_get_contents($imagePath2)) !!}') ;
        background-repeat: no-repeat;
        }
        .container1 {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 3px solid #ccc;
        border-radius: 10px;
        }
    </style>
</head>
<body class="background">
    <div class="container">
    {{--En esta línea de código muestra una imagen en un documento HTML.
        La imagen se codifica en base64 y se almacena en una variable llamada $imagePath.
        La línea de código utiliza la función base64_encode() para codificar la imagen en base64.
        Luego, utiliza la función file_get_contents() para leer el contenido de la imagen codificada.
        Finalmente, utiliza la etiqueta img para mostrar la imagen en el documento HTML.--}}
        <img src="data:image/jpg;base64, {!! base64_encode(file_get_contents($imagePath)) !!}" alt="Logotipo Sii" width="200px" height="100px">
        <h1>Auditoría de Materiales</h1>
        {{-- Informacion del responsable --}}
        <div class="container1">
        <p style="text-align: center">Responsable: {{ $responsable }}</p>
        <p style="text-align: center">Dirección regional: {{$direccion}}</p>
        <p style="text-align: center">Fecha y hora de generación del reporte: {{ $fecha }}</p>
        </div>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Objeto</th>
                    <th>Tipo de objeto</th>
                    <th>Tipo de movimiento</th>
                    <th>Cantidad previa</th>
                    <th>Cantidad a modificar</th>
                    <th>Cantidad resultante</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditorias as $auditoria)
                    <tr>
                        <td>{{ $auditoria->MOVIMIENTO_OBJETO }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_TIPO_OBJETO }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_TIPO }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_STOCK_PREVIO }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_CANTIDAD_A_MODIFICAR }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_STOCK_RESULTANTE }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
