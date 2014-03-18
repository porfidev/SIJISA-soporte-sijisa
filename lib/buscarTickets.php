<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */
 
//Incluimos clases
require_once("_folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");
require_once("../class/class.tickets.php");
require_once("../class/class.empresa.php");


$oTicket = new Ticket;
$oEmpresa = new Empresa;

if(isset($_POST["inIdTicket"])){
    if($_POST["inIdTicket"] != ""){
        $idticket = filter_var(trim($_POST["inIdTicket"]), FILTER_SANITIZE_STRING);
        
        $oTicket->isQuery("id_unico");
        $oTicket->setValores(array("id_unico" => $idticket));
        $tickets = $oTicket->consultaTicket();
        
        if(!empty($tickets)){
            $oEmpresa->isQuery("id");
            $oEmpresa->setValores(array("id_empresa"=>$tickets[0]["intIdEmpresa"]));
	        $empresas = $oEmpresa->consultaEmpresa();
        }
        else {
            $status = "warning";
            $mensaje = "No se encontro el ticket por el ID especificado";
        }
    }
    else {
        $status = "warning";
        $mensaje = "No se introdujo un número de ticket";
    }
}


if(isset($_POST["inEmpresaTicket"])){
    $idempresa = filter_var($_POST["inEmpresaTicket"], FILTER_SANITIZE_NUMBER_INT);
    
    $_POST["inPrioridad"] = (empty($_POST["inPrioridad"])) ? array() : $_POST["inPrioridad"];
    
    settype($idempresa, "integer");
    
    $tickets = $oTicket->isSearch($idempresa, $_POST["inPrioridad"], $_POST["inEstado"], $_POST["inFechaInicio"], $_POST["inFechaFin"]);
    $tickets = $oTicket->consultaTicket();
    $empresas = $oEmpresa->consultaEmpresa();
    /*
    if($idempresa == 0) {
        $tickets = $oTicket->consultaTicket();
        $empresas = $oEmpresa->consultaEmpresa();
    }
    elseif(is_int($idempresa) and $idempresa > 0) {
        $oTicket->isQuery("id");
        $oTicket->setValores(array("id_empresa"=>$idempresa));
        $tickets = $oTicket->consultaTicket();
        
        $oEmpresa->isQuery("id");
        $oEmpresa->setValores(array("id_empresa"=>$idempresa));
        $empresas = $oEmpresa->consultaEmpresa();
    }
    else{
        $status = "danger";
        $mensaje = "El valor especificado, no es valido para esta busqueda";
    }*/
}
/*
$oDatosEmpresa = new Empresa;
$empresas = $_POST['empresa'] == "0" ? $oDatosEmpresa->consultaEmpresa() : $oDatosEmpresa->consultaEmpresa(array("empresa"=>$_POST['empresa']));
*/
if(!empty($tickets)){
    $valores = array();
    $i = 0;
    
    foreach($tickets as $campo){
        switch($campo['intIdEstatus']){
            case 1:
                $estatus = "Asignado";
                break;
            case 2:
                $estatus = "En curso";
                break;
            case 3: 
                $estatus = "Pendiente";
                break;
            case 4:
                $estatus = "Resuelto";
                break;
            case 5:
                $estatus = "Cancelado";
                break;
            case 6:
                $estatus = "Cerrado";
                break;
            default:
                $estatus = "No disponible";
                break;
        }
        
        foreach($empresas as $indice => $fila){
            if($campo['intIdEmpresa'] == $fila['intIdEmpresa']){
                $empresa = $fila['Descripcion'];
            }
        }
        
        $valores[$i][] = $empresa;	
        $valores[$i][] = "<a href='#myModal' role='button' class='detalleTicket' data-toggle='modal' data-backdrop='static' data-id='".$campo['intIdTicket']."' ><i class='icon-check'></i> ".$campo['intIdUnico']."</a>";
        $valores[$i][] = $campo['prioridad'];
        $valores[$i][] = $estatus;
        $valores[$i][] = $campo['fecha_alta'];
        //$valores[$i][] = "Ver historial";
        $i++;
    }
}
    
if(!empty($tickets)){
	$respuesta = array("tickets"=>true, "datos"=>$valores);
}
else {
	$respuesta = array("tickets"=>false, "status" => $status, "mensaje" => $mensaje);	
}

echo json_encode($respuesta);

/*<a href='#myModal' role='button' class='btn btn-info abrirRegistro' data-toggle='modal' data-backdrop='static' data-id='".$campo['idforos']."' ><i class='icon-check icon-white'></i> Registrate Aquí</a>*/
?>