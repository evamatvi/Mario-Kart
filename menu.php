#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Pràctica PHP: menú</title>
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
  <h2>Operacions disponibles</h2>
  <p> <a class="menu" href="altaVehicle.php">a) Donar d'alta un vehicle </a></p>
  <p> <a class="menu" href="inscripcioCursa1.php">b) Inscripció cursa</a></p>
  <p> <a class="menu" href="mostrarVehicles.php">c) Mostrar vehicles</a></p>
  <p> <a class="menu" href="entraTemps1.php">d) Entrar temps de tots els participants</a></p>
  <p> <a class="menu" href="consultarParticipants.php">e) Consultar els participants d'una cursa</a></p>  
  <p> <a class="menu" href="opcioF.php">f) Consultar informació sobre les cerimònies</a></p>  
  <?php peu("Tornar a la pàgina de login","practicaPHP.html");?>
</body>
</html>
