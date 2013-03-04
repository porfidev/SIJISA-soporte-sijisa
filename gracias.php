<?php
ob_start(); ?>

	<p><span class="boldtext">En hora buena..!!!</span> </p>
	<p>Tu solicitud fue procesada con &eacute;xito.</p>
	<?php if ($_GET['t']<>null) { echo "<p>Su numero de ticket es: <b>". Base64_decode($_GET['t']) . "</b></p>"; } ?>
	<br><br>

<?php 
$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");
?>