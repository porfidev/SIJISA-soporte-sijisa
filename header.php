<div id="header" style="margin-top: 150px;">
	<?php
	session_start();
	//var_dump($_SESSION);
	if($_SESSION != null){
	?>
	<!--<div id="menu">
		<ul>
			<!--<li><a href="../../">Akumen</a></li>-->
			<!--<li><a href="inicio.php">Inicio</a></li>
			<li><a href="lista_ticket.php">Ticket</a></li>
			<?php if($_SESSION["intIdTipoUsuario"] != 3){?>
			<li><a href="crear_usuario.php">Usuario</a></li>
			<li><a href="crear_empresa.php">Empresa</a></li>
			<li><a href="ver_reporte.php">Reporte</a></li>
			<?php } ?>
			<li><a href="cerrar.php">Cerrar sesión</a></li>
		</ul>
	</div>-->
	<?php
	if(isset($_GET['seccion'])){
		$sec = htmlentities($_GET['seccion']);
	}
	else {
		$sec = "inicio";
	}
	?>
	<div class="navbar">
		<div class="navbar-inner"> <a class="brand" href="#">Soporte Akumen</a>
			<ul class="nav">
				<li class="<?php if($sec == "inicio") echo "active"?>"><a href="inicio.php">Inicio</a></li>
				<li class="dropdown <?php if($sec == "usuario") echo "active"?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuarios<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="crearUsuario.php?seccion=usuario">Nuevo</a></li>
						<li class="disabled"><a href="#">Editar</a></li>
						<li class="disabled"><a href="#">Eliminar</a></li>
					</ul>
				</li>
				<li class="dropdown <?php if($sec == "empresa") echo "active"?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Empresa<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="crearEmpresa.php?seccion=empresa">Nueva</a></li>
						<li class="disabled"><a href="#">Editar</a></li>
						<li class="disabled"><a href="#">Eliminar</a></li>
					</ul>
				</li>
				<li class="dropdown <?php if($sec == "ticket") echo "active"?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tickets<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="crearTicket.php?seccion=ticket">Nuevo</a></li>
						<li><a href="seguimientoTicket.php?seccion=ticket">Seguimiento</a></li>
						<li class="divider"></li>
						<li><a href="reporteTicket.php?seccion=ticket">Generar reportes</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav pull-right">
				<li><a href="cerrarSesion.php">Cerrar sesión</a></li>
			</ul>
		</div>
	</div>
	<?php
	} // FIN SESSION != null
	?>
</div>
<!-- FIN DIV HEADER -->
<div class="clr"></div>
