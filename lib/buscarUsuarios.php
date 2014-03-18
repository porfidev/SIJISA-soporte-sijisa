<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */
 
//Incluimos clases
include("folder.php");
require_once(DIR_BASE."/class/class.usuario.php");

$oUsuario = new Usuario;
$oUsuario->empresa = $_POST['empresa'];
$oUsuario->isQuery();
$usuarios = $oUsuario->consultaUsuario();

/*			
<th>Nombre</th>
<th>Usuario</th>
<th>Tipo</th>
<th>Correo electrónico</th>
<th>&nbsp;</th>
*/
if($_POST['ticket'] and is_array($usuarios)){
	foreach($usuarios as $indice => $campo){
		$individuos .= "<option value=\"".$campo['intIdUsuario']."\">".$campo['nombre']."</option>";
	}
	
	if(sizeof($individuos) != 0){
		$respuesta = array("clientes"=>$individuos);
		echo json_encode($respuesta);
	}
	else {
		$respuesta = array("clientes"=>false);
		echo json_encode($respuesta);
	}
}
else { //CUANDO NO ES TICKET (pendiente revision)
		if(is_array($usuarios)){
			foreach($usuarios as $indice => $campo){
				switch($campo['intIdTipoUsuario']){
					case 1:
						$tipo = "Administrador";
						break;
					case 2:
						$tipo = "Operador";
						break;
					case 3:
						$tipo = "Cliente";
						break;
				}
				$html .= "<tr>
								<td>".$campo['nombre']."</td>
								<td>".$campo['username']."</td>
								<td>".$tipo."</td>
								<td>".$campo['email']."</td>
								<td>
									<input type=\"hidden\" value=\"".$campo['intIdUsuario']."\" name=\"inId\">
									<a class=\"btn btn-small editar\">Editar</a>
									<a class=\"btn btn-small eliminar\">Eliminar</a>
								</td>
						</tr>
				";
			}
		}
		
		if(!empty($usuarios)){
			$respuesta = array("usuarios"=>true, "datos"=>$html);
			echo json_encode($respuesta);
		}
		else {
			$respuesta = array("usuarios"=>false);
			echo json_encode($respuesta);
		}
}
?>