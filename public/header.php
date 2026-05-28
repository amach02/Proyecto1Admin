<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclo Turrialba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="index.php">Ciclo Turrialba</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="?controlador=Producto&accion=mostrar">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?controlador=Cliente&accion=mostrar">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?controlador=Factura&accion=mostrar">Facturar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?controlador=Reporte&accion=mostrar">Reportes</a>
            </li>
        </ul>
    </div>
</nav>
<div id="toast-container" style="position:fixed;top:80px;right:20px;z-index:9999;"></div>