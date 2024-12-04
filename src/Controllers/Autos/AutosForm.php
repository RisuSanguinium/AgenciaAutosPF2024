<?php

namespace Autos\Autos;
use Controllers\PublicController;
use Views\Renderer;
use Utilities\Site;
use Dao\Autos\Autos;

class AutosForm extends PublicController {
    
    private $viewData = [];
    private $mode = '';
    private $modeArr = [
        "INS" => "Crear nuevo Auto",
        "UPD" => "Editando %s (%s)",
        "DSP" => "Detalle de %s (%s)",
        "DEL" => "Eliminado %s (%s)"
    ];

    private $auto = [
        "id_auto" => 0,  
        "marca" => '',               
        "modelo" => '',          
        "anio" => 0,                      
        "registro" => '',    
        "estado" => '',  
        "precio" => 0,          
        "precio_min" => 0,        
        "autoImgUrl" => '', 
    ];

    private $errors = [];

    private function addError($error, $context=''){
        if(isset($this->errors[$context])){
            $this->errors[$context][] = $error;
        }else {
            $this->errors[$context] = [$error];
        }
    }

    public function run():void
    {
        $this->inicializarForm();
        if($this->isPostBack()){
            $this->cargarDatosFormulario();
            $this->procesarAccion();
        }
        $this->generarViewData();
        Renderer::render('autos/autos_form', $this->viewData);
    }

    private function inicializarForm(){
        if(isset($_GET["mode"]) && isset($this->modeDscArr[$_GET["mode"]])){
            $this->mode = $_GET["mode"];
        } else {
            Site::redirectToWithMsg("index.php?page=Autos-AutosList","Hubo un error en el guardado, Reintente");
            die();
        }
        if($this->mode !=='INS' && isset($_GET["id_auto"])){
            $this->auto["id_auto"] = $_GET["id_auto"];
            $this->cargarDatosAuto();
        }
    }

    private function cargarDatosAuto(){
        $tmpAuto = Autos::obenerAutoPorId($this->auto["id_auto"]);
        $this->auto = $tmpAuto;
    }

    private function generarViewData(){
        $this->viewData["mode"] = $this->mode;
        $this->viewData["modes_dsc"] = sprintf(
            $this->modeArr[$this->mode], 
            $this->auto["marca"], 
            $this->auto["id_auto"]
        );
        $this->viewData["auto"] = $this->auto;
        $this->viewData["readonly"] = 
            ($this->viewData["mode"] === 'DEL' 
                || $this->viewData["mode"] === 'DSP'
                ) ? 'readonly': '';
        foreach($this->errors as $context=>$errores){
            $this->viewData[$context.'-error'] = $errores;
        }
        $this->viewData["showConfirm"] = ($this->viewData["mode"] != 'DSP');

    }

    private function cargarDatosFormulario(){  
        $this->auto["marca"] = $_POST["marca"];                
        $this->auto["modelo"] = $_POST["modelo"];           
        $this->auto["anio"] = $_POST["anio"];                      
        $this->auto["registro"] = $_POST["registro"];   
        $this->auto["estado"] = $_POST["estado"];   
        $this->auto["precio"] = $_POST["precio"];          
        $this->auto["precio_min"] = $_POST["precio_min"];
        $this->auto["autoImgUrl"] = $_POST["autoImgUrl"];  
    }

    private function procesarAccion(){
        switch($this->mode){
            case 'INS':
                $result = Autos::agregarAutos($this->auto);
                if($result){
                    Site::redirectToWithMsg("index.php?page=Autos-AutosList","Auto Registrado satisfactoriamente");
                }
                break;
            case 'UPD':
                $result = Autos::actualizarAuto($this->auto);
                if($result){
                    Site::redirectToWithMsg("index.php?page=Autos-AutosList","Auto Actualizado satisfactoriamente");
                }
                break;
            case 'DEL':
                $result = Autos::eliminarAuto($this->auto);
                if($result){
                    Site::redirectToWithMsg("index.php?page=Autos-AutosList","Auto Eliminado satisfactoriamente");
                }
                break;
        }
    }
}