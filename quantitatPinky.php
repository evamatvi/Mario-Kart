#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: Mostrar quantitat total de pinky entre dues dates</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar la quantitat total de pinky ingerida per cada personatge entre dues dates"); 
?>
  <h1>Selecciona una data inicial i una final:</h1>
  <form action="quantitatPinky_BD.php" method="post">
    <p><label>Data Inicial</label><input type="date" name="dataInicial"></p>
	<p><label>Data Final</label><input type="date" name="dataFinal"></p>
	<p><label>&nbsp</label><input type = "submit" value="Confirma"></p>
  </form>
  
<?php
 peu("Tornar al submenú", "opcioF.php");
 peu("Tornar al menú principal","menu.php");?>
</body>
</html>
