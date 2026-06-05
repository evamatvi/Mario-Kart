#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica: mostrar vehicles</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar vehicles"); 
  
  //Fer la consulta on es mostren els vehicles
	$consulta="SELECT v.codi, v.descripcio, v.color, v.combustible, v.consum, c.preuUnitat * v.consum AS cost_per_100km, 
       u.nom || ' ' || u.cognoms AS propietari 
	   FROM Vehicles v JOIN Combustibles c ON v.combustible = c.descripcio
	   JOIN Usuaris u ON v.propietari = u.alias
	   ORDER BY 1";
	   
	 //Preparar la comanda
	$comanda = oci_parse($conn, $consulta);
	if (!$comanda) { mostraErrorParser($conn,$consulta);} // mostrem error i avortem
	
	//Executar la comanda
	$exit=oci_execute($comanda);
	if (!$exit) { mostraErrorExecucio($comanda);} // mostrem error i avortem
	$numColumnes=oci_num_fields($comanda);
	
	echo "<table>\n";
	echo "  <tr>";
	for ($i=1;$i<=$numColumnes; $i++) {
		echo "<th>".htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>"; 
	}
	echo "</tr>\n";
	// Recórrer les files
	while (($row = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
		echo "  <tr>";
		foreach ($row as $element) {
			echo "<td>".($element !== null ? 
			             htmlentities($element, ENT_QUOTES) : 
			             "&nbsp;") . "</td>";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
	
	//Alliberar memòria assoicada a la comanda
	oci_free_statement($comanda);
	//Tancar connexió
	oci_close($conn);
  peu("Tornar al menú principal","menu.php");
?>
</body>
</html>
