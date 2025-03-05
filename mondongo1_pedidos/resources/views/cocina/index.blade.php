<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas - Cocina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        :root {
            --color-primario: #2C3E50;
            --color-secundario: #18BC9C;
            --color-acento: #F39C12;
            --color-fondo: #ECF0F1;
            --color-texto: #34495E;
            --color-exito: #27AE60;
            --color-error: #E74C3C;
            --color-advertencia: #E67E22;
        }

        body {
            background-color: var(--color-fondo);
            color: var(--color-texto);
        }

        .section-comandas {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section-comandas h1 {
            color: var(--color-primario);
            text-align: center;
            margin-bottom: 20px;
        }

        .table-comandas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-comandas th,
        .table-comandas td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table-comandas th {
            background-color: var(--color-primario);
            color: white;
            font-weight: bold;
        }

        .table-comandas tr:hover {
            background-color: #f1f1f1;
        }

        .btn-marcar-listo {
            background-color: var(--color-secundario);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-marcar-listo:hover {
            background-color: #128f76;
        }

        .estado-pendiente {
            color: var(--color-advertencia);
            font-weight: bold;
        }

        .estado-entregado {
            color: var(--color-exito);
            font-weight: bold;
        }

        @media (max-width: 767.98px) {
            .table-comandas th,
            .table-comandas td {
                padding: 6px;
                font-size: 12px;
            }

            .btn-marcar-listo {
                padding: 6px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <section class="container section-comandas">
        <h1>Comandas en Cocina</h1>
        <table class="table-comandas">
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
                        <td class="{{ $pedido->estado == 'pendiente' ? 'estado-pendiente' : 'estado-entregado' }}">
                            {{ $pedido->estado }}
                        </td>
                        <td>
                            @if($pedido->estado == 'pendiente')
                                <button class="btn-marcar-listo" onclick="marcarComoListo({{ $pedido->id }})">Marcar como listo</button>
                            @else
                                {{ $pedido->estado }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <script>
        async function marcarComoListo(pedidoId) {
            try {
                const response = await fetch(`/api/pedidos/${pedidoId}/estado`, {
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
</body>
</html>