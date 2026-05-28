
<div class="modal fade" id="modalRetiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 12px;">
      
      <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #eee;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">
            <i class="fa-solid fa-box-open"></i> Registrar Salida de Inventario
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form action="?controlador=Retiro&accion=procesarRetiro" method="POST">
  <div class="modal-body" style="padding: 20px;">
    
    <div class="form-group">
    <label style="font-weight: 600;">
        Cédula del Beneficiario:
    </label>

    <input 
        type="text"
        name="id_beneficiario"
        class="form-control"
        placeholder="Ej: 102340567"
        required

        pattern="^[0-9]{1,9}$"

        maxlength="9"

        title="La cédula debe contener únicamente números y máximo 9 dígitos."
    >

    <small class="text-muted">
        Ingrese únicamente números.
    </small>
</div>

    <div class="form-group">
      <label style="font-weight: 600;">Producto a retirar:</label>
      <select name="id_producto" class="form-control" required>
    <option value="">-- Seleccione un producto --</option>
    
   <?php foreach($productos as $prod): ?>
    <option value="<?php echo $prod['ID_producto']; ?>">
        <?php echo $prod['nombre']; ?>
        (Disponible: <?php echo $prod['inventario']; ?>)
    </option>
<?php endforeach; ?>
    
</select>
    </div>

    <div class="form-group">
      <label style="font-weight: 600;">Cantidad a retirar:</label>
      <input type="number" name="cantidad" class="form-control" min="1" required placeholder="0">
    </div>

  </div>
  
  <div class="modal-footer" style="background-color: #f8f9fa;">
    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    
    <button type="submit" class="btn btn-success" style="background-color: #28a745; font-weight: bold;">
        Confirmar Retiro
    </button>
</div>
</form>
    </div>
  </div>
</div>