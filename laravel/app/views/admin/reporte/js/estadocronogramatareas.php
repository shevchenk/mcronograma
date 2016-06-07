<script type="text/javascript">
    
$(document).ready(function(){
    filtro=[];
    CartaInicio.cargar(filtro, HTMLreportep);
});
activarTabla=function(){
    var table = $("#t_reporte").dataTable( {
        "columnDefs": [ {
            "visible": false,
            "targets": -1
        } ]
    } ); // inicializo el datatable    
    $('#t_reporte tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('active') ) {
            $(this).removeClass('active');
        }
        else {
            table.$('tr.active').removeClass('active');
            $(this).addClass('active');
        }
    } );
};
HTMLreportep=function(datos){
    var html="", semaforo="", estdo='', estado_carta_inicio='';
    $('#t_reporte').dataTable().fnDestroy();
    $.each(datos,function(index,data){
        estado=data.estado;
        estado_carta_inicio=data.estado_carta_inicio;
        if (estado=='Concluido') {
            semaforo='#507C33';//'Resuelto';// 
        } else if (estado =='Inconcluso' && estado_carta_inicio=='Incumplimiento') {
            semaforo='#FF0000';//'Incumplimiento';// 
        } else if (estado =='Trunco' && estado_carta_inicio=='culminado') {
            semaforo='#FFC000';//'Existe retraso en el paso actual';// 
        } else if (estado =='Inconcluso' && estado_carta_inicio=='culminado') {
            semaforo='#92D050';//'No existe retraso en el paso actual';// 
        }

        html+="<tr>"+
            "<td>"+data.proceso+"</td>"+
            "<td>"+data.cantidad_pasos_proceso+"</td>"+
            "<td>"+data.dias_total+"</td>"+
            "<td>"+data.tramite+"</td>"+
            "<td>"+data.ultimo_paso+"</td>"+
            "<td>"+data.dias_ultimo_paso+"</td>"+
            "<td>"+data.fecha_inicio+"</td>"+
            "<td>"+data.fecha_fin+"</td>"+
            "<td style='background-color:"+semaforo+"'>"+"</td>"+
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