<?php

include("folder.php");

class Archivo
{
	private $upload_dir;
	
	public function __construct(){
		 $this->upload_dir = DIR_BASE."/upload";
	}
	
	public function getUploadDir(){
		return $this->upload_dir;
	}
	
	public function moverArchivos($archivos){ //Optimizada para multiples archivos
			
			//var_dump($archivos);
			$this->upload_dir = DIR_BASE."/upload"; //archivos para subir
			$ubicaciones = array();
			
			$ext_type = array('gif','jpg','jpe','jpeg','png','txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'msg');
			
			//comprueba si el directorio existe y si es posible escribir
			if (file_exists($this->upload_dir) && is_writable($this->upload_dir)) {
				
				foreach ($archivos["archivo_adjunto"]["error"] as $key => $error) {
					if ($error == 0) {
						//$archivo = date("Ymd") . "_" . date("is"). "_".$archivos["userfile"]["name"][$key];
						
						$trozo = explode(".",$archivos["archivo_adjunto"]["name"][$key]);
						$extension = strtolower(end($trozo));  
						
						foreach($ext_type as $tipo){
							//echo $tipo;
							if($tipo == $extension){
								$valido = true;
							}
						}
						
						$nombre_archivo = preg_replace(array("/\s+/", "/[^-\.\w]+/"),array("_", ""),trim($trozo[0])); 
						 
						$archivo = date("Ymd") . "_" . date("is")."_".$nombre_archivo.".".$extension;
						
						$ubicacion = $archivos["archivo_adjunto"]["tmp_name"][$key];
						if(isset($valido) and $valido == true){
							if(!move_uploaded_file($ubicacion,$this->upload_dir."/$archivo")){
								echo "No se puede mover el archivo";
							}
							else{
								$ubicaciones[] = $archivo;
							}
						}
						else{
							echo "extension de archivo no valida";
						}
					}
					else{
						if($error != 0 and $error != 4){//Si no subieron archivos No enviar ninguna advertencia
							$mensaje = $this->file_upload_error_message($archivos["userfile"]["error"][$key]);
							echo $mensaje." Intente nuevamente.";
							exit;
						}
					}
				} //Fin del foreach
				
				return $ubicaciones;
			}
			else {
				echo "No existe la carpeta para subir archivos o no tiene los permisos suficientes.";
			}
		} // Termina la funcion moverArchivos()
		
		private function file_upload_error_message($error_code) {
			switch ($error_code) {
				case UPLOAD_ERR_INI_SIZE:
					return 'El archivo excede el limite permitido por la directa de PHP';
				case UPLOAD_ERR_FORM_SIZE:
					return 'El archivo excede el limite permitido por la directiva de HTML';
				case UPLOAD_ERR_PARTIAL:
					return 'El archivo solo se subio parcialmente, intentelo de nuevo';
				case UPLOAD_ERR_NO_FILE:
					return 'No hay archivo que subir';
				case UPLOAD_ERR_NO_TMP_DIR:
					return 'El folder temporal no existe';
				case UPLOAD_ERR_CANT_WRITE:
					return 'No tiene permisos para grabar archivos en el disco';
				case UPLOAD_ERR_EXTENSION:
					return 'El archivo tiene una extensión NO permitida';
				default:
					return 'Error desconocido al subir el archivo';
			}
		} // Termina funcion file_upload_error_message()
}
?>