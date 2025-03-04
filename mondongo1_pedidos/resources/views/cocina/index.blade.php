<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas - Cocina</title>
    <script>
       async function marcarComoListo(pedidoId) {
    try {
        const response = await fetch(`api/pedidos/${pedidoId}/estado`, { // Ruta corregida
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ estado: 'entregado' })
        });
        
        if (response.ok) {
            alert('Pedido marcado como listo');
            location.reload(); // Recargar la página para actualizar la lista
        } else {
            alert('Error al actualizar el estado');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

    </script>
</head>
<body>
    <h1>Comandas en Cocina</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Productos</th>
                <th>Estado</th>
                <th>Acción</th>
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
                                <li>{{ $producto->nombre }} - {{ $producto->cantidad }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $pedido->estado }}</td>
                    <td>
                        @if($pedido->estado == 'pendiente')
                            <button onclick="marcarComoListo({{ $pedido->id }})">Marcar como listo</button>
                        @else
                            {{ $pedido->estado }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
