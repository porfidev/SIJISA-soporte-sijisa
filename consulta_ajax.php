<?php
require_once("class/maestra.php");

$datos = new consultarUsuarios;

$mycliente = $datos->getUsuariosClientes($_POST["empresa"]);

if (sizeof($mycliente) != 0){
	foreach($mycliente as $indice => $contenido){
		$id_cliente = $contenido["intIdUsuario"];
		$nombre_cliente = $contenido["nombre"];	
		?>
		<option value="<?php echo "$id_cliente"; ?>"><?php echo "$nombre_cliente"; ?></option>
		<?php
	}
}
?>