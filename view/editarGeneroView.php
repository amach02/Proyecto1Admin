<?php

include 'public/header.php';

/*
 * Compatibilidad con las dos formas en que View.php
 * puede enviar los datos.
 */
if (!isset($genero) && isset($data['genero'])) {
    $genero = $data['genero'];
}

if (!isset($familias) && isset($data['familias'])) {
    $familias = $data['familias'];
}

/*
 * Valores seguros para evitar:
 * Undefined variable
 * Undefined index
 */
$id_genero_actual = isset($genero['id_genero'])
    ? $genero['id_genero']
    : '';

$nombre_actual = isset($genero['nombre'])
    ? $genero['nombre']
    : '';

$id_familia_actual = isset($genero['id_familia'])
    ? $genero['id_familia']
    : '';

if (!isset($familias) || !is_array($familias)) {
    $familias = array();
}

$status = isset($_GET['status'])
    ? $_GET['status']
    : '';

?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Taxonomía: Editar Género
        </h2>

        <?php if ($status === 'invalido'): ?>

            <div class="alert alert-warning">
                Debe completar todos los campos.
            </div>

        <?php elseif ($status === 'error'): ?>

            <div class="alert alert-danger">
                No se pudo actualizar el género.
            </div>

        <?php endif; ?>

        <?php if (empty($id_genero_actual)): ?>

            <div class="alert alert-danger">
                No se encontraron los datos del género.
            </div>

            <a
                href="?controlador=Taxonomia&accion=mostrar&tab=generos"
                class="btn btn-secondary"
            >
                Volver
            </a>

        <?php else: ?>

            <form
                method="POST"
                action="?controlador=Genero&accion=editar"
            >

                <input
                    type="hidden"
                    name="id_genero"
                    value="<?php
                        echo htmlspecialchars(
                            $id_genero_actual,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>"
                >

                <div class="mb-3">

                    <label class="form-label">
                        Nombre del Género
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        class="form-control"
                        value="<?php
                            echo htmlspecialchars(
                                $nombre_actual,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Familia Jerárquica
                    </label>

                    <select
                        name="id_familia"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Seleccione una familia
                        </option>

                        <?php foreach ($familias as $familia): ?>

                            <?php
                            $id_familia = isset($familia['id_familia'])
                                ? $familia['id_familia']
                                : '';

                            $nombre_familia = isset($familia['nombre'])
                                ? $familia['nombre']
                                : 'Sin nombre';

                            $seleccionada = (
                                (string) $id_familia_actual
                                === (string) $id_familia
                            )
                                ? 'selected'
                                : '';
                            ?>

                            <option
                                value="<?php
                                    echo htmlspecialchars(
                                        $id_familia,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                <?php echo $seleccionada; ?>
                            >
                                <?php
                                    echo htmlspecialchars(
                                        $nombre_familia,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (empty($familias)): ?>

                        <div class="text-danger mt-2">
                            No hay familias registradas.
                        </div>

                    <?php endif; ?>

                </div>

                <button
                    type="submit"
                    class="btn btn-success"
                    <?php echo empty($familias) ? 'disabled' : ''; ?>
                >
                    Guardar cambios
                </button>

                <a
                    href="?controlador=Taxonomia&accion=mostrar&tab=generos"
                    class="btn btn-secondary"
                >
                    Volver
                </a>

            </form>

        <?php endif; ?>

    </div>

</div>

<?php include 'public/footer.php'; ?>