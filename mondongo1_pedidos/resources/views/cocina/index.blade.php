<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas en Cocina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <h1 class="mb-4">Comandas en Cocina</h1>

        @if($pedidos->isEmpty())
            <p class="alert alert-info">No hay pedidos pendientes.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Usuario</th>
                        <th>Productos</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->user_id }}</td>
                            <td>
                                <ul>
                                    @foreach($pedido->productos as $producto)
                                        <li>{{ $producto->nombre }} (x{{ $producto->cantidad }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $pedido->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</body>
</html>
