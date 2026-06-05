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
  capcalera("Consultar participants d'una cursa"); 
?>
  <h1>Selecciona una cursa per consultar els participants:</h1>
  <p>Selecciona una cursa del llistat per veure els seus participants</p>
  <form action="consultarParticipants_BD.php" method="post">
    <label for="cursa">Cursa:</label>
    <select name="cursa" id="cursa" required>
      <option value="" disabled selected>Escull una cursa</option>
      <?php 
          $consultaCurses = "SELECT codi, nom FROM Curses WHERE iniciReal IS NOT NULL ORDER BY nom";
          $comanda = oci_parse($conn, $consultaCurses);
          $exit = oci_execute($comanda);

          if (!$exit) {
              mostraErrorExecucio($comanda);
          }

          while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
              echo "<option value=\"" . htmlentities($fila['CODI'], ENT_QUOTES) . "\">" . htmlentities($fila['NOM'], ENT_QUOTES) . "</option>\n";
          }
      ?>
    </select>
    <br><br>
    <button type="submit" class="btn">Consultar</button>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
