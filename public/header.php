<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorio Entomología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

<?php
    require_once 'libs/Auth.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $rol = Auth::rolActual();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="index.php">Laboratorio Entomología</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">

            <?php if (Auth::tienePermiso($rol, 'Especimen', 'mostrarListar')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Especímenes</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="?controlador=Especimen&accion=mostrarListar">Listar</a></li>
                    <?php if (Auth::tienePermiso($rol, 'Especimen', 'mostrarRegistrar')): ?>
                    <li><a class="dropdown-item" href="?controlador=Especimen&accion=mostrarRegistrar">Registrar</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (Auth::tienePermiso($rol, 'Taxonomia', 'mostrar')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Taxonomía</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="?controlador=Taxonomia&accion=mostrar&tab=ordenes">Órdenes</a></li>
                    <li><a class="dropdown-item" href="?controlador=Taxonomia&accion=mostrar&tab=familias">Familias</a></li>
                    <li><a class="dropdown-item" href="?controlador=Taxonomia&accion=mostrar&tab=generos">Géneros</a></li>
                    <li><a class="dropdown-item" href="?controlador=Taxonomia&accion=mostrar&tab=especies">Especies</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (Auth::tienePermiso($rol, 'Infraestructura', 'mostrar')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Infraestructura</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="?controlador=Infraestructura&accion=mostrar&tab=gabinetes">Gabinetes</a></li>
                    <li><a class="dropdown-item" href="?controlador=Infraestructura&accion=mostrar&tab=cajas">Cajas</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (Auth::tienePermiso($rol, 'Usuario', 'mostrarListar')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Usuarios</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="?controlador=Usuario&accion=mostrarListar">Listar</a></li>
                    <?php if (Auth::tienePermiso($rol, 'Usuario', 'mostrarRegistrar')): ?>
                    <li><a class="dropdown-item" href="?controlador=Usuario&accion=mostrarRegistrar">Registrar</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['nombre'])): ?>
            <li class="nav-item d-flex align-items-center gap-3 ms-3">
                <span class="text-white" style="font-size:14px">
                    <?php echo $_SESSION['nombre']; ?>
                    <span class="badge bg-secondary ms-1">
                        <?php echo $_SESSION['nombre_rol']; ?>
                    </span>
                </span>
                <a href="?controlador=Session&accion=logout"
                   class="btn btn-outline-light btn-sm">
                    Cerrar sesión
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>

<div id="toast-container" style="position:fixed;top:80px;right:20px;z-index:9999;"></div>