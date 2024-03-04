<?php
//iniciamos sesion
session_start();
session_write_close();

//Incluimos clases
require_once("_folder.php");
require_once(DIR_BASE."/class/class.tickets.php");
require_once(DIR_BASE."/class/class.empresa.php");
require_once(DIR_BASE."/class/class.archivo.php");
require_once(DIR_BASE."/class/class.correo.php");
require_once(DIR_BASE."/class/class.usuario.php");

if($_POST == null or !isset($_POST)){
	echo "No se pueden ingresar";
	header('Location: '.DIR_BASE.'/inicio.php');
	exit;
}
else {
    ##################################
    ####  REGISTRAR SEGUIMIENTO  ####
    ##################################
	if(isset($_POST['tipo']) and $_POST['tipo'] == "seguimiento"){
		$status = $_POST['estado'];
		
		$oTicket = new Ticket;
		$oTicket->isUpdate($status);

		if(isset($_FILES) and !empty($_FILES)){
			$oArchivo = new Archivo;
			$archivos = $oArchivo->moverArchivos($_FILES);
			foreach($archivos as $indice){
				$archivo[] = $indice;
			}
		}
		else {
			$archivo[0] = null;
			$archivo[1] = null;
			$archivo[2] = null;
		}
		
		$oTicket->setValores(array("idtrans"=>null,
									"idticket"=>$_POST['id_ticket'],
									"estatus"=>$_POST['estado'],
									"prioridad"=>$_POST['prioridad'],
									"usuario"=>$_SESSION['id_usuario'],
									"observaciones"=>$_POST['observaciones'],
									"fecha"=>$oTicket->timeZone(),
									"archivo"=>"".$archivo[0]."&>".$archivo[1]."&>".$archivo[2]."",
									"prioridad" =>$_POST['prioridad'],
									"fecha_cierre"=>$oTicket->timeZone(),
									"fecha_asignacion"=>$oTicket->timeZone(),
									"fecha_termino"=>$oTicket->timeZone(),
									"tipo_atencion"=>$_POST['atencion']
									));
		
		$seguimiento_ticket = $oTicket->consultaTicket();
		
		if(empty($seguimiento_ticket)){
            
            $oTicketB = new Ticket;
            $oTicketB->isQuery("ticket");
            $oTicketB->setValores(array("idticket"=>$_POST['id_ticket']));
            $resultado = $oTicketB->consultaTicket();
            $ticket = $resultado[0]["intIdUnico"];
            
            $observaciones = filter_var($_POST['observaciones'], FILTER_SANITIZE_STRING);
            
            $oCorreo = new Correo;
            $envio = $oCorreo->correoSeguimientoTicket($correo_usuario, "",$ticket,$observaciones);
            
            
            $status ="success";
            $mensaje = "seguimiento registrado";
            $registro = true;
            
            if($envio != true){
                $status = "danger";
                $mensaje = "No se pudo enviar el mail: ".$envio;
            }
            
			//$respuesta = array("registro"=>true,"estado" => $status, "mensaje"=> $mensaje);
			//echo json_encode($respuesta);
        }
	}
	else{
        ##################################
        ####  REGISTRAR NUEVO ####
        ##################################
        
        $oTicket = new Ticket;
		$oTicket->isRegister();
		
		$oEmpresa = new Empresa;
		$oEmpresa->isQuery(true);
		$oEmpresa->setValores(array("id_empresa"=>$_POST['empresa']));
		$empresa = $oEmpresa->consultaEmpresa();
		
		foreach($empresa as $indice => $campo){
			$siglas = ($campo['siglasEmpresa'] != "") ? $campo['siglasEmpresa'] : strtoupper(substr($campo['Descripción'],0,3));
			$id_unico = substr($_POST['tipo_solicitud'],0,1)."-".$siglas."-".date("Ymd").date("Hs").rand(1, 9);
		}
		
		if(isset($_FILES) and !empty($_FILES)){
			$oArchivo = new Archivo;
			$archivos = $oArchivo->moverArchivos($_FILES);
			foreach($archivos as $indice){
				$archivo[] = $indice;
			}
		}
		else {
			$archivo[0] = null;
			$archivo[1] = null;
			$archivo[2] = null;
		}
		
		$oTicket->setValores(array("id_unico"=>$id_unico,
									"tipo"=>$_POST['tipo_solicitud'],
									"fecha_alta"=>$_POST['fecha_control'],
									"fecha_problema"=>$_POST['fecha_problema'],
									"id_empresa"=>$_POST['empresa'],
									"prioridad"=>$_POST['prioridad'],
									"id_usuario"=>$_POST['cliente'],
									"destinatario"=>$_POST['tipo_ticket'],
									"problema"=>$_POST['problema'],
									"observaciones"=>$_POST['observaciones'],
									"archivo1"=>$archivo[0],
									"archivo2"=>$archivo[1],
									"archivo3"=>$archivo[2],
									"estatus"=>1,
									"fecha_asignacion"=>null,
									"fecha_termino"=>null,
									"fecha_cierre"=>null));
		
		$nuevo_ticket = $oTicket->consultaTicket();
		
		if(empty($nuevo_ticket)){
            $status = "success";
            $mensaje = "Ticket registrado";
            
            if($_SESSION["tipo_usuario"] != 3){
                $mensaje = "<b>Ticket registrado:</b> <br>
                            <a href='seguimientoTicket.php?seccion=ticket&ticketId=".$id_unico."'><h3>".$id_unico. "</h3> para dar seguimiento haga clic aquí </a>";
            }
            
            #envio de correo
            
            $oUsuario = new Usuario;
            $oUsuario->obtenerEmail();
            $oUsuario->setValores(array("id_usuario" => $_POST["cliente"]));
            $resultado= $oUsuario->consultaUsuario();
            $correo_usuario = $resultado[0]["email"];
            
            $oEmpresa->obtenerMail();
            $resultado = $oEmpresa->consultaEmpresa();
            
            if(!empty($resultado)){
                $correo_empresa = $resultado[0]["emailEmpresa"];
            }
            $status ="success";
            $mensaje = "seguimiento registrado";
            $registro = true;
            
            $oCorreo = new Correo;
            $envio = $oCorreo->correoNuevoTicket($correo_usuario, $correo_empresa, $id_unico, $_POST['problema'], $_POST['observaciones']);
            
            if($envio != true){
                $status = "danger";
                $mensaje = "No se pudo enviar el mail: ".$envio;
            }
		}
	}
}

#Respuesta al AJAX de Jquery
$respuesta = array("registro" => $registro, "estado" => $status, "mensaje" => $mensaje);
echo json_encode($respuesta);

?>