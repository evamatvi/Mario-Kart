#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: Mostrar cerimònies del personatge</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);

  $personatge = $_POST['personatge'];
	//JOIN entre Cerimonies i Curses per consultar la següent informació
  $consulta = "
    SELECT 
      cu.nom AS NOM_CURSA, 
      TO_CHAR(cu.iniciReal, 'YYYY-MM-DD HH24:MI:SS') AS DATA_CURSA,
      ce.posicio AS POSICIO, 
      ce.quantitat_pinky AS QUANTITAT_PINKY
    FROM 
      CERIMONIES ce 
    JOIN 
      CURSES cu ON ce.codiCursa = cu.codi
    WHERE 
      ce.alias_personatge = :personatge
    ORDER BY 
      cu.iniciReal ASC";

  // Preparar la comanda
  $comanda = oci_parse($conn, $consulta);
  if (!$comanda) {
    mostraErrorParser($conn, $consulta);
  }

  oci_bind_by_name($comanda, ":personatge", $personatge);
  $exit = oci_execute($comanda);
  if (!$exit) {
    mostraErrorExecucio($comanda); 
  }

  // Comprovar si hi ha resultats
  $row = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS);
  if (!$row) {
    // Si no hi ha resultats
    echo "<h2>Cerimònies del personatge: " . htmlentities($personatge, ENT_QUOTES) . "</h2>";
    echo "<p>El personatge especificat no ha participat en cap cerimònia.</p>";
  } else {
    // Si hi ha resultats, mostrar taula
    echo "<h2>Cerimònies del personatge: " . htmlentities($personatge, ENT_QUOTES) . "</h2>";
    echo "<table>\n<tr>";

    // Mostrar capçaleres
    foreach (array_keys($row) as $columna) {
      echo "<th>" . htmlentities($columna, ENT_QUOTES) . "</th>";
    }
    echo "</tr>\n";

    echo "<tr>";
    foreach ($row as $element) {
      echo "<td>" . ($element !== null ? htmlentities($element, ENT_QUOTES) : "&nbsp;") . "</td>";
    }
    echo "</tr>\n";

    // Files restants
    while (($row = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
      echo "<tr>";
      foreach ($row as $element) {
        echo "<td>" . ($element !== null ? htmlentities($element, ENT_QUOTES) : "&nbsp;") . "</td>";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
  }

  // Alliberar recursos
  oci_free_statement($comanda);
  oci_close($conn);

  // Peu de pàgina
  peu("Tornar al submenú", "opcioF.php");
  peu("Tornar al menú principal", "menu.php");
?>
</body>
</html>
