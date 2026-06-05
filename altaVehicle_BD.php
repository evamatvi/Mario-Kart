#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Pràctica PHP: Donar d'alta un vehicle, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css">
</head>
<body>
<?php
  include 'funcions.php';    
  iniciaSessio();
  connecta($conn);
  capcalera("Inserir un vehicle a la base de dades");

//Generar un codi únic d'un vehicle
if (isset($_POST["grupVehicle"], $_POST["descripcio"])) {
    // Aquí substitueix espais amb guions baixos
    $original = substr($_POST["grupVehicle"], 0, 2) . substr(str_replace(' ', '_', $_POST["descripcio"]), 0, 5);
	$codi = $original;
} else {
    echo "Error: Falten dades del formulari.";
    exit;
}

// Comprovar si existeix el codi
$consultaCodi = "SELECT codi FROM Vehicles WHERE codi = :codiVehicle";
$comanda = oci_parse($conn, $consultaCodi);
oci_bind_by_name($comanda, ":codiVehicle", $codi);
oci_execute($comanda);
$existeix = oci_fetch_array($comanda);

// Generar un codi aleatori fins que assegurem que no existeixi
while ($existeix) {
    $codi=$original. rand(100, 999); //Ho concatenem amb l'original
    oci_bind_by_name($comanda, ":codiVehicle", $codi); 
    oci_execute($comanda);
    $existeix = oci_fetch_array($comanda);
}
oci_free_statement($comanda);

//Inserimrels seqüents camps
$sentenciaSQL = "INSERT INTO Vehicles (codi, descripcio, color, consum, dataCompra, preu, grupVehicle, combustible, propietari)
                 VALUES (:codi, :descripcio, :color, :consum, TO_DATE(:dataCompra, 'YYYY-MM-DD'), :preu, :grupVehicle, :combustible, :propietari)";

//Connectar amb la base de dades
$comanda = oci_parse($conn, $sentenciaSQL);

//Associar variables
oci_bind_by_name($comanda, ":codi", $codi);
oci_bind_by_name($comanda, ":descripcio", $_POST["descripcio"]);
oci_bind_by_name($comanda, ":color", $_POST["color"]);
oci_bind_by_name($comanda, ":consum", $_POST["consum"]);
oci_bind_by_name($comanda, ":dataCompra", $_POST["dataCompra"]);
oci_bind_by_name($comanda, ":preu", $_POST["preu"]);
oci_bind_by_name($comanda, ":grupVehicle", $_POST["grupVehicle"]);
oci_bind_by_name($comanda, ":combustible", $_POST["combustible"]);
oci_bind_by_name($comanda, ":propietari", $_POST["propietari"]);

//Executar la comanda
$exit = oci_execute($comanda);
if ($exit) {
    echo "<p>Nou vehicle amb codi " . htmlspecialchars($codi) . " inserit a la base de dades.</p>";
} else {
    mostraErrorExecucio($comanda);
}

//Alliberar memòria i tancar la connexió
oci_free_statement($comanda);
oci_close($conn);
peu("Tornar al menú principal", "menu.php");
?>
</body>
</html>