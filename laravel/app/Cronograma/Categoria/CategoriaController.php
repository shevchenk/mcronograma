<?php
namespace Cronograma\Categoria;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Categoria;

class CategoriaController extends \BaseController {

    /**
     * cargar categorias
     * POST /categoria/cargar
     */
    public function postCargar()
    {
        if ( Request::ajax() ) {
            $areas = Categoria::get(Input::all());
            return Response::json(array('rst'=>1,'datos'=>$areas));
        }
    }

}
