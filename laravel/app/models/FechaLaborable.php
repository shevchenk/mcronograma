<?php

class FechaLaborable extends Base
{
    public $table = "fechas_laborables";
    public static $where = ['id', 'fecha', 'general', 'estado'];
    public static $selec = ['id', 'fecha', 'general', 'estado'];

    public static function getFechas()
    {
        return DB::table('fechas_laborables as f')
                ->leftjoin('fechas_areas_laborables as fa', function($join){
                    $join->on('fa.fecha_laborable_id', '=', 'f.id');
                    $join->on('fa.estado', '=', DB::raw("1"));
                  })
                ->leftjoin('areas as a', function($join){
                    $join->on('a.id', '=', 'fa.area_id');
                    $join->on('a.estado', '=', DB::raw("1"));
                  })
                ->select(
                    'f.id',
                    'f.fecha',
                    'f.general',
                    'f.estado',
                    'a.id as area_id',
                    'a.nombre as nombre_area',
                    'a.nemonico'
                )
                ->where('f.estado', 1)
                ->orderBy('f.estado')
                ->orderBy('f.fecha')
                ->get();
    }

    public static function getFechaByArea($fecha, $area_id, $general){
        return DB::table('fechas_laborables as f')
                ->leftjoin('fechas_areas_laborables as fa', function($join){
                    $join->on('fa.fecha_laborable_id', '=', 'f.id');
                    $join->on('fa.estado', '=', DB::raw("1"));
                  })
                ->where(function ($query) use ($area_id, $general) {
                    if (isset($area_id) && !empty($area_id) && !$general) {
                        $query->where('fa.area_id', '=', $area_id);
                    } else {
                        $query->where('f.general', '=', DB::raw("1"));
                    }
                })
                ->where('f.estado', 1)
                ->where('f.fecha', $fecha)
                ->get();
    }

    public static function getAreas(){
        return DB::table('areas')
                ->select('id','nombre','nemonico','estado','imagen')
                ->where('estado', 1)
                ->orderBy('nombre')
                ->get();
    }

}
