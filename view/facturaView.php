<?php include 'public/header.php'; ?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Facturación
        </h2>

        <!-- Código factura -->

        <div class="mb-3">

            <label class="form-label">
                Código Factura
            </label>

            <input
                type="text"
                id="codigoFactura"
                class="form-control"
                value="<?php echo $codigoFactura; ?>"
                readonly>
        </div>

        <!-- Buscar cliente -->

        <div class="row">

            <div class="col-md-4">

                <label class="form-label">
                    Cédula Cliente
                </label>

                <input
                    type="text"
                    id="cedula"
                    class="form-control">
            </div>

            <div class="col-md-2 d-flex align-items-end">

                <button
                    class="btn btn-primary"
                    onclick="buscarCliente()">
                    Buscar
                </button>
            </div>
        </div>

        <div class="row mt-3">

            <div class="col-md-6">

                <label>
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombreCliente"
                    class="form-control"
                    readonly>
            </div>

            <div class="col-md-6">

                <label>
                    Apellidos
                </label>

                <input
                    type="text"
                    id="apellidosCliente"
                    class="form-control"
                    readonly>
            </div>
        </div>

        <hr>

        <!-- Buscar producto -->

        <div class="row">

            <div class="col-md-4">

                <label>
                    Buscar Marca
                </label>

                <input
                    type="text"
                    id="marcaBuscar"
                    class="form-control">
            </div>

            <div class="col-md-2 d-flex align-items-end">

                <button
                    class="btn btn-success"
                    onclick="buscarProducto()">
                    Buscar
                </button>
            </div>
        </div>

        <!-- Resultados -->

        <div class="mt-3">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Código</th>
                        <th>Marca</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody id="resultadoProductos">

                </tbody>

            </table>

        </div>

        <hr>

        <!-- Detalle factura -->

        <h4>
            Detalle Factura
        </h4>

        <table class="table table-striped">

            <thead>

                <tr>
                    <th>Código</th>
                    <th>Marca</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>

            </thead>

            <tbody id="detalleFactura">

            </tbody>

        </table>

        <h4 class="text-end">
            Total:
            ₡<span id="totalFactura">0</span>
        </h4>

        <div class="text-end">

            <button
                class="btn btn-primary"
                onclick="guardarFactura()">
                Guardar Factura
            </button>
        </div>

    </div>

</div>

<script>
    let productosFactura = [];

    function buscarCliente() {

        let cedula =
            document.getElementById('cedula').value;

        fetch(
                '?controlador=Cliente&accion=buscarAjax&cedula=' + cedula
            )
            .then(response => response.json())
            .then(data => {

                if (data) {

                    document.getElementById('nombreCliente')
                        .value = data.nombre;

                    document.getElementById('apellidosCliente')
                        .value = data.apellidos;

                } else {

                    alert('Cliente no encontrado');
                }
            });
    }

    function buscarProducto() {

        let marca =
            document.getElementById('marcaBuscar').value;

        fetch(
                '?controlador=Producto&accion=buscarAjax&marca=' + marca
            )
            .then(response => response.json())
            .then(data => {

                let html = '';

                data.forEach(producto => {

                    html += `
                <tr>

                    <td>${producto.codigo}</td>
                    <td>${producto.marca}</td>
                    <td>${producto.descripcion}</td>
                    <td>${producto.precio}</td>

                    <td>

                        <button
                            class="btn btn-success"
                            onclick='agregarProducto(
                                "${producto.codigo}",
                                "${producto.marca}",
                                ${producto.precio}
                            )'
                        >
                            Agregar
                        </button>

                    </td>

                </tr>
            `;
                });

                document.getElementById(
                    'resultadoProductos'
                ).innerHTML = html;
            });
    }

    function agregarProducto(
        codigo,
        marca,
        precio
    ) {

        let cantidad =
            prompt('Cantidad');

        if (
            cantidad == null ||
            cantidad <= 0
        ) {
            return;
        }

        let subtotal =
            cantidad * precio;

        productosFactura.push({
            codigo,
            marca,
            cantidad,
            precio
        });

        let html = '';

        let total = 0;

        productosFactura.forEach(producto => {

            let subtotal =
                producto.cantidad * producto.precio;

            total += subtotal;

            html += `
            <tr>

                <td>${producto.codigo}</td>
                <td>${producto.marca}</td>
                <td>${producto.cantidad}</td>
                <td>${producto.precio}</td>
                <td>${subtotal}</td>

            </tr>
        `;
        });

        document.getElementById(
            'detalleFactura'
        ).innerHTML = html;

        document.getElementById(
            'totalFactura'
        ).innerText = total;
    }

    function guardarFactura() {

        if (productosFactura.length == 0) {

            alert(
                'Debe agregar productos'
            );

            return;
        }

        if (
            !confirm(
                '¿Guardar factura?'
            )
        ) {
            return;
        }

        fetch(
                '?controlador=Factura&accion=registrar', {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json'
                    },

                    body: JSON.stringify({

                        codigoFactura: document.getElementById(
                            'codigoFactura'
                        ).value,

                        cedula: document.getElementById(
                            'cedula'
                        ).value,

                        productos: productosFactura
                    })
                }
            )
            .then(response => response.text())
            .then(data => {

                console.log(data);

                try {

                    let resultado = JSON.parse(data);

                    if (resultado.success) {

                        alert('Factura guardada');

                        location.reload();

                    } else {

                        alert(resultado.mensaje);
                    }

                } catch (error) {

                    console.log('ERROR PHP REAL:');
                    console.log(data);
                }

            });
    }
</script>

<?php include 'public/footer.php'; ?>