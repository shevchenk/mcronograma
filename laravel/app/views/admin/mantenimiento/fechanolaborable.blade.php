<!DOCTYPE html>
@extends('layouts.master')

@section('includes')
    @parent
    {{ HTML::script('http://malsup.github.com/jquery.form.js') }}
    {{ HTML::style('/lib/fullcalendar-2.7/fullcalendar.css') }}
    {{ HTML::style('/lib/fullcalendar-2.7/fullcalendar.print.css', array('media' => 'print')) }}
    {{ HTML::script('/lib/fullcalendar-2.7/lib/moment.min.js') }}
    {{ HTML::script('/lib/fullcalendar-2.7/fullcalendar.min.js') }}
    {{ HTML::script('/lib/fullcalendar-2.7/lang/es.js') }}

    {{ HTML::style('/lib/daterangepicker/css/daterangepicker-bs3.css') }}
    {{ HTML::script('/lib/daterangepicker/js/daterangepicker_single.js') }}
    {{ HTML::style('lib/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}
    {{ HTML::script('lib/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}

    @include( 'admin.js.slct_global_ajax' )
    @include( 'admin.js.slct_global' )
    @include( 'admin.mantenimiento.js.fechanolaboral_ajax' )
    @include( 'admin.mantenimiento.js.fechanolaboral' )
@stop

@section('contenido')

    <section class="content-header">
        <h1>
            Mantenimiento de Fechas <b>No</b> laborales
            <small> </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li><a href="#">Mantenimientos</a></li>
            <li class="active">Mantenimiento de Fechas <b>No</b> laborales</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <h4>Filtro</h4>
                        <form id="form_fechafiltro" name="form_fechafiltro" action="" method="post" >
                          <div class="form-group">
                            <label class="control-label">Fecha:</label>
                            <input type="text" class="form-control fecha" placeholder="Fecha" name="txt_fechafiltro" id="txt_fechafiltro">
                          </div>
                          <div class="form-group">
                            <label class="control-label">Area:</label>
                            <select class="form-control" name="slct_areafiltro" id="slct_areafiltro"></select>
                          </div>
                          <div class="form-group">
                            <label class="control-label">Estado:</label>
                            <select class="form-control" name="slct_estadofiltro" id="slct_estadofiltro">
                                <option value='0'>Inactivo</option>
                                <option value='1' selected>Activo</option>
                            </select>
                          </div>
                          <button type="button" class="btn btn-primary">
                              <i class="fa fa-search fa-sm"></i>&nbsp;Buscar
                           </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-body no-padding">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('formulario')
     @include( 'admin.mantenimiento.form.fechanolaborable' )
@stop