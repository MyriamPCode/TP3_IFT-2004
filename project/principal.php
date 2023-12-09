<!DOCTYPE html>
<html>

<head>
	<title>TP3 - Équipe 10</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"/>
</head>

<body>
<?php session_start(); ?>
<?php include 'init.php' ?>

<h2>Sondages en cours</h2>
<table>
	<tr>
		<th>Numéro</th>
		<th>Projet</th>
		<th>Titre</th>
		<th>Date de début</th>
		<th>Date de fin</th>
		<th>Rapport</th>
		<th>Modifier</th>
	</tr>
	<?php 
	   $selectSondages = "SELECT * FROM TP3_SONDAGE";
	   $stid = performDatabaseQuery($selectSondages);
	   $keys = array('NO_SONDAGE', 'CODE_PROJET', 'TITRE_SON', 'DATE_DEBUT_SON', 'DATE_FIN_SON');
	   
	   while (($sondage = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
	       
	       echo "<tr>\n";
	       foreach ($keys as $item) {
	           if($item  === 'NO_SONDAGE'){
	               echo "<td><a href='liste_questions.php&NO_SONDAGE=<". $sondage[$item] .">'>" . $sondage[$item] . "</a></td>\n";
	           } else {
	               echo "<td>" . $sondage[$item] . "</td>\n";
	           }
           }
           
           echo "<td><a href='rapport.php'>Rapport</a></td>\n";
           echo "<td><a href='sondage_edit.php&NO_SONDAGE=<". $sondage['NO_SONDAGE'] .">''>Modifier</a></td>\n";
           echo "</tr>\n";
	   }
	   
	   closeDatabaseConnection();
	?>
</table>

<h2>Sondages archivés</h2>
<table>
	<tr>
		<th>Numéro</th>
		<th>Projet</th>
		<th>Titre</th>
		<th>Date de début</th>
		<th>Date de fin</th>
		<th>Rapport</th>
	</tr>
	<?php 
	   $selectSondages = "SELECT * FROM TP3_SONDAGE_ARCHIVE";
	   $stid = performDatabaseQuery($selectSondages);
	   $keys = array('NO_SONDAGE', 'CODE_PROJET', 'TITRE_SON', 'DATE_DEBUT_SON', 'DATE_FIN_SON');
	   
	   while (($sondage = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
	       
	       echo "<tr>\n";
	       foreach ($keys as $item) {
	           if($item  === 'NO_SONDAGE'){
	               echo "<td><a href='liste_questions.php&NO_SONDAGE=<". $sondage[$item] .">'>" . $sondage[$item] . "</a></td>\n";
	           } else {
	               echo "<td>" . $sondage[$item] . "</td>\n";
	           }
           }
           
           echo "<td><a href='rapport.php'>Rapport</a></td>\n";
           echo "</tr>\n";
	   }
	   
	   closeDatabaseConnection();
	?>
</table>

</body>
