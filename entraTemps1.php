#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: Entrar temps de tots els participants</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Entrar temps dels participants"); 

	//Es mostren les curses tancades
    $cursaSQL = "SELECT codi, nom FROM Curses WHERE iniciReal IS NOT NULL AND codi NOT IN 
	(SELECT DISTINCT codiCursa FROM CERIMONIES)
	ORDER BY nom";
    $comanda = oci_parse($conn, $cursaSQL);
    $exit = oci_execute($comanda);
    if (!$exit) {
        mostraErrorExecucio($comanda);
    }
?>
	
 <form action="entraTemps2.php" method="post">
	  <p><label>Curses tancades:</label>
		  <select name="cursa">
<?php 
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
    }
?>
      </select>
  </p>      
  <p><label>&nbsp;</label><input type="submit" value="Entrar"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php"); ?>
</body>
</html>
