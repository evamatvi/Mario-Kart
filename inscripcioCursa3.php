#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Practica PHP: Inscriure en una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
	//Seleccionar el nom i la inscripció de curses
	$consulta = "SELECT nom, inscripcio
			FROM CURSES
			WHERE codi = :codicursa
	";
    $comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda,":codicursa",$_SESSION['codiCursa']);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }

	$fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS);

    capcalera("Inscriure un personatge i un usuari"); 
	
	$tmp=substr($_POST["inicireal"],0,10).' '.substr ($_POST["inicireal"],11,5);
	//Actualitzar les curses amb el nou temps
	  $actualitzacio = "UPDATE Curses SET iniciReal =to_date(:dataReal,'YYYY-MM-DD HH24:MI') WHERE codi = :codiCursa";
            $comanda = oci_parse($conn, $actualitzacio);
            oci_bind_by_name($comanda, ":dataReal", $tmp );
            oci_bind_by_name($comanda, ":codiCursa",$_SESSION['codiCursa']);
            oci_execute($comanda)
 ?>
<div>
  <form action="inscripcioCursa4.php" method="post">
  <p><label>Usuaris:</label>
      <select name="usuari">
<?php 
    //Seleccionar els usuaris amb prou saldo
    $usuaris = "SELECT alias
			FROM usuaris 
			wHERE saldo>=(select inscripcio from curses where codi=:codicursa) AND 
			alias NOT IN (SELECT p.usuari 
				FROM PARTICIPANTSCURSES pC 
				JOIN personatges p ON p.alias=pC.personatge
				WHERE pC.cursa=:codicursa)";
			
	//Preparar la comanda		
    $comanda = oci_parse($conn, $usuaris);
	oci_bind_by_name($comanda,":codicursa",$_SESSION['codiCursa']);
	//Executar la comanda
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['ALIAS'] . "\">" . $fila['ALIAS'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>   
 
  <p><label>&nbsp;</label><input type = "submit" value="Inscriure" required></p>
  </form>
	</div>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
