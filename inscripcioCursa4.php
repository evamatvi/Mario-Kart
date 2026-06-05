#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Practica PHP: Inscriure una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Triar personatge i vehicle"); 
 
 ?>
<div>
  <form action="inscripcioCursa5.php" method="post">
  <p><label>Personatges:</label>
      <select name="personatge">
	  
<?php 

	//Selecionar l'alies de personatges amb el mateix usuari
    $personatges = "SELECT alias
			FROM personatges
			WHERE usuari =:usuari
	";
	//Preparar i executar la comanda
    $comanda = oci_parse($conn, $personatges);
	oci_bind_by_name($comanda,":usuari",$_POST['usuari']);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['ALIAS'] . "\">" . $fila['ALIAS'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>
    <p><label>Vehicles:</label>
      <select name="vehicle">
<?php 
	//Seleccionar els vehicles amb el mateix propietari
    $vehicles = "SELECT codi, descripcio
			FROM vehicles
			WHERE propietari = :propietari";
    $comanda = oci_parse($conn, $vehicles);
	oci_bind_by_name($comanda,":propietari",$_POST['usuari']);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['CODI'] . "\">" . $fila['DESCRIPCIO'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>      
  <p><label>&nbsp;</label><input type = "submit" value="Inscriure" required></p>
  </form>
	</div>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>