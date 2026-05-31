<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ubicación de Especímenes - Laboratorio</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body style="padding: 20px;">

    <div class="container" style="max-width: 600px; margin: 0 auto;">
        <div class="card" style="padding: 30px;">
            <h2>Registro de Ubicación Física</h2>
            <p class="text-muted" style="margin-bottom: 20px;">Gestión de infraestructura del laboratorio de entomología</p>

            <?php 
            $msgError = isset($vars['error']) ? $vars['error'] : (isset($error) ? $error : null);
            $msgSuccess = isset($vars['success']) ? $vars['success'] : (isset($success) ? $success : null);
            ?>

            <?php if (!empty($msgError)): ?>
                <div class="alert alert-danger" style="margin-bottom: 15px; padding: 12px; background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; border-radius: 6px;">
                    <?php echo htmlspecialchars(utf8_encode($msgError)); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($msgSuccess)): ?>
                <div class="alert alert-success" style="margin-bottom: 15px; padding: 12px; background-color: #dcfce7; color: #166534; border: 1px solid #4ade80; border-radius: 6px;">
                    <?php echo htmlspecialchars(utf8_encode($msgSuccess)); ?>
                </div>
            <?php endif; ?>

            <form action="?controlador=Inventario&accion=guardar" method="POST">
                
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display:block; margin-bottom:5px;">Código Único del Insecto (Código ID)</label>
                    <input type="text" name="codigo_id" class="form-control" required placeholder="Ej: UCR-ENT-003">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display:block; margin-bottom:5px;">Localización de Recolección</label>
                    <input type="text" name="localizacion" class="form-control" placeholder="Ej: Turrialba, Centro">
                </div>

                <div style="margin-bottom: 20px; display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label style="font-weight: bold; display:block; margin-bottom:5px;">Fecha Recolección</label>
                        <input type="date" name="fecha" class="form-control">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-weight: bold; display:block; margin-bottom:5px;">ID Especie (Opcional)</label>
                        <input type="number" name="id_especie" class="form-control" value="1">
                    </div>
                </div>

                <hr style="border-color: #cbd5e1; margin-bottom: 20px;">
                <h4 style="margin-bottom: 15px; color: #1e3a8a;">Estructura Jerárquica Obligatoria (CA 1)</h4>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display:block; margin-bottom:5px;">1. Seleccione el Gabinete</label>
                    <select id="gabinete" class="form-control" onchange="cambioGabinete()" required>
                        <option value="">-- Seleccione un Gabinete --</option>
                        <?php 
                        // Validación doble para evitar caídas según el motor de la clase View
                        if (isset($vars['gabinetes'])) {
                            $gabs = $vars['gabinetes'];
                        } elseif (isset($gabinetes)) {
                            $gabs = $gabinetes;
                        } else {
                            $gabs = array();
                        }

                        if (is_array($gabs)) {
                            foreach ($gabs as $gab) {
                                $desc = isset($gab['descripcion']) && $gab['descripcion'] !== null ? $gab['descripcion'] : 'Sin descripción';
                                echo '<option value="' . htmlspecialchars($gab['id_gabinete']) . '">';
                                echo htmlspecialchars($gab['codigo']) . " - " . htmlspecialchars($desc);
                                echo '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display:block; margin-bottom:5px;">2. Seleccione la Gaveta</label>
                    <select id="gaveta" class="form-control" onchange="cambioGaveta()" disabled required>
                        <option value="">-- Debe seleccionar un gabinete primero --</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display:block; margin-bottom:5px;">3. Seleccione la Caja</label>
                    <select id="caja" class="form-control" onchange="cambioCaja()" disabled required>
                        <option value="">-- Debe seleccionar una gaveta primero --</option>
                    </select>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="font-weight: bold; display:block; margin-bottom:5px;">4. Seleccione el Vial Destino</label>
                    <select id="vial" name="id_vial" class="form-control" disabled required>
                        <option value="">-- Debe seleccionar una caja primero --</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Registrar Espécimen en Infraestructura</button>
            </form>
        </div>
    </div>

    <script>
        function cambioGabinete() {
            var id = document.getElementById("gabinete").value;
            var gavetaSelect = document.getElementById("gaveta");
            resetearSelect(gavetaSelect, "-- Seleccione una Gaveta --");
            resetearSelect(document.getElementById("caja"), "-- Debe seleccionar una gaveta primero --");
            resetearSelect(document.getElementById("vial"), "-- Debe seleccionar una caja primero --");

            if(id === "") return;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var datos = JSON.parse(this.responseText);
                    datos.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id_gaveta;
                        option.text = item.codigo;
                        gavetaSelect.appendChild(option);
                    });
                    gavetaSelect.disabled = false;
                }
            };
            xmlhttp.open("GET", "?controlador=Inventario&accion=cargarGavetas&id_gabinete=" + id, true);
            xmlhttp.send();
        }

        function cambioGaveta() {
            var id = document.getElementById("gaveta").value;
            var cajaSelect = document.getElementById("caja");
            resetearSelect(cajaSelect, "-- Seleccione una Caja --");
            resetearSelect(document.getElementById("vial"), "-- Debe seleccionar una caja primero --");

            if(id === "") return;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var datos = JSON.parse(this.responseText);
                    datos.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id_caja;
                        option.text = item.codigo;
                        cajaSelect.appendChild(option);
                    });
                    cajaSelect.disabled = false;
                }
            };
            xmlhttp.open("GET", "?controlador=Inventario&accion=cargarCajas&id_gaveta=" + id, true);
            xmlhttp.send();
        }

        function cambioCaja() {
            var id = document.getElementById("caja").value;
            var vialSelect = document.getElementById("vial");
            resetearSelect(vialSelect, "-- Seleccione un Vial --");

            if(id === "") return;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var datos = JSON.parse(this.responseText);
                    datos.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id_vial;
                        option.text = item.codigo;
                        vialSelect.appendChild(option);
                    });
                    vialSelect.disabled = false;
                }
            };
            xmlhttp.open("GET", "?controlador=Inventario&accion=cargarViales&id_caja=" + id, true);
            xmlhttp.send();
        }

        function resetearSelect(selectElement, texto) {
            selectElement.innerHTML = "";
            var option = document.createElement("option");
            option.value = "";
            option.text = texto;
            selectElement.appendChild(option);
            selectElement.disabled = true;
        }
    </script>
</body>
</html>