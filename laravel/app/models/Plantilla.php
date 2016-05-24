<?php 
/**
* 
*/
class Plantilla extends Base
{
    
    public $table = "plantillas";
    public static $where =['id', 'nombre', 'estado'];
    public static $selec =['id', 'nombre', 'estado'];
    
}