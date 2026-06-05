#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Practica PHP: Inscriure  una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>


<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Inscriure  una cursa"); 
 ?>
<div>
  <form action="inscripcioCursa2.php" method="post">
  <p><label>Curses obertes:</label>
      <select name="cursa">
<?php 
	//Fer un SELECT de les curses que estan tancades
    $curses = "SELECT codi, nom
			  FROM Curses
			  WHERE inicireal IS NULL
			  ORDER BY nom";
	//Preparar la comanda
    $comanda = oci_parse($conn, $curses);
	//Executar la comanda
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>      
  <p><label>&nbsp;</label><input type = "submit" value="Inscriure" required></p>
  </form>
	</div>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>