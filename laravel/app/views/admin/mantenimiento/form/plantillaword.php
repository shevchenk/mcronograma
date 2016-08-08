<!-- /.modal -->
<div class="modal fade" id="plantillaModal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header logo">
        <button class="btn btn-sm btn-default pull-right" data-dismiss="modal">
            <i class="fa fa-close"></i>
        </button>
        <h4 class="modal-title">New message</h4>
      </div>
      <div class="modal-body">
        <form id="form_plantilla" name="form_plantilla" action="plantilla/editar" method="post">
          <div class="row">
            <div class="col-xs-6">
              <div class="form-group">
                  <label class="control-label">Nombre
                      <a id="error_nombre" style="display:none" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Ingrese Nombre">
                          <i class="fa fa-exclamation"></i>
                      </a>
                  </label>
                  <input type="text" class="form-control" placeholder="Ingrese Nombre" name="txt_nombre" id="txt_nombre">
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                  <label class="control-label">Estado:
                  </label>
                  <select class="form-control" name="slct_estado" id="slct_estado">
                      <option value='0'>Inactivo</option>
                      <option value='1' selected>Activo</option>
                  </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                  <textarea id="plantillaWord" name="word" class="form-control" rows="6"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- /.modal -->