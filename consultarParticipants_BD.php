#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Pràctica PHP: Consultar participants d'una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css">
</head>
<body>
<?php
  include 'funcions.php';    
  iniciaSessio();
  connecta($conn);

  // Comprovar que s'ha seleccionat una cursa, si no s'ha seleccionat es mostra error
  $cursaSeleccionada = $_POST['cursa'] ?? null;
  if (!$cursaSeleccionada) {
      echo "<p>Error: No hi ha cap cursa seleccionada.</p>";
      exit;
  }

  // Comprovar si la cursa està tancada
  $consultaCursa = "SELECT millorTemps
                    FROM CURSES
                    WHERE codi = :cursa AND millorTemps IS NOT NULL";
  $comandaCursa = oci_parse($conn, $consultaCursa);
  if (!$comandaCursa) {
      mostraErrorParser($conn, $consultaCursa);
  }
  oci_bind_by_name($comandaCursa, ":cursa", $cursaSeleccionada);
  $exitCursa = oci_execute($comandaCursa);
  if (!$exitCursa) {
      mostraErrorExecucio($comandaCursa);
  }
  $filaCursa = oci_fetch_array($comandaCursa, OCI_ASSOC + OCI_RETURN_NULLS);
if ($filaCursa) {
    $cursaTancada = true;
} else {
    $cursaTancada = false;
}

 //Si la cursa no s'ha tancat
  if (!$cursaTancada) {
      echo "<h2>Llistat de participants</h2>\n";
      $consultaParticipants = "SELECT v.codi AS Vehicle, v.descripcio AS Descripcio, pc.personatge AS Personatge
                               FROM ParticipantsCurses pc
                               JOIN VEHICLES v ON pc.vehicle = v.codi
                               WHERE pc.cursa = :cursa";
  } else {
      // Si la cursa està tancada
      echo "<h2>Classificació de la cursa</h2>\n";
      $consultaParticipants = "SELECT v.codi AS Vehicle, v.descripcio AS Descripcio, pc.personatge AS Personatge,
                                      CASE
                                          WHEN pc.temps IS NOT NULL THEN TO_CHAR(pc.temps)
                                          ELSE 'Abandonat'
                                      END AS Temps
                               FROM ParticipantsCurses pc
                               JOIN VEHICLES v ON pc.vehicle = v.codi
                               WHERE pc.cursa = :cursa
                               ORDER BY pc.temps ASC";
  }

  // Executar la consulta de participants
  $comandaParticipants = oci_parse($conn, $consultaParticipants);
  if (!$comandaParticipants) {
      mostraErrorParser($conn, $consultaParticipants);
  }
  oci_bind_by_name($comandaParticipants, ":cursa", $cursaSeleccionada);
  $exitParticipants = oci_execute($comandaParticipants);
  if (!$exitParticipants) {
      mostraErrorExecucio($comandaParticipants);
  }

  // Raula amb els resultats
  $filaParticipants = oci_fetch_array($comandaParticipants, OCI_ASSOC + OCI_RETURN_NULLS);
  if (!$filaParticipants) {
      echo "<p>No hi ha participants registrats en aquesta cursa.</p>\n";
  } else {
      echo "<table>\n";
      echo "<tr>";
      foreach (array_keys($filaParticipants) as $camp) {
          echo "<th>" . htmlentities($camp, ENT_QUOTES) . "</th>";
      }
      echo "</tr>\n";

      do {
          echo "<tr>";
          foreach ($filaParticipants as $valor) {
              echo "<td>" . ($valor !== null ? htmlentities($valor, ENT_QUOTES) : "&nbsp;") . "</td>";
          }
          echo "</tr>\n";
      } while (($filaParticipants = oci_fetch_array($comandaParticipants, OCI_ASSOC + OCI_RETURN_NULLS)) != false);
      echo "</table>\n";
  }

  oci_free_statement($comandaCursa);
  oci_free_statement($comandaParticipants);
  oci_close($conn);

  peu("Tornar al menú principal", "menu.php");
?>
</body>
</html>

