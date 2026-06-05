#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: Consulta la quantitat total de pinky ingerida entre dues dates</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar quantitat total de pinky ingerida per cada personatge entre dues dates seleccionades");
//Seleccionar alias i quantitat de Pinky entre les dues dates entrades
  $consulta = "
  SELECT alias_personatge AS ALIAS_PERSONATGE, 
         SUM(quantitat_pinky) AS QUANTITAT_PINKY, 
         MAX(data_hora_cerimonia) AS ULTIMA_CERIMONIA
  FROM Cerimonies
  WHERE TRUNC (data_hora_cerimonia) BETWEEN TO_DATE(:dataInicial, 'YYYY-MM-DD') 
                                AND TO_DATE(:dataFinal, 'YYYY-MM-DD')
  GROUP BY alias_personatge
  ORDER BY QUANTITAT_PINKY DESC, ULTIMA_CERIMONIA DESC";
;
  $comanda = oci_parse($conn, $consulta);
  if (!$comanda) { mostraErrorParser($conn,$consulta);}
  oci_bind_by_name($comanda, ":dataInicial", $_POST['dataInicial']);
  oci_bind_by_name($comanda, ":dataFinal", $_POST['dataFinal']);  $exit=oci_execute($comanda);
  $exit = oci_execute($comanda);
  if (!$exit) { 
      mostraErrorExecucio($comanda); 
  }

  $col=0;
  while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
      if ($col === 0) {
          echo "<table border='1'>
                  <tr>
                      <th>Alias Personatge</th>
                      <th>Quantitat Pinky</th>
                  </tr>";
      }
      $col++; // Comptar cada fila que obtenim
      echo "<tr>
              <td>{$fila['ALIAS_PERSONATGE']}</td>
              <td>{$fila['QUANTITAT_PINKY']}</td>
            </tr>";
  }

  // Tancar la taula si s'ha generat
  if ($col > 0) {
      echo "</table>";
  } else {
      // Si no hi ha resultats, mostrar un missatge
      echo "<p>No hi ha cap personatge que hagi participat en cerimònies entre les dates seleccionades.</p>";
  }
  // Alliberar recursos
  oci_free_statement($comanda);
  oci_close($conn);

  peu("Tornar al submenú", "opcioF.php");
  peu("Tornar al menú principal", "menu.php");
?>
</body>
</html>
