<?php

if (!isset($bitacora)) {
    $bitacora = array();
}

if (!isset($fecha_inicio)) {
    $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
}

if (!isset($fecha_fin)) {
    $fecha_fin = date('Y-m-d');
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de Cambios</title>
</head>
<body>

<h2>Historial de Cambios</h2>

<p>Total de registros: <?php echo count($bitacora); ?></p>

<form method="POST" action="?controlador=Bitacora&accion=filtrar">

    <label>Fecha inicial:</label>
    <input
        type="date"
        name="fecha_inicio"
        value="<?php echo $fecha_inicio; ?>"
    >

    <label>Fecha final:</label>
    <input
        type="date"
        name="fecha_fin"
        value="<?php echo $fecha_fin; ?>"
    >

    <button type="submit">Filtrar</button>

</form>

<br>

<table border="1" cellpadding="8" cellspacing="0">

    <tr>
        <th>ID</th>
        <th>Fecha y hora</th>
        <th>Acción</th>
        <th>Tabla afectada</th>
        <th>Usuario</th>
        <th>Correo</th>
    </tr>

    <?php if (count($bitacora) > 0) { ?>

        <?php foreach ($bitacora as $registro) { ?>

            <tr>
                <td>
                    <?php echo $registro['id_bitacora']; ?>
                </td>

                <td>
                    <?php echo $registro['fecha_hora']; ?>
                </td>

                <td>
                    <?php echo $registro['accion']; ?>
                </td>

                <td>
                    <?php echo $registro['tabla_afectada']; ?>
                </td>

                <td>
                    <?php echo $registro['nombre_usuario']; ?>
                </td>

                <td>
                    <?php echo $registro['correo_usuario']; ?>
                </td>
            </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>
            <td colspan="6">
                No hay registros en la bitácora.
            </td>
        </tr>

    <?php } ?>

</table>

<br>

<a href="?controlador=Index&accion=mostrar">Volver al inicio</a>

</body>
</html>