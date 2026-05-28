<?php include 'public/header.php'; ?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Registrar Producto
        </h2>

        <?php
        $status = isset($_GET['status'])
            ? $_GET['status']
            : '';
        ?>

        <?php if ($status == 'success'): ?>

            <div class="alert alert-success">
                Producto registrado correctamente.
            </div>

        <?php elseif ($status == 'error'): ?>

            <div class="alert alert-danger">
                Error al registrar el producto.
            </div>

        <?php elseif ($status == 'duplicado'): ?>

            <div class="alert alert-danger">
                El código ya existe.
            </div>AC

        <?php elseif ($status == 'precio'): ?>

            <div class="alert alert-warning">
                El precio debe ser mayor a cero.
            </div>

        <?php elseif ($status == 'invalido'): ?>

            <div class="alert alert-warning">
                Todos los campos son obligatorios.
            </div>

        <?php endif; ?>

        <form
            method="POST"
            action="?controlador=Producto&accion=registrar"
            onsubmit="return confirmarProducto()">

            <div class="mb-3">

                <label class="form-label">
                    Código
                </label>

                <input
                    type="text"
                    name="codigo"
                    id="codigo"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Marca
                </label>

                <input
                    type="text"
                    name="marca"
                    id="marca"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    id="descripcion"
                    class="form-control"
                    required></textarea>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Precio
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="precio"
                    id="precio"
                    class="form-control"
                    required>
            </div>

            <button
                type="submit"
                class="btn btn-primary">
                Guardar Producto
            </button>

            <a
                href="?controlador=Index&accion=mostrar"
                class="btn btn-secondary">
                Volver
            </a>

        </form>

    </div>

</div>

<script>
    function confirmarProducto() {

        let codigo =
            document.getElementById('codigo').value;

        let marca =
            document.getElementById('marca').value;

        let descripcion =
            document.getElementById('descripcion').value;

        let precio =
            document.getElementById('precio').value;

        if (
            codigo.trim() == '' ||
            marca.trim() == '' ||
            descripcion.trim() == '' ||
            precio.trim() == ''
        ) {

            alert(
                'Todos los campos son obligatorios'
            );

            return false;
        }

        if (parseFloat(precio) <= 0) {

            alert(
                'El precio debe ser mayor a cero'
            );

            return false;
        }

        return confirm(
            '¿Desea registrar este producto?'
        );
    }
</script>

<?php include 'public/footer.php'; ?>