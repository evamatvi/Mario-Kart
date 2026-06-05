#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Pràctica PHP: Inscriure una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css">
</head>
<body>
<?php
  include 'funcions.php';    
  iniciaSessio();
  connecta($conn);
  capcalera("Inserir a ParticipantsCursa");
		//Inserir a participantscurses
		$consulta= "INSERT INTO PARTICIPANTSCURSES(CURSA,VEHICLE,PERSONATGE, TEMPS)
					VALUES(:codiCursa,:vehicle,:personatge, NULL)";
		//Preparar i executar la comanda
		$comanda = oci_parse($conn, $consulta);
		oci_bind_by_name($comanda,":codiCursa",$_SESSION['codiCursa']);
		oci_bind_by_name($comanda,":vehicle",$_POST['vehicle']);
		oci_bind_by_name($comanda,":personatge",$_POST['personatge']);


		$exit=oci_execute($comanda);
		if (!$exit){
			mostraErrorExecucio($comanda);
		}
		echo("<p>S'ha inscrit el personatge ".$_POST['personatge']
		." amb el vehicle ".$_POST['vehicle']);
	
		if (!$exit){
			mostraErrorExecucio($comanda);
		}
		
		//Si volem inserir-ne més
		echo "<form action=\"inscripcioCursa2.php\" method=\"post\">";
		echo "    <input type=\"hidden\" name=\"cursa\" value=".$_SESSION['codiCursa'].">";
		echo "    <p><input type=\"submit\" value=\"Inscriure una altra parella\" required></p>";
		echo "</form>";
		//Si volem tornar al menú
		echo "<form action=\"menu.php\" method=\"post\">";
		echo "    <p><input type=\"submit\" value=\"Acabar les incripcions\" required></p>";
		echo "</form>";
		echo "</div>";
	
  oci_free_statement($comanda);
  oci_close($conn);
?>

</body>
</html>