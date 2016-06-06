<!DOCTYPE html>
@extends('layouts.master')  

@section('includes')
    @parent
    {{ HTML::style('lib/daterangepicker/css/daterangepicker-bs3.css') }}
    {{ HTML::style('lib/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}
    {{ HTML::script('lib/daterangepicker/js/daterangepicker.js') }}
    {{ HTML::script('lib/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}
    
    @include( 'admin.js.slct_global_ajax' )
    @include( 'admin.js.slct_global' )
    @include( 'admin.ruta.js.ruta_ajax' )
    @include( 'admin.reporte.js.estadocronogramatareas_ajax' )
    @include( 'admin.reporte.js.estadocronogramatareas' )
@stop
<!-- Right side column. Contains the navbar and content of the page -->
@section('contenido')

<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Estado de Cronograma de Tareas
            <small> </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li><a href="#">Reporte</a></li>
            <li class="active">Estado de Cronograma de Tareas</li>
        </ol>
    </section>

    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
         <div class="row">
                <div class="col-xs-12">
                    <!-- Inicia contenido -->
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Filtros</h3>

                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table cellspacing="0" id="t_reporte" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style='background-color:#FFF2CC' colspan="3">Proceso</th>
                                        <th style='background-color:#DEEBF7' colspan="6">Tramite</th>
                                        <th style='background-color:#C4DFB3' colspan="7">Tarea y responsable</th>
                                    </tr>
                                    <tr>
                                        <th style='background-color:#FFF2CC'>proceso</th>
                                        <th style='background-color:#FFF2CC'>cantidad_pasos_proceso</th>
                                        <th style='background-color:#FFF2CC'>dias_total</th>
                                        <th style='background-color:#DEEBF7'>tramite</th>
                                        <th style='background-color:#DEEBF7'>ultimo_paso</th>
                                        <th style='background-color:#DEEBF7'>dias_ultimo_paso</th>
                                        <th style='background-color:#DEEBF7'>fecha_inicio</th>
                                        <th style='background-color:#DEEBF7'>fecha_fin</th>
                                        <th style='background-color:#DEEBF7'>estado</th>
                                        <th style='background-color:#C4DFB3'>estado_carta_inicio</th>
                                        <th style='background-color:#C4DFB3'>tarea</th>
                                        <th style='background-color:#C4DFB3'>descripcion_tarea</th>
                                        <th style='background-color:#C4DFB3'>area</th>
                                        <th style='background-color:#C4DFB3'>responsable</th>
                                        <th style='background-color:#C4DFB3'>recursos</th>
                                        <th style='background-color:#C4DFB3'> [ ] </th>
                                    </tr>
                                </thead>
                                <tbody id="tb_reporte">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>proceso</th>
                                        <th>cantidad_pasos_proceso</th>
                                        <th>dias_total</th>
                                        <th>tramite</th>
                                        <th>ultimo_paso</th>
                                        <th>dias_ultimo_paso</th>
                                        <th>fecha_inicio</th>
                                        <th>fecha_fin</th>
                                        <th>estado</th>
                                        <th>estado_carta_inicio</th>
                                        <th>tarea</th>
                                        <th>descripcion_tarea</th>
                                        <th>area</th>
                                        <th>responsable</th>
                                        <th>recursos</th>
                                        <th> [ ] </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <!-- Finaliza contenido -->
                </div>
            </div>
    </section><!-- /.content -->

@stop
@section('formulario')
     @include( 'admin.ruta.form.ruta' )
@stop
