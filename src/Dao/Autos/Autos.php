<?php

namespace Dao\Autos;

use Dao\Table;

class Autos extends Table{

    private $id_auto; 
    private $marca;              
    private $modelo;         
    private $anio;                     
    private $registro;   
    private $estado; 
    private $precio;         
    private $precio_min;       
    private $autoImgUrl;   

    public static function obtenerAutos()
    {
        $sqlstr = 'SELECT * FROM autos;';
        $autos = self ::obtenerRegistros($sqlstr, []);
        return $autos;
    }

    public static function obenerAutoPorId($id){
        $sqlstr = 'SELECT * FROM autos where id_auto =:id_auto;';
        $auto = self:: obtenerUnRegistro($sqlstr, ["id_auto"=> $id]);
        return $auto;
    }
    public static function agregarAutos($auto){
        
        unset($autos['id_auto']);
        
      
            $sqlstr = ' insert into autos (
            marca, modelo,  anio, registro, 
            estado, precio, precio_min, autoImgUrl,)
            values
            (
            :marca, :modelo, :anio, :registro, :estado, :precio, :precio_min, :autoImgUrl, 
    
            );';
            return self::executeNonQuery($sqlstr,  $auto);
    }

    public static function actualizarAuto($auto){
       
        $sqlstr = "update autos set  marca = :marca, modelo = :modelo, anio = :anio, 
            registro = :registro, estado = :estado, precio = :precio, precio_min = :precio_min, 
            cilindraje = :cilindraje, notas = :notas, rodaje = :rodaje, estado = :estado,
            autoImgUrl = :autoImgUrl where id_auto = :id_auto;";
            return self::executeNonQuery($sqlstr, $auto);
        }

    public static function eliminarAuto($auto){
        $sqlstr = "delete from autos where id_auto = :id_auto;";
        return self::executeNonQuery($sqlstr, ["id_auto"=>$auto]);
    }
}