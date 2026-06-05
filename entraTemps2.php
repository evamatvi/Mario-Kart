#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Practica PHP: Entrar temps</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>


<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Entrar temps"); 
?>
<div>
  <form action="entraTemps3_BD.php" method="post">
<?php
	//Fer un SELECT per mostrar l'usuari, el personatge i el vehicle
	$consulta = "SELECT p.usuari, pc.personatge, pc.vehicle
				FROM ParticipantsCurses pc JOIN personatges p
				ON pc.personatge = p.alias
				WHERE pc.cursa = :codiCursa";
	//Preparar la comanda
    $comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda,":codiCursa",$_POST['cursa']);
	//Executar la comanda
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
	while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
		    echo $fila['USUARI'] . ", " . $fila['VEHICLE'] . ", " . $fila['PERSONATGE'];
			echo '<br>';
			echo '<input type="time" name="'.$fila['PERSONATGE'].'"></p>'."\n";
	}
	echo '    <p><input type = "hidden" name="cursa" value="'.$_POST['cursa'].'"></p>';	
 ?>
     <input type = "submit" value="Entrar"></p>
  </form>
 </div>
 <?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>

