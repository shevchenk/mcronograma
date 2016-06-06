<script type="text/javascript">
    
$(document).ready(function(){
    filtro=[];
    CartaInicio.cargar(filtro, HTMLreportep);
});
activarTabla=function(){
    $("#t_reporte").dataTable( {
        "columnDefs": [ {
            "visible": false,
            "targets": -1
        } ]
    } ); // inicializo el datatable    
};
HTMLreportep=function(datos){
    var html="";
    $('#t_reporte').dataTable().fnDestroy();
    $.each(datos,function(index,data){

        html+="<tr>"+
            "<td>"+data.proceso+"</td>"+
            "<td>"+data.cantidad_pasos_proceso+"</td>"+
            "<td>"+data.dias_total+"</td>"+
            "<td>"+data.tramite+"</td>"+
            "<td>"+data.ultimo_paso+"</td>"+
            "<td>"+data.dias_ultimo_paso+"</td>"+
            "<td>"+data.fecha_inicio+"</td>"+
            "<td>"+data.fecha_fin+"</td>"+
            "<td>"+data.estado+"</td>"+
            "<td>"+data.estado_carta_inicio+"</td>"+
            "<td>"+data.tarea+"</td>"+
            "<td>"+data.descripcion_tarea+"</td>"+
            "<td>"+data.area+"</td>"+
            "<td>"+data.responsable+"</td>"+
            "<td>"+data.recursos+"</td>"+
            '<td><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#areaModal" data-id="'+index+'" data-titulo="Editar"><i class="fa fa-edit fa-lg"></i> </a></td>';

        html+="</tr>";
    });
    $("#tb_reporte").html(html);
    activarTabla();
};
</script>