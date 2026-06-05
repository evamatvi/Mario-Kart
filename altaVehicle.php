#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Pràctica PHP: Afegir Vehicle, entrada de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css">
</head>
<body>
<?php
    include 'funcions.php';    
    iniciaSessio();
    connecta($conn);
    capcalera("Afegir un vehicle");
 ?>
 <div>
 <form action="altaVehicle_BD.php" method="post">
  <p><label>Descripció: </label><input type="text" name="descripcio" required></p>
  <p><label>Color: </label><input type="text" name="color" required></p>
  <p><label>Consum (L/100km): </label><input type="number" step="0.01" name="consum"  min="0"  required></p>
  <p><label>Data de compra: </label><input type="date" name="dataCompra" required></p>
  <p><label>Preu: </label><input type="number" name="preu" step="0.01" min="0" required></p>
	<p><label>Grup de vehicles: </label>
	<select name="grupVehicle" required>
	  <?php // Desplegable amb grup de vehicles
		$consultaGrups = "SELECT codi FROM GrupsVehicles";  
		$stmtGrups = oci_parse($conn, $consultaGrups);
		oci_execute($stmtGrups);
		while ($filaGrups = oci_fetch_array($stmtGrups, OCI_ASSOC + OCI_RETURN_NULLS)) {
			echo "<option value=\"" . htmlspecialchars($filaGrups['CODI']) . "\">" . htmlspecialchars($filaGrups['CODI']) . "</option>\n";
		}
	  ?>
</select>
</p>
  <p><label>Combustible: </label>  
  <select name="combustible" required>

  <?php //Desplegable amb combustible
        $consultaCombustible = "SELECT descripcio FROM Combustibles";
        $stmtCombustible = oci_parse($conn, $consultaCombustible);
        oci_execute($stmtCombustible);
        while ($filaCombustible = oci_fetch_array($stmtCombustible, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo "<option value=\"" . htmlspecialchars($filaCombustible['DESCRIPCIO']) . "\">" . htmlspecialchars($filaCombustible['DESCRIPCIO']) . "</option>\n";
        }
      ?>
    </select>
  </p> 
<p><label>Propietari: </label>
  <select name="propietari" required>
    <?php //Desplegable amb propietari
      $consultaPropietari = "SELECT alias FROM USUARIS";  
      $stmtPropietari = oci_parse($conn, $consultaPropietari);
      oci_execute($stmtPropietari);
      while ($filaPropietari = oci_fetch_array($stmtPropietari, OCI_ASSOC + OCI_RETURN_NULLS)) {
          echo "<option value=\"" . htmlspecialchars($filaPropietari['ALIAS']) . "\">" . htmlspecialchars($filaPropietari['ALIAS']) . "</option>\n";
      }
    ?>
  </select>
</p>

  <p><input type="submit" value="Afegir vehicle"></p>
  </form>
</div>
<?php peu("Tornar al menú principal", "menu.php"); ?>
</body>
</html>
