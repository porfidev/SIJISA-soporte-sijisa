<?php
/*$uploaddir = 'comeri_docs/normativos/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "success".$_POST['texto'];
} else {
  echo "error";
}*/

imagen($_FILES);
function imagen($_FILES){
	if ($_FILES['userfile']['error'] > 0 && $_FILES['userfile']['error'] <= 3)
	{
		$tipoProblema;
		switch ($_FILES['userfile']['error']) //$userfile_error
		{
			case 1: $tipoProblema = 'File exceeded upload_max_size.'; break;
			case 2: $tipoProblema = 'El archivo excede el tamaño maximo para subir imagenes.';break;
			case 3: $tipoProblema = 'File on partially uploaded.';break;
			//case 4: echo '<h1>No file uploaded</h1></div></div>';break;
		}
		fncMensage($tipoProblema);		
		exit;
	}
	//print_r($_POST); echo " type:".$_FILES['userfile']['type']; exit;
/*	if (!($_FILES['userfile']['type'] == 'image/jpeg' || $_FILES['userfile']['type'] == 'image/jpg' || $_FILES['userfile']['type'] == 'image/pjpeg' || $_FILES['userfile']['error'] == 4))
    {
		fncMensage("Problema: El archivo no es una imagen jpg.", $num_dictamen, $no_servicio);
		exit;
	}*/
	if($_FILES['userfile']['error'] == 4){ return $upfile="../images_condiciones_dictamen/no_imagen_sys.jpg"; }
	$_FILES['userfile']['name'] = str_replace('%', '_', $_FILES['userfile']['name']);
	//$_FILES['userfile']['name'] = str_replace('[', '_', $_FILES['userfile']['name']);
	//$_FILES['userfile']['name'] = str_replace(']', '_', $_FILES['userfile']['name']);
	$_FILES['userfile']['name'] = strtolower(str_replace(' ', '_', $_FILES['userfile']['name']));
	$nombreArchivo = $_FILES['userfile']['name'];
	// coloque el archivo donde desee
	
	$uploaddir = 'documentos/';
	$upfile = $uploaddir . basename($nombreArchivo);	
	//$upfile = 'comeri_docs/normativos/'.$nombreArchivo;
	$duplicado = buscar(basename($nombreArchivo));
	if($duplicado == 'duplicado'){
		echo 'duplicado';
		exit;
	}
	if(is_uploaded_file($_FILES['userfile']['tmp_name']))
	{
		if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $upfile))
			{
				fncMensage("Problema: No se pudo copiar el archivo al directorio destino.");
				exit;
			}
	}
	else
	{
		fncMensage("Problema: Posiblemente el archivo fue intersectado.");
		exit;
	}	
	echo "OK-".$duplicado;
}
function buscar($file_usuario) {
	$directorio = 'documentos/';
	$dir = opendir($directorio);
	while($file = readdir($dir)){
	  if(is_file($directorio.'/'.$file))
	  {
		$file2 = basename($file);
		if($file2 == $file_usuario)
			return 'duplicado';
	  }
	}
	closedir($dir);
}

function fncMensage($mensage){
	echo "error";			
}	  
?>