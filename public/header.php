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

            <?php if (Auth::tienePermiso($rol, 'Planta', 'mostrarListar')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Plantas Hospedadoras</a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="?controlador=Planta&accion=mostrarListar">Listar</a></li>
                    <?php if (Auth::tienePermiso($rol, 'Planta', 'mostrarRegistrar')): ?>
                    <li><a class="dropdown-item" href="?controlador=Planta&accion=mostrarRegistrar">Registrar</a></li>
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

            <li class="nav-item">
    <a class="nav-link text-warning" href="?controlador=Bitacora&accion=index">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history mb-1" viewBox="0 0 16 16">
            <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
            <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
        </svg>
        Historial de Cambios
    </a>
</li>

        </ul>
    </div>
</nav>

<div id="toast-container" style="position:fixed;top:80px;right:20px;z-index:9999;"></div>