<form class="was-validated " id="form_edicion">
    <!-- The Modal -->
    <div class="modal fade" id="modal_edicion">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-center"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <input type="text" hidden class="form-control" id="id_precio" name="id_precio">
					
					<div class="form-group">
                        <label for="nombre_origenes">Destino</label>
                        <input type="text" class="form-control" id="destino" name="destino"
                               placeholder="Nombre del origen" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre_origenes">Zona</label>
                        <input type="text" class="form-control" id="zona" name="zona"
                             required>
                    </div>
					
                   
					<div class="form-group">
                        <label for="nombre_origenes">Precio Sedán</label>
                        <input type="number" step="any" class="form-control" id="precio" name="precio"
                               required>
                    </div>
					<div class="form-group">
                        <label for="nombre_origenes">Precio Ejecutiva</label>
                        <input type="number" step="any" class="form-control" id="precio_ejecutiva" name="precio_ejecutiva"
                              required>
                    </div>
                   
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-success"><i class="fa fa-save"></i> Guardar</button>
                </div>

            </div>
        </div>
    </div>
</form>
