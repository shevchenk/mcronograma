<script>
$(document).ready(function() {
    Plantillas.Cargar(activarTabla);
    HTMLtinymce();
    $('#plantillaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var titulo = button.data('titulo');
        plantilla_id = button.data('id');
        var Plantilla = PlantillaObj[plantilla_id];
        var modal = $(this);
        modal.find('.modal-title').text(titulo+' Plantilla');
        $('#form_plantilla [data-toggle="tooltip"]').css("display","none");
        $("#form_plantilla input[type='hidden']").remove();

        if(titulo=='Nuevo'){
            modal.find('.modal-footer .btn-primary').text('Guardar');
            modal.find('.modal-footer .btn-primary').attr('onClick','Agregar();');
        } else{
            modal.find('.modal-footer .btn-primary').text('Actualizar');
            modal.find('.modal-footer .btn-primary').attr('onClick','Editar();');
            $('#form_plantilla #txt_nombre').val( Plantilla.nombre );
            $('#form_plantilla #slct_estado').val( Plantilla.estado );
            Plantilla.cuerpo = ( Plantilla.cuerpo == null ) ? '' : Plantilla.cuerpo;
            CKEDITOR.instances.plantillaWord.setData( Plantilla.cuerpo );
            $("#form_plantilla").append("<input type='hidden' value='"+Plantilla.id+"' name='id'>");

        }
        $( "#form_plantilla #slct_estado" ).trigger('change');
        $( "#form_plantilla #slct_estado" ).change(function() {
            if ($( "#form_plantilla #slct_estado" ).val()==1) {
                $('#word').removeAttr('disabled');
            }
            else {
                $('#word').attr('disabled', 'disabled');
            }
        });
    });
    $('#plantillaModal').on('hide.bs.modal', function (event) {
        var modal = $(this);
        modal.find('.modal-body input').val('');
        CKEDITOR.instances.plantillaWord.setData( "" );
        $('#form_plantilla #slct_estado').val( 1 );
    });
});
activarTabla=function(){
    $("#t_plantilla").dataTable();
};
Editar=function(){
    if(validaPlantilla()){
        Plantillas.AgregarEditar(1);
    }
};
activar=function(id){
    Plantillas.CambiarEstado(id,1);
};
desactivar=function(id){
    Plantillas.CambiarEstado(id,0);
};
Agregar=function(){
    if(validaPlantilla()){
        Plantillas.AgregarEditar(0);
    }
};
validaPlantilla=function(){
    $('#form_plantilla [data-toggle="tooltip"]').css("display","none");
    var a=[];
    a[0]=valida("txt","nombre","");
    var rpta=true;
    for(i=0;i<a.length;i++){
        if(a[i]===false){
            rpta=false;
            break;
        }
    }
    return rpta;
};

valida=function(inicial,id,v_default){
    var texto="Seleccione";
    if(inicial=="txt"){
        texto="Ingrese";
    }
    if( $.trim($("#"+inicial+"_"+id).val())==v_default ){
        $('#error_'+id).attr('data-original-title',texto+' '+id);
        $('#error_'+id).css('display','');
        return false;
    }
};
HTMLCargar=function(datos){
    var html="";
    $('#t_plantilla').dataTable().fnDestroy();
    $.each(datos,function(index,data){
        estadohtml='<span id="'+data.id+'" onClick="activar('+data.id+')" class="btn btn-danger">Inactivo</span>';
        if(data.estado==1){
            estadohtml='<span id="'+data.id+'" onClick="desactivar('+data.id+')" class="btn btn-success">Activo</span>';
        }
        html+="<tr>"+
            "<td>"+data.nombre+"</td>"+
            "<td>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#plantillaModal" data-id="'+index+'" data-titulo="Editar"><i class="fa fa-edit fa-lg"></i> </a></td>';

        html+="</tr>";
    });
    $("#tb_plantilla").html(html);
    activarTabla();
};

HTMLtinymce=function(){

    CKEDITOR.replace( 'plantillaWord');

};
</script>