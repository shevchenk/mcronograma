<?php

class Carta extends Base
{
    public $table = "cartas";

    public static function Correlativo(){
        $areaId= Input::get('area_id');
        $año= date("Y");
        $sql="  SELECT LPAD(c.correlativo+1,4,'0') correlativo,a.nombre area,'$año' ano
                FROM cartas c 
                INNER JOIN areas a ON a.id=c.area_id
                WHERE year(c.created_at)='$año'
                AND c.area_id='$areaId'
                ORDER BY c.correlativo DESC
                LIMIT 1";

        $r= DB::select($sql);

        if( count($r)>0 ){
            return $r[0];
        }
        else{
            $sql="  SELECT '0001' correlativo,nombre area, '$año' ano
                    FROM areas 
                    WHERE id='$areaId'";
            $r= DB::select($sql);

            return $r[0];
        }
    }

    public static function CargarDetalle (){

        if( Input::has('vista') ){
            $sql="  SELECT c.id,c.nro_carta,c.objetivo,c.entregable,c.alcance, c.informe_objetivo, c.informe_alcance, c.informe_entregable,
                    GROUP_CONCAT( 
                        DISTINCT(
                            CONCAT(
                                (
                                SELECT tr.nombre
                                FROM tipo_recurso tr
                                WHERE tr.id=cr.tipo_recurso_id
                                ),'|',
                                cr.descripcion,'|',
                                cr.cantidad,'|',
                                cr.informe_sobro
                            ) 
                        )
                        SEPARATOR '*' 
                    ) recursos,
                    GROUP_CONCAT( 
                        DISTINCT(
                            CONCAT(
                                cm.metrico,'|',
                                cm.actual,'|',
                                cm.objetivo,'|',
                                cm.comentario,'|',
                                cm.informe_alcanzo
                            ) 
                        )
                        SEPARATOR '*' 
                    ) metricos,
                    GROUP_CONCAT( 
                        DISTINCT(
                            CONCAT(
                                (
                                SELECT nombre
                                FROM tipo_actividad ta
                                WHERE ta.id=cd.tipo_actividad_id
                                )
                                ,'|',
                                cd.actividad,'|',
                                (
                                SELECT CONCAT(p.paterno,' ',substr(p.materno,1,4),'. ',substr(p.nombre,1,7))
                                FROM personas p 
                                WHERE p.id=cd.persona_id
                                ),'-',
                                (
                                SELECT a.nombre
                                FROM areas a
                                WHERE a.id=cd.area_id
                                ),'|',
                                cd.recursos,'|',
                                cd.fecha_inicio,'|',
                                cd.fecha_fin,'|',
                                cd.hora_inicio,'|',
                                cd.hora_fin,'|',
                                cd.informe_responsable,'|',
                                cd.informe_recurso
                            ) 
                        )
                        SEPARATOR '*' 
                    ) desgloses
                    FROM cartas c
                    LEFT JOIN carta_recurso cr ON c.id=cr.carta_id AND cr.estado=1
                    LEFT JOIN carta_metrico cm ON c.id=cm.carta_id AND cm.estado=1
                    LEFT JOIN carta_desglose cd ON c.id=cd.carta_id AND cd.estado=1
                    WHERE c.id = '".Input::get('carta_id')."'
                    GROUP BY c.id";
        }
        elseif (Input::has('flujo_id')) {
            //gerente o subgerente de las areas a cargo
            $sql="  SELECT f.id, f.nombre, f.estado,
                    f.area_id, f.area_id as evento, 
                    IF(f.tipo_flujo=1,'Trámite','Proceso de oficio') as tipo_flujo,
                    f.tipo_flujo as tipo_flujo_id,
                    CONCAT(p.id,'-', a.id) as responsable_area
                    FROM flujos AS f 
                    INNER JOIN rutas_flujo AS rf ON rf.flujo_id = f.id AND rf.estado = 1
                    INNER JOIN rutas_flujo_detalle AS rfd ON rfd.ruta_flujo_id = rf.id   AND rfd.estado=1
                    INNER JOIN areas AS a ON a.id = rfd.area_id 
                    LEFT JOIN personas p ON p.area_id=a.id AND (p.rol_id='8' OR p.rol_id='9' )
                    WHERE  f.estado = 1 AND f.id ='".Input::get('flujo_id')."'";
        }
        else {
            $sql="  SELECT c.id,c.nro_carta,c.objetivo,c.entregable,c.alcance, c.informe_objetivo, c.informe_alcance, c.informe_entregable,
                    GROUP_CONCAT( 
                        DISTINCT(
                            CONCAT(
                                cr.tipo_recurso_id,'|',
                                cr.descripcion,'|',
                                cr.cantidad,'|',
                                cr.informe_sobro,'|',
                                cr.id
                            )
                        )
                        SEPARATOR '*' 
                    ) recursos,
                    GROUP_CONCAT( 
                        DISTINCT(
                            CONCAT(
                                cm.metrico,'|',
                                cm.actual,'|',
                                cm.objetivo,'|',
                                cm.comentario ,'|',
                                cm.informe_alcanzo,'|',
                                cm.id
                            )
                        )
                        SEPARATOR '*' 
                    ) metricos,
                    GROUP_CONCAT( 
                        DISTINCT(
                            CONCAT(
                                cd.tipo_actividad_id,'|',
                                cd.actividad,'|',
                                cd.persona_id,'-',cd.area_id,'|',
                                cd.recursos,'|',
                                cd.fecha_inicio,'|',
                                cd.fecha_fin,'|',
                                cd.hora_inicio,'|',
                                cd.hora_fin,'|',
                                cd.informe_responsable,'|',
                                cd.informe_recurso,'|',
                                cd.id
                            ) 
                        )
                        SEPARATOR '*' 
                    ) desgloses,
                    IFNULL(f.id,'') as flujo_id, f.nombre as flujo
                    FROM cartas c
                    LEFT JOIN carta_recurso cr ON c.id=cr.carta_id AND cr.estado=1
                    LEFT JOIN carta_metrico cm ON c.id=cm.carta_id AND cm.estado=1
                    LEFT JOIN carta_desglose cd ON c.id=cd.carta_id AND cd.estado=1
                    LEFT JOIN flujos f ON c.flujo_id=f.id
                    WHERE c.id = '".Input::get('carta_id')."'
                    GROUP BY c.id";
        }
        $set=DB::statement('SET group_concat_max_len := @@max_allowed_packet');
        $r=DB::select($sql);

        return $r;
    }

    public static function CrearActualizar (){
        DB::beginTransaction(); // Iniciando transacción
        $carta=array();
        if( Input::has('informe') ){
            $carta=Carta::find(Input::get('carta_id'));
            if( Input::has('inf_rec') ){
                $inforec[]=Input::get('inf_rec');
                $data = Input::all();

                // Carta data base
                Carta::where('id','=',Input::get('carta_id')) ->update(array(
                    'usuario_updated_at'=>Auth::user()->id,
                    'informe_objetivo'=>$data["objetivo_inf"],
                    'informe_entregable'=>$data["entregable_inf"],
                    'informe_alcance'=>$data["alcance_inf"]
                ));

                $recursos = $data["recursos_id"];
                $count = -1;
                foreach($recursos as $rec_id){
                    $count++;
                    CartaRecurso::where('id','=',$rec_id)->update(array(
                        'usuario_updated_at'=>Auth::user()->id,
                        'informe_sobro'=>$data["inf_sob"][$count]
                    ));
                }

                $metrico = $data["metricos_id"];
                $count = -1;
                foreach($metrico as $rec_id){
                    $count++;
                    CartaMetrico::where('id','=',$rec_id)->update(array(
                    'usuario_updated_at'=>Auth::user()->id,
                    'informe_alcanzo'=>$data["inf_alc"][$count]
                    ));
                }

                $desgloses = $data["desgloses_id"];
                $count = -1;
                foreach($desgloses as $rec_id){
                    $count++;
                    CartaDesglose::where('id','=',$rec_id)->update(array(
                    'usuario_updated_at'=>Auth::user()->id,
                    'informe_responsable'=>$data["inf_res"][$count],
                    'informe_recurso'=>$data["inf_rec"][$count]
                ));
                }

            }
        }
        else{
            if( Input::has('carta_id') ){
                $carta=Carta::find(Input::get('carta_id'));
                $carta['usuario_updated_at']=Auth::user()->id;

                DB::table('carta_recurso')
                    ->where('carta_id', '=', Input::get('carta_id'))
                    ->update(array(
                        "estado"=>0,
                        "updated_at"=>date("Y-m-d H:i:s"),
                        "usuario_updated_at"=>Auth::user()->id
                    )
                );

                DB::table('carta_metrico')
                    ->where('carta_id', '=', Input::get('carta_id'))
                    ->update(array(
                        "estado"=>0,
                        "updated_at"=>date("Y-m-d H:i:s"),
                        "usuario_updated_at"=>Auth::user()->id
                    )
                );

                DB::table('carta_desglose')
                    ->where('carta_id', '=', Input::get('carta_id'))
                    ->update(array(
                        "estado"=>0,
                        "updated_at"=>date("Y-m-d H:i:s"),
                        "usuario_updated_at"=>Auth::user()->id
                    )
                );
            }
            else{
                $carta=new Carta;
                $carta['usuario_created_at']=Auth::user()->id;
                $carta['area_id']=Input::get('area_id');
                $carta['flujo_id']=Input::get('flujo_id');
                $carta['nro_carta']=Input::get('nro_carta');
                $correlativo=explode("-",Input::get('nro_carta'));
                $carta['correlativo']= $correlativo[1]*1;
            }

            $carta['objetivo']=Input::get('objetivo');
            $carta['entregable']=Input::get('entregable');
            $carta['alcance']=Input::get('alcance');
            $carta->save();

            $recursos=array();
            if( Input::has('rec_tre') ){
                $recursos[]=Input::get('rec_tre');
                $recursos[]=Input::get('rec_des');
                $recursos[]=Input::get('rec_can');

                for( $i=0; $i<count($recursos[0]); $i++ ){
                    $cartaRecurso=new CartaRecurso;
                    $cartaRecurso['usuario_created_at']=Auth::user()->id;

                    $cartaRecurso['carta_id']=$carta->id;
                    $cartaRecurso['tipo_recurso_id']=$recursos[0][$i];
                    $cartaRecurso['descripcion']=$recursos[1][$i];
                    $cartaRecurso['cantidad']=$recursos[2][$i];

                    $cartaRecurso->save();
                }
            }

            $metricos=array();
            if( Input::has('met_met') ){
                $metricos[]=Input::get('met_met');
                $metricos[]=Input::get('met_act');
                $metricos[]=Input::get('met_obj');
                $metricos[]=Input::get('met_com');

                for( $i=0; $i<count($metricos[0]); $i++ ){
                    $cartaMetrico=new CartaMetrico;
                    $cartaMetrico['usuario_created_at']=Auth::user()->id;

                    $cartaMetrico['carta_id']=$carta->id;
                    $cartaMetrico['metrico']=$metricos[0][$i];
                    $cartaMetrico['actual']=$metricos[1][$i];
                    $cartaMetrico['objetivo']=$metricos[2][$i];
                    $cartaMetrico['comentario']=$metricos[3][$i];

                    $cartaMetrico->save();
                }
            }

            $desgloses=array();
            if( Input::has('des_tac') ){
                $desgloses[]=Input::get('des_tac');
                $desgloses[]=Input::get('des_act');
                $desgloses[]=Input::get('des_res');
                $desgloses[]=Input::get('des_rec');
                $desgloses[]=Input::get('des_fin');
                $desgloses[]=Input::get('des_ffi');
                $desgloses[]=Input::get('des_hin');
                $desgloses[]=Input::get('des_hfi');

                for( $i=0; $i<count($desgloses[0]); $i++ ){
                    $cartaDesglose=new CartaDesglose;
                    $cartaDesglose['usuario_created_at']=Auth::user()->id;

                    $cartaDesglose['carta_id']=$carta->id;
                    $cartaDesglose['tipo_actividad_id']=$desgloses[0][$i];
                    $cartaDesglose['actividad']=$desgloses[1][$i];
                    $pa=explode("-",$desgloses[2][$i]);
                    $cartaDesglose['persona_id']=$pa[0];
                    $cartaDesglose['area_id']=$pa[1];
                    $cartaDesglose['recursos']=$desgloses[3][$i];
                    $cartaDesglose['fecha_inicio']=$desgloses[4][$i];
                    $cartaDesglose['fecha_fin']=$desgloses[5][$i];
                    $cartaDesglose['hora_inicio']=$desgloses[6][$i];
                    $cartaDesglose['hora_fin']=$desgloses[7][$i];

                    $cartaDesglose->save();
                }
            }
        }
        //DB::rollback();
        DB::commit();

        return  array(
                    'rst'=>1,
                    'msj'=>'Registro realizado correctamente',
                );
    }

    public static function Cargar (){
        $r=DB::table('cartas')
                ->select('id','nro_carta','objetivo','entregable')
                ->where( 
                    function($query){
                        if ( Input::get('union') ) {
                            $query->where('union','=',0)
                                ->where( 'area_id','=',Input::get('area_id') );
                        }
                        if( Input::has('area_id') ){
                            $query->where( 'area_id','=',Input::get('area_id') );
                        }
                    }
                )
                ->orderBy('nro_carta')
                ->get();
                
        return $r;
    }
}
