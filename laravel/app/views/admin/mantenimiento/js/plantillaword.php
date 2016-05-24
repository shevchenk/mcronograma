<script>
/*
tinymce.init({
    selector: '#word'
    //,plugin: 'a_tinymce_plugin'
    ,a_plugin_option: true
    ,a_configuration_option: 400
    //,inline: true
    ,plugins : 'advlist autolink link image lists charmap print preview'
});*/

/*
tinymce.init({
    selector: '#word',
    theme: 'modern',
   // width: 800,
    skin: 'lightgray',
    height: 300,
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'save table contextmenu directionality emoticons template paste textcolor'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
  });

*/
/*
tinymce.init({
  selector: '#word',
  theme: 'modern',
  height: 400,
  browser_spellcheck: true,
  spellchecker_language: 'sv_SE',
  contextmenu: false,
  //mages_upload_url: 'postAcceptor.php',
  //images_upload_base_path: '/some/basepath',
  //images_upload_credentials: true
  plugins: [
    'advlist autolink lists link image charmap print preview anchor pagebreak spellchecker',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
});*/
/*
tinyMCE.init({
    //selector: '#word',
   mode : 'textareas',
   theme : 'ribbon',
   content_css : 'css/editor.css',
   plugins : 'bestandsbeheer,tabfocus,advimagescale,loremipsum,image_tools,embed,tableextras,style,table,inlinepopups,searchreplace,contextmenu,paste,wordcount,advlist,autosave',
   inlinepopups_skin : 'ribbon_popup'
});*/


tinyMCE.init({
   mode : 'textareas',
   theme : 'ribbon',
   //content_css : 'css/editor.css',
   height: 600,
   //width: 1200,
   plugins : 'bestandsbeheer,tabfocus,advimagescale,loremipsum,image_tools,embed,tableextras,style,table,inlinepopups,searchreplace,contextmenu,paste,wordcount,advlist,autosave',
   inlinepopups_skin : 'ribbon_popup',

   theme_ribbon_tab1 : {   title : "Start",
                            items : [
                                    ["paste"],
                                    ["justifyleft,justifycenter,justifyright,justifyfull",
                                     "bullist,numlist",
                                     "|",
                                     "bold,italic,underline",
                                     "outdent,indent"],
                                    ["paragraph", "heading1", "heading2", "heading3"],
                                    ["search", "|", "replace", "|", "removeformat"]]
                        },



    theme_ribbon_tab2 : {   title : "Insert",
                            items : [["tabledraw"],
                                    ["image", "bestandsbeheer_file", "bestandsbeheer_video", "bestandsbeheer_mp3"],
                                    ["embed"],
                                    ["link", "|", "unlink", "|", "anchor"],
                                    ["loremipsum", "|", "charmap", "|", "hr"]]
                        },

    theme_ribbon_tab3 : {   title : "Source",
                            source : true

                        },

    theme_ribbon_tab4 : {   title : "Image",
                            bind :  "img",
                            items : [["image_float_left", "image_float_right", "image_float_none"],
                                    ["image_alt"],
                                    ["image_width_plus", "|", "image_width_min", "|", "image_width_original"],
                                    ["image_edit", "|", "image_remove"]]
                        }

});
$(document).ready(function() {
    Plantillas.Cargar(activarTabla);

    $('#plantillaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var titulo = button.data('titulo');
        plantilla_id = button.data('id');


        var modal = $(this);
        modal.find('.modal-title').text(titulo+' Plantilla');
        $('#form_plantilla [data-toggle="tooltip"]').css("display","none");
        $("#form_plantilla input[type='hidden']").remove();

        if(titulo=='Nuevo'){
            modal.find('.modal-footer .btn-primary').text('Guardar');
            modal.find('.modal-footer .btn-primary').attr('onClick','Agregar();');

        } else{
            //leer path del archivo y mostrarlo en e texarea
            Plantillas.CargarDetalle(plantilla_id);

            modal.find('.modal-footer .btn-primary').text('Actualizar');
            modal.find('.modal-footer .btn-primary').attr('onClick','Editar();');
            $("#form_plantilla").append("<input type='hidden' value='"+button.data('id')+"' name='id'>");

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
        modal.find('.modal-body textarea').val('');
        $('#word').val('');
    });
});
activarTabla=function(){
    $("#t_plantilla").dataTable(); // inicializo el datatable
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
            "<td >"+data.nombre+"</td>"+
            "<td id='estado_"+data.id+"' data-estado='"+data.estado+"'>"+estadohtml+"</td>"+
            '<td><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#plantillaModal" data-id="'+data.id+'" data-titulo="Editar"><i class="fa fa-edit fa-lg"></i> </a></td>';

        html+="</tr>";
    });
    $("#tb_plantilla").html(html); 
    activarTabla();
};
</script>