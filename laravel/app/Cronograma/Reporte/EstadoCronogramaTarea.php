<?php
namespace Cronograma\Reporte;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Plantilla;
use Helpers;
/**
* 
*/
class EstadoCronogramaTarea
{
    
    public static function getTotal($filtro=array())
    {
        $data=array();
        $sql = "
                SELECT * FROM (
                   SELECT
                        f.id AS proceso_id, f.nombre AS proceso,
                        fdetalle.cantidad_pasos_proceso,
                        rdetalle.cantidad_pasos_tramite, rdetalle.tramite,
                        rdetalle.estado, rdetalle.ultimo_paso,
                        -- c.*,
                        cd.fecha_inicio,
                        cd.fecha_fin,
                        
                        IF(
                            cd.fecha_fin<CURDATE(),
                            'Incumplimiento',
                            'culminado'
                        ) AS estado_carta_inicio,
                        
                        ta.nombre AS tarea,
                        cd.actividad AS descripcion_tarea,
                        a.`nombre` AS `area`,
                        CONCAT(p.paterno,' ' ,p.materno, ' ', p.nombre) AS responsable,
                        cd.`recursos`
                    FROM
                        flujos f 
                    INNER JOIN 
                    -- proceso
                        (SELECT COUNT(rfd.id) AS cantidad_pasos_proceso , rf.flujo_id

                         FROM rutas_flujo rf
                         INNER JOIN rutas_flujo_detalle rfd ON rf.id=rfd.ruta_flujo_id
                         WHERE rf.estado=1 AND rfd.estado=1
                         GROUP BY rf.flujo_id
                        ) fdetalle
                    ON f.id=fdetalle.flujo_id
                    INNER JOIN 
                    -- tramite 
                        (SELECT COUNT(rd.id) AS cantidad_pasos_tramite, rd.ruta_id,
                            tr.id_union AS tramite , r.flujo_id ,
                            IF(
                                (SELECT COUNT(rd.id)
                                FROM rutas_detalle rd
                                WHERE rd.ruta_id=r.id  AND rd.alerta=1
                                ),'Trunco',
                                IF(
                                    (SELECT COUNT(norden)
                                    FROM rutas_detalle rd 
                                    WHERE rd.ruta_id=r.id
                                    AND rd.fecha_inicio IS NOT NULL
                                    AND rd.dtiempo_final IS NULL
                                    AND rd.estado=1 
                                    ),'Inconcluso','Concluido'
                                )
                            ) AS estado,
                            IFNULL(
                                (SELECT MIN(norden)
                                 FROM rutas_detalle rd 
                                 WHERE rd.ruta_id=r.id
                                 AND rd.dtiempo_final IS NULL
                                 AND rd.estado=1 
                                 ORDER BY norden LIMIT 1
                                ),'' 
                            ) AS ultimo_paso
                        FROM rutas r 
                        INNER JOIN tablas_relacion tr ON r.tabla_relacion_id=tr.id
                        INNER JOIN rutas_detalle rd ON r.id=rd.ruta_id 
                        WHERE r.estado=1 AND rd.estado=1 AND tr.estado=1
                        GROUP BY r.id) rdetalle
                    ON f.id=rdetalle.flujo_id
                    INNER JOIN cartas c ON f.id=c.`flujo_id`
                    INNER JOIN carta_desglose cd ON c.id=cd.`carta_id`
                    INNER JOIN tipo_actividad ta ON cd.tipo_actividad_id=ta.id
                    INNER JOIN areas a ON cd.`area_id`=a.id
                    INNER JOIN personas p ON cd.`persona_id`=p.id
                    WHERE
                    f.estado=1 AND c.estado=1 AND cd.estado=1
                 ) tabla"
                 ;
        try {
            $data = DB::select($sql);
        } catch (Exception $e) {
            $data="error";
        }
        return $data;
    }
}