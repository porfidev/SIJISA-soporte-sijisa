<?php
$file=trim($_POST['file']);
if($borrar = unlink('../documentos/'.$file))
	echo "Eliminado";
else
	echo "Error al eliminar el archivo";	
?>