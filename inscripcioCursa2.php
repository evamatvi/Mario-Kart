#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: inscriure cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
	$_SESSION['codiCursa']=$_POST['cursa'];
	    capcalera("Escull la data d'inici real "); 

	 ?>
<div>
	
	<form action="inscripcioCursa3.php" method="post">
	<p><label>Inici cursa:</label>
    <input type="datetime-local" step="1" name="inicireal" required></p>
    <p><input type="submit" value="Guardar"></p>
	</form>
	<?php 	
	peu("Tornar al menú principal","menu.php");
?>
</body>
</html>