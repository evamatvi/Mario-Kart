#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta charset="UTF-8">
  <title>Pràctica PHP: Entrar temps</title>
  <link rel="stylesheet" href="exemple.css" type="text/css">
</head>
<body>
<?php
include 'funcions.php';
iniciaSessio();
connecta($conn);

if (!isset($_POST['cursa'])) {
    echo "No s'ha especificat el codi de la cursa.";
    exit;
}

$cursa = $_POST['cursa'];


// Actualitzar els temps dels participants
foreach ($_POST as $clau => $tempsText) {
    if ($clau !== 'cursa' && $clau !== 'submit') {
        if (!empty($tempsText)) {
            $temps = round(substr($tempsText, 0, 2) + substr($tempsText, 3, 2) / 60, 2);
            $actualitzacio = "UPDATE ParticipantsCurses SET temps = :temps WHERE cursa = :codi AND personatge = :personatge";
            $comanda = oci_parse($conn, $actualitzacio);
            oci_bind_by_name($comanda, ":temps", $temps);
            oci_bind_by_name($comanda, ":codi", $cursa);
            oci_bind_by_name($comanda, ":personatge", $clau);
            oci_execute($comanda);
		
			
        }
    }
}

// Obtenir el temps més lent despres d'actualitzar els temps
$consultaLent = "
SELECT MAX(temps) AS temps_mes_lent
FROM ParticipantsCurses
WHERE cursa = :codi";
$comandaLent = oci_parse($conn, $consultaLent);
oci_bind_by_name($comandaLent, ":codi", $cursa);
oci_execute($comandaLent);
$filaLent = oci_fetch_array($comandaLent, OCI_ASSOC);
$tempsMesLent = $filaLent['TEMPS_MES_LENT'] ?? 0;

$consultaIniciCursa = "SELECT iniciReal FROM Curses WHERE codi = :codi";
$comandaInici = oci_parse($conn, $consultaIniciCursa);
oci_bind_by_name($comandaInici, ":codi", $cursa);
oci_execute($comandaInici);
$filaInici = oci_fetch_array($comandaInici, OCI_ASSOC);


//Comprovem amb PHP si la cerimonia és dues hores més tard del temps més lent (es podria fer directament al insert, però ho he fet aquí perquè quedés separat)
$data_hora_cerimonia = "";
if ($filaInici['INICIREAL']) {
    $data_hora_cerimonia_query = "SELECT TO_CHAR(iniciReal + (:temps_mes_lent/1440) + (2/24), 'YYYY-MM-DD HH24:MI:SS') AS data_hora_cerimonia FROM Curses WHERE codi = :codi";
    $comandaDataCerimonia = oci_parse($conn, $data_hora_cerimonia_query);
    oci_bind_by_name($comandaDataCerimonia, ":temps_mes_lent", $tempsMesLent);
    oci_bind_by_name($comandaDataCerimonia, ":codi", $cursa);
    oci_execute($comandaDataCerimonia);
    $filaDataCerimonia = oci_fetch_array($comandaDataCerimonia, OCI_ASSOC);
    $data_hora_cerimonia = $filaDataCerimonia['DATA_HORA_CERIMONIA'] ?? '';
}

// Obtenir els temps dels participants i calcular el millor temps
$tempsParticipants = [];
foreach ($_POST as $clau => $tempsText) {
    if ($clau !== 'cursa' && $clau !== 'submit') {
        if (!empty($tempsText)) {
            $temps = round(substr($tempsText, 0, 2) + substr($tempsText, 3, 2) / 60, 2);
            $tempsParticipants[$clau] = $temps;
        }
    }
}

// Ordenar els temps de menor a major amb la comanda assort
asort($tempsParticipants);

// Mostrar el millor temps, si és en singular mostrarà minut i en plural minuts
if (!empty($tempsParticipants)) {
    $millorTemps = min($tempsParticipants);
    $millorParticipant = array_key_first($tempsParticipants);
    $unitatTemps = ($millorTemps == 1) ? "minut" : "minuts";
    echo "<p>El millor temps de la cursa <b>" . htmlspecialchars($cursa) . "</b> és de <b>" . htmlspecialchars($millorTemps) . "</b> $unitatTemps </p>";
} else {
    echo "No hi ha temps registrats per aquesta cursa.";
    exit;
}

//Mostrar 3 posicions podi, també mostrar empats
$participantsPodium = []; //vector dels participants del podu
$tempsAnterior = null; //temps d'abans és nul
$posicioActual = 1; // Comencem per la primera posició
$posicionsSeleccionades = 0;

foreach ($tempsParticipants as $personatge => $temps) {
    // Si ja hem seleccionat 3 posicions diferents i el temps actual és diferent, break
    if ($posicionsSeleccionades >= 3 && $temps !== $tempsAnterior) {
        break;
    }

    // Incrementar la posició si el temps és diferent
    if ($temps !== $tempsAnterior) {
        $posicionsSeleccionades++;
        $posicioActual = $posicionsSeleccionades; // Actualitzar posició
    }

    // Afegir el participant al podi a la posició actual
    if (!isset($participantsPodium[$posicioActual])) {
        $participantsPodium[$posicioActual] = [];
    }
    $participantsPodium[$posicioActual][] = $personatge;

    // Actualitzar el temps anterior
    $tempsAnterior = $temps;
}
 foreach ($participantsPodium as $posicio => $corredors) {
    foreach ($corredors as $personatge) {
		
        // Calcula Pinky segons les curses recents
	$litresPinky=0;
        $consultaCursesRecents = "
SELECT
    NVL(SUM(
        CASE
            WHEN c1.posicio = 1 THEN 1
            WHEN c1.posicio = 2 THEN 0.5
            WHEN c1.posicio = 3 THEN 0.25
            ELSE 0
        END
    ), 0) AS LITRES_TOTALS
FROM Cerimonies c1
JOIN Curses c ON c.codi=c1.codiCursa
WHERE c1.alias_personatge = :personatge
  AND TO_CHAR(iniciReal, 'YYYY-MM-DD HH24:MI:SS')
      BETWEEN TO_CHAR(TO_DATE(:data_hora_cerimonia, 'YYYY-MM-DD HH24:MI:SS') - INTERVAL '30' DAY, 'YYYY-MM-DD HH24:MI:SS')
          AND TO_CHAR(TO_DATE(:data_hora_cerimonia, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')";


		  
$comandaCursesRecents = oci_parse($conn, $consultaCursesRecents);
$data_hora_cerimonia = date('Y-m-d H:i:s', strtotime($data_hora_cerimonia));
oci_bind_by_name($comandaCursesRecents, ":personatge", $personatge);
oci_bind_by_name($comandaCursesRecents, ":data_hora_cerimonia", $data_hora_cerimonia);
			

oci_execute($comandaCursesRecents);

if ($filaCursaRecents = oci_fetch_array($comandaCursesRecents, OCI_ASSOC)) {
    $litresPinky = $filaCursaRecents['LITRES_TOTALS']; 
} else {
    $litresPinky = 0;
}


// Afegir litres per la posició actual
if ($posicio == 1) {
    $litresPinky += 1;
} elseif ($posicio == 2) {
    $litresPinky += 0.5;
} elseif ($posicio == 3) {
    $litresPinky += 0.25;
}


 // Inserir a la taula Cerimonies, inserim ja la data_hora_cerimonia calculada abans al bloc php
$inserirCerimonia = "
            INSERT INTO Cerimonies (codiCursa, alias_personatge, posicio, quantitat_pinky, data_hora_cerimonia)
            VALUES (:codi, :personatge, :posicio, :quantitat_pinky, TO_DATE(:data_hora_cerimonia, 'YYYY-MM-DD HH24:MI:SS'))";
$comandaInserir = oci_parse($conn, $inserirCerimonia);
oci_bind_by_name($comandaInserir, ":codi", $cursa);
oci_bind_by_name($comandaInserir, ":personatge", $personatge);
oci_bind_by_name($comandaInserir, ":posicio", $posicio);
oci_bind_by_name($comandaInserir, ":quantitat_pinky", $litresPinky);
oci_bind_by_name($comandaInserir, ":data_hora_cerimonia", $data_hora_cerimonia);


// Executar i verificar errors
if (!oci_execute($comandaInserir)) {
$error = oci_error($comandaInserir);
         echo "<p>Error en inserir a Cerimonies: " . htmlspecialchars($error['message']) . "</p>";
        }
else {
        echo "<p>S'ha inserit correctament el personatge <b>$personatge</b> a la posició <b>$posicio</b>.</p>";
        }
    }
}

echo "<p>La cerimònia de premis s'ha registrat correctament.</p>";

//Alliberar memòria i tancar connexió
oci_free_statement($comandaInserir);
oci_close($conn);
peu("Tornar al menú principal", "menu.php");
?>
</body>
</html>
