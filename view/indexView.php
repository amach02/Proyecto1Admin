<?php include 'public/header.php'; ?>

<div class="container mt-5">

    <div class="text-center mb-5">

        <h1>
            Bienvenido a Ciclo Turrialba
        </h1>

        <p class="text-muted">
            Sistema de ventas e inventario
        </p>

    </div>

    <div class="card shadow p-4">

        <h3 class="mb-4">
            Catálogo de Productos de la tienda
        </h3>

        <table class="table table-hover table-bordered">

            <thead class="table-dark">

                <tr>
                    <th>Código</th>
                    <th>Marca</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach($productos as $producto): ?>

                    <tr>

                        <td>
                            <?php echo $producto['codigo']; ?>
                        </td>

                        <td>
                            <?php echo $producto['marca']; ?>
                        </td>

                        <td>
                            <?php echo $producto['descripcion']; ?>
                        </td>

                        <td>
                            ₡<?php echo number_format(
                                $producto['precio'],
                                2
                            ); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include 'public/footer.php'; ?>