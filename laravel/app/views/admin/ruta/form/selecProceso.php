<template id="bs-modal">
    <!-- MODAL -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            <form id="form_flujos" name="form_flujos" action="" method="post">
              <div class="form-group">
                  <label class="control-label">Categoria:
                    <a id="error_categoria" style="display:none" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Seleccione Categoria">
                        <i class="fa fa-exclamation"></i>
                    </a>
                  </label>
                  <select class="form-control" name="slct_categoria_id" id="slct_categoria_id">
                  </select>
              </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="greet">Save changes</button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    Vue.component('modal', {
        template: '#bs-modal',
        data: function () {
            console.log("### DATA");
        },
        methods: {
          greet: function (event) {
            $("#cartainicio").css("display","");
            $("#txt_nro_carta").focus();
            var datos={area_id:AreaIdG};
            Carta.CargarCorrelativo(HTMLCargarCorrelativo,datos);
          }
        },
    });

    var vm = new Vue({
      el: '#el',
      data: {
        query: "select * from clients;",
      },
    });

</script>