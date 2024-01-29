<!DOCTYPE html>
<html lang="es">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mensaje ISO-ONE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
 
<body class="bg-light">
    <div class="container">
        <div class="card p-6 p-lg-10 space-y-4">
            <h1 class="h3 fw-700 pl-4 pr-4 pt-4">Hola {{$name}}</h1>
            <p class=" pl-4 pr-4">{{ $message }}</p>
            <div class="table-responsive mx-2">
                <table class="table table-sm">
                    <tr><th>Nombre Documento</th><th>Código</th><th>Estado</th><th>Días sin gestión</th><th>Enlace</th></tr>
                    @foreach($documents as $document)
                    <tr><td>{{ $document['name'] }}</td><td>{{ $document['code'] }}</td><td>{{ $document['status'] }}</td><td class="text-center">{{ $document['due'] }}</td><td class="text-center"><a href="{{ route('documents.control.manage.edit', ['slug' => 'user', 'hash' => $document['hash'] ]) }}" title="Ir a editar el documento">Gestionar</a></td></tr>
                    @endforeach
                </table>
            </div>
            <hr class="mt-2 mb-3" />
            <p class=" pl-4 pr-4">Atentamente:</p>
            <p class=" pl-4 pr-4"><span class="font-weight-light">{{$sign}}</span><br /><span>{{$role}}</span></p>
        </div>
        <div class="text-muted text-center my-6">
            ISO-ONE (c) {{ date("Y") }}
        </div>
    </div>
</body>
 
</html>