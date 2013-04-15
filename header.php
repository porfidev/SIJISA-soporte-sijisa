<div id="header">
	<div id="logo"></div>
	<?php
	if($_SESSION != null){
	?>
		<div id="menu">
				<ul>
					<li><a href="../../">Akumen</a></li>
					<li><a href="inicio.php">Inicio</a></li>
					<li><a href="lista_ticket.php">Ticket</a></li>
					<?php if($_SESSION["intIdTipoUsuario"] != 3){?>
					<li><a href="crear_usuario.php">Usuario</a></li>
					<li><a href="crear_empresa.php">Empresa</a></li>
					<li><a href="ver_reporte.php">Reporte</a></li>
					<?php } ?>
					<li><a href="cerrar.php">Cerrar sesión</a></li>
				</ul>
			</div>
	<?php
	} // FIN SESSION != null
	?>
</div> <!-- FIN DIV HEADER -->
<div class="clr"></div>