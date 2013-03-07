<?php
session_start();

//if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: index.php');exit;}

ob_start();
include('conexion.php');
include("class.phpmailer.php");
include("class.smtp.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>

<?php
//print_r($_POST);
//print_r($_FILES);
//verifica si se enviaron variable mediante $_POST
	if ($_POST != null){
		
		/*
		$archivo = date("Ymd") . "_" . $_FILES["userfile"]["name"];
		$ubicacion = $_FILES["userfile"]["tmp_name"];
		*/
		
		// Directorio al que se suben los archivos
		$uploads_dir = "upload";
		
		//Se comprueba si se subieron archivos adjuntos
		if (isset($_FILES["userfile1"]) && $_FILES["userfile1"]["error"] == 0 )
		{
			$archivo1 = date("Ymd") . "_" . date("is"). "_".$_FILES["userfile1"]["name"];
			$ubicacion_archivo1 = $_FILES["userfile1"]["tmp_name"];
			
			echo "<br>";
			
			var_dump($archivo1);
			var_dump($ubicacion_archivo1);
			
			echo "$uploads_dir/$archivo1";
			
			move_uploaded_file($ubicacion_archivo1, "$uploads_dir/$archivo1");
			/*echo "<script>alert('Hubo un problema al subir el archivo 1, no se genero el ticket.')</script>"; */
			//header('Location: levantar_ticket.php');
			//exit;
		}
		if (isset($_FILES["userfile2"]) && $_FILES["userfile2"]["error"] == 0 )
		{
			$archivo2 = date("Ymd") . "_" . date("is"). "_".$_FILES["userfile2"]["name"];
			$ubicacion_archivo2 = $_FILES["userfile2"]["tmp_name"];
			
			echo "<br>";
			
			var_dump($archivo2);
			var_dump($ubicacion_archivo2);
			
			echo "$uploads_dir/$archivo2";
			
			move_uploaded_file($ubicacion_archivo2, "$uploads_dir/$archivo2");
			/*echo "<script>alert('Hubo un problema al subir el archivo 1, no se genero el ticket.')</script>"; */
			//header('Location: levantar_ticket.php');
			//exit;
		}
		if (isset($_FILES["userfile3"]) && $_FILES["userfile3"]["error"] == 0 )
		{
			$archivo3 = date("Ymd") . "_" . date("is"). "_".$_FILES["userfile3"]["name"];
			$ubicacion_archivo3 = $_FILES["userfile3"]["tmp_name"];
			
			echo "<br>";
			
			var_dump($archivo3);
			var_dump($ubicacion_archivo3);
			
			echo "$uploads_dir/$archivo3";
			
			move_uploaded_file($ubicacion_archivo3, "$uploads_dir/$archivo3");
			/*echo "<script>alert('Hubo un problema al subir el archivo 1, no se genero el ticket.')</script>"; */
			//header('Location: levantar_ticket.php');
			//exit;
		}
		
		

$mivar = "call save_ticket('".
		$_POST['tipoticket'] . "','" .
		$_POST['fecha_alta'] . "','" .
		$_POST['fecha_problema'] . "','" .
		$_POST['procedencia'] . "','" .
		$_POST['prioridad'] . "','" .
		$_SESSION['intIdUsuario'] . "','" .
		$_POST['destinatario'] . "','" .
		$_POST['problema'] . "','" .
		$_POST['observaciones'] . "','" .
		$archivo1 . "','" .
		$archivo2 . "','" .
		$archivo3 . "',1);";
		
$datos = mysql_query($mivar) or die(mysql_error());

var_dump($mivar);
var_dump($datos);
print_r($datos);

$fila = mysql_fetch_array($datos);

var_dump($fila);
$mail = new PHPMailer();
		$descripcion="<br> <br> Se ha dado de alta  para el día ".$_POST['fecha_alta']." el siguiente usuario: <br> <br>".
		"<strong> Nombre: </strong>".$_SESSION['nombre']." <br>".
		"<strong> Empresa: </strong>".$_SESSION['empresa']." <br>".
		"<strong> Prioridad: </strong>".$_POST['prioridad']." <br> ".
		"<strong> Problema: </strong>".$_POST['problema']."  <br> ".
		"<strong> Observaciones: </strong>".$_POST['observaciones']."<br>".
		"<strong> Enviado a : </strong>".$_POST['destinatario'];
		$mail->IsSMTP();
		$mail->FromName = "Alta de Requerimiento";
		$mail->Subject =  "Soporte";
		$mail->AltBody ="$descripcion";
		$mail->MsgHTML("$descripcion");
		$mail->AddAddress($_SESSION["email"]);
		$mail->IsHTML(true);
		//$mail->Send();
		header('Location: gracias.php?t='.Base64_encode($fila[0]));
		//exit;

	}
?>
</body>
</html>