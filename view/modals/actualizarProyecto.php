<link rel="stylesheet" href="public/css/modal.css">


<div id="editarProyecto" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Proyecto</h2>
            <span class="close-modal">&times;</span>
        </div>
        <form id="formularioEditarProyecto">
            <input type="hidden" id="nombreOriginal">
            
            <div class="form-group">
                <label>Nombre del Proyecto</label>
                <input type="text" id="nombre" required>
            </div>
            
            <div class="form-row split">
                <div class="form-group">
                    <label>Estado</label>
                    <select id="estado">
                        <option value="completado">Completado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en-progreso">En progreso</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="text" id="fecha" required>
                </div>
            </div>
            
            <hr class="form-divider">
            <div class="form-actions-right">
                <button type="submit" class="btn-save">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>