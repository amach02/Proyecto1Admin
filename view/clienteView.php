<?php include 'public/header.php'; ?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Registrar Cliente
        </h2>

        <?php
        $status = isset($_GET['status'])
            ? $_GET['status']
            : '';
        ?>

        <?php if($status == 'success'): ?>

            <div class="alert alert-success">
                Cliente registrado correctamente.
            </div>

        <?php elseif($status == 'error'): ?>

            <div class="alert alert-danger">
                Error al registrar el cliente.
            </div>

        <?php elseif($status == 'invalido'): ?>

            <div class="alert alert-warning">
                Todos los campos son obligatorios.
            </div>

        <?php elseif($status == 'duplicado'): ?>

            <div class="alert alert-danger">
                La cédula ya existe.
            </div>

        <?php endif; ?>



        <form
            method="POST"
            action="?controlador=Cliente&accion=registrar"
            id="formCliente"
            onsubmit="return confirmarRegistro()"
        >

            <div class="mb-3">

                <label class="form-label">
                    Cédula
                </label>

                <input
                    type="text"
                    name="cedula"
                    id="cedula"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Apellidos
                </label>

                <input
                    type="text"
                    name="apellidos"
                    id="apellidos"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Dirección
                </label>

                <textarea
                    name="direccion"
                    id="direccion"
                    class="form-control"
                    required
                ></textarea>
            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar Cliente
            </button>

            <a
                href="?controlador=Index&accion=mostrar"
                class="btn btn-secondary"
            >
                Volver
            </a>

        </form>

    </div>

</div>
<script>

function confirmarRegistro(){

    let cedula =
        document.getElementById('cedula').value;

    let nombre =
        document.getElementById('nombre').value;

    let apellidos =
        document.getElementById('apellidos').value;

    // Validación extra
    if(
        cedula.trim() == '' ||
        nombre.trim() == '' ||
        apellidos.trim() == ''
    ){

        alert(
            'Todos los campos son obligatorios'
        );

        return false;
    }

    return confirm(
        '¿Desea registrar este cliente?'
    );
}

</script>

<?php include 'public/footer.php'; ?>