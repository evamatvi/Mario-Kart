#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: submenú opció F</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';
  iniciaSessio();
  // emmagatzem usuari i password en una sessió per tenir-los disponibles 
  if (!empty($_POST['username'])) { // Hem arribat des de index.html
    $_SESSION['usuari'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    // ara comprovem usuari i password intentant establir connexió amb Oracle    
    connecta($conn);
   }
?>
  <h1>Pràctica PHP-Kario Mart</h1>
  <h2>Escull un subapartat</h2>
  <p> <a class="menu" href="quantitatPinky.php">f1) Consulta la quantitat total de pinky ingerida per a cada personatge entre dues dates</a></p>
  <p> <a class="menu" href="consultaCerimonies.php">f2) Consulta les cerimonies en què ha participat un personatge concret</a></p>
 
  <?php peu("Tornar al menú","menu.php");?>
</body>
</html>
