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

if (!function_exists('textoSeguro')) {
    function textoSeguro($valor)
    {
        if ($valor === null) {
            return '';
        }

        $valor = (string) $valor;

        if (!preg_match('//u', $valor)) {
            $valor = utf8_encode($valor);
        }

        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de Cambios</title>

    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #e0f2fe, #f8fafc);
            color: #1e293b;
        }

        .contenedor-bitacora {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .encabezado-bitacora {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 18px;
            margin-bottom: 25px;
        }

        .encabezado-bitacora h1 {
            margin: 0;
            color: #0f172a;
            font-size: 28px;
        }

        .encabezado-bitacora p {
            margin: 6px 0 0;
            color: #64748b;
        }

        .contador {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 10px 18px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 14px;
        }

        .filtros {
            background: #f1f5f9;
            padding: 18px;
            border-radius: 14px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filtros label {
            font-weight: bold;
            color: #334155;
        }

        .filtros input {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }

        .filtros button {
            padding: 9px 18px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .filtros button:hover {
            background: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 14px;
            background: white;
        }

        thead {
            background: #1e3a8a;
            color: white;
        }

        th {
            padding: 13px;
            text-align: center;
            font-size: 14px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .col-id {
            text-align: center;
            font-weight: bold;
            color: #1d4ed8;
        }

        .col-fecha {
            white-space: nowrap;
            color: #475569;
        }

        .col-accion {
            min-width: 330px;
        }

        .badge-tabla {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
        }

        .usuario {
            font-weight: bold;
            color: #0f172a;
        }

        .correo {
            color: #64748b;
            font-size: 13px;
        }

        .sin-registros {
            text-align: center;
            padding: 30px;
            color: #64748b;
            font-weight: bold;
        }

        .acciones-finales {
            margin-top: 25px;
        }

        .btn-volver {
            display: inline-block;
            text-decoration: none;
            background: #64748b;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-volver:hover {
            background: #475569;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .contenedor-bitacora {
                padding: 20px;
            }

            .encabezado-bitacora {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 9px;
            }
        }
    </style>
</head>

<body>

<div class="contenedor-bitacora">

    <div class="encabezado-bitacora">
        <div>
            <h1>Historial de Cambios</h1>
            <p>Registro de acciones realizadas dentro del sistema.</p>
        </div>

        <div class="contador">
            Total de registros: <?php echo count($bitacora); ?>
        </div>
    </div>

    <form class="filtros" method="POST" action="?controlador=Bitacora&accion=filtrar">

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

        <button type="submit">
            Filtrar
        </button>

    </form>

    <div style="overflow-x: auto;">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha y hora</th>
                    <th>Acción</th>
                    <th>Tabla afectada</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                </tr>
            </thead>

            <tbody>

                <?php if (count($bitacora) > 0) { ?>

                    <?php foreach ($bitacora as $registro) { ?>

                        <tr>
                            <td class="col-id">
                                <?php echo textoSeguro($registro['id_bitacora']); ?>
                            </td>

                            <td class="col-fecha">
                                <?php echo textoSeguro($registro['fecha_hora']); ?>
                            </td>

                            <td class="col-accion">
                                <?php echo textoSeguro($registro['accion']); ?>
                            </td>

                            <td>
                                <span class="badge-tabla">
                                    <?php echo textoSeguro($registro['tabla_afectada']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="usuario">
                                    <?php echo textoSeguro($registro['nombre_usuario']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="correo">
                                    <?php echo textoSeguro($registro['correo_usuario']); ?>
                                </span>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6" class="sin-registros">
                            No hay registros en la bitácora para las fechas seleccionadas.
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>

    </div>

    <div class="acciones-finales">
        <a class="btn-volver" href="?controlador=Index&accion=mostrar">
            Volver al inicio
        </a>
    </div>

</div>

</body>
</html>