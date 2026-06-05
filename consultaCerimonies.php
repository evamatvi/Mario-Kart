#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: Consulta les cerimònies d'un personatge</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Triar personatge"); 
?><div>
  <h1>Selecciona un personatge per consultar les cerimònies:</h1>
  <form action="consultaCerimonies_BD.php" method="post">
  <p><label>Personatge:</label>
      <select name="personatge">
<?php 
	//Fer un formulari per consultar l'alias dels PERSONATGES
	$consulta = "SELECT alias 
			FROM PERSONATGES
	";
    $comanda = oci_parse($conn, $consulta);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['ALIAS'] . "\">" . $fila['ALIAS'] . "</option>\n";
    }
  ?>
	</select></p>
  <p><label>&nbsp;</label><input type = "submit" value="Seleccionar"></p>
  </form>
</div>
 <?php peu("Tornar al submenú","opcioF.php");?>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
