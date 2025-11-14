<div class="container">
    <h1>Listado de Notas de Compra y Venta</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>FECHA</th>
                <th>TIPO NOTA</th>
                <th>CLIENTE/PROVEEDOR</th>
                <th>impuesto</th>
                <th>DES</th>
                <th>USUARIO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notas as $nota)
                <tr>
                    <td>{{ $nota['id'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($nota['fecha'])->format('d/m/Y') }}</td>
                    <td>{{ $nota['tipo_nota'] }}</td>
                    <td>{{ $nota['cliente']['razon_social'] }}</td>
                    <td>{{ $nota['impuesto'] }}</td>
                    <td>{{ $nota['descuento'] }}</td>
                    <td>{{ $nota['user']['name'] }}</td>
                </tr>
            @endforeach

        </tbody>

    </table>
</div>

<style>
    table{
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    table, th, td{
        border: 1px solid #ddd;
    }

    th, td{
        padding: 8px;
        text-align: center;
    }
</style>