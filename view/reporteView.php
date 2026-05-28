<?php include 'public/header.php'; ?>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Reporte de Ventas
        </h2>

        <!-- Formulario -->

        <form method="GET">

            <input
                type="hidden"
                name="controlador"
                value="Reporte"
            >

            <input
                type="hidden"
                name="accion"
                value="buscar"
            >

            <div class="row">

                <div class="col-md-4">

                    <label class="form-label">
                        Cédula Cliente
                    </label>

                    <input
                        type="text"
                        name="cedula"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Buscar
                    </button>

                </div>

            </div>

        </form>

        <hr>

        <?php if(isset($reporte)): ?>

            <?php if(count($reporte) > 0): ?>

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>Factura</th>
                            <th>Fecha</th>
                            <th>Marca</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $total = 0;
                        ?>

                        <?php foreach($reporte as $fila): ?>

                            <?php
                            $total += $fila['subtotal'];
                            ?>

                            <tr>

                                <td>
                                    <?php echo $fila['codigo']; ?>
                                </td>

                                <td>
                                    <?php echo $fila['fecha']; ?>
                                </td>

                                <td>
                                    <?php echo $fila['marca']; ?>
                                </td>

                                <td>
                                    <?php echo $fila['descripcion']; ?>
                                </td>

                                <td>
                                    <?php echo $fila['cantidad']; ?>
                                </td>

                                <td>
                                    ₡<?php echo number_format(
                                        $fila['precio_unitario'],
                                        2
                                    ); ?>
                                </td>

                                <td>
                                    ₡<?php echo number_format(
                                        $fila['subtotal'],
                                        2
                                    ); ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

                <div class="text-end">

                    <h4>

                        Total General:

                        ₡<?php
                        echo number_format(
                            $total,
                            2
                        );
                        ?>

                    </h4>

                </div>

            <?php else: ?>

                <div class="alert alert-warning">

                    No hay registros para esa cédula.

                </div>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>

<?php include 'public/footer.php'; ?>