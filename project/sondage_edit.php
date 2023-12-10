<?php
session_start();

include 'init.php';
//include 'barre_etat.php';

//estConnecte();

date_default_timezone_set("America/Toronto");

$dateCreation = "";
$dateDebut = "";
$dateFin = "";
$titreSondage = "";
$codeProjet = "";
$nomProjet = "";
$listeDeroulante = '<select name="NOM_PRO" id="CODE_PROJET">';
$erreurSondage = "";
$erreurProjet = "Aucun projet ne correspond à ce code";
$listeQuestions = "";
$erreurQuestion = "Aucune question associée";



// On recupère la valeur du NO_SONDAGE de la page liste_sondage pour le mode Modification
if (isset($_GET["NO_SONDAGE"])){
    $noSondage = $_GET["NO_SONDAGE"];
    $mode = "Modification";
} else if (isset($_POST["mode"])){
    // on a cliqué sur OK et on exécute la suite de modification ou ajout
    $mode = $_POST["mode"];
    $noSondage = $_POST["noSondage"];
    $dateCreation = $_POST["dateCreationSon"];
}else {
    $noSondage = "";
    // Si aucun numéro est passé en paramètre, c'est qu'on est en mode Ajout
    $mode = "Ajout";
    $dateCreation = date( "Y-m-d");
    
}

// si on est dans le cas qu'un numéro de sondage a été passé en paramètre et qu'on est en mode modification sans avoir encore cliqué sur OK
if ((isset($_GET["NO_SONDAGE"])) & ($mode == "Modification")){
        
    // On va chercher les informations dans la table TP3_SONDAGE
    $where = " where NO_SONDAGE = ". $noSondage;
    $stid = oci_parse($conn, 'select * from TP3_SONDAGE ' . $where . ' fetch first 1 rows only');
    oci_execute($stid);
    
    // On met le résultat dans des variables pour les afficher dans le HTML
    if(($sondage = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
        $titreSondage = $sondage["TITRE_SON"];
        $dateCreation = date("Y-m-d", strtotime($sondage["DATE_CREATION_SON"]));
        $dateDebut = date("Y-m-d", strtotime($sondage["DATE_DEBUT_SON"]));
        $dateFin = date("Y-m-d", strtotime($sondage["DATE_FIN_SON"]));
        $codeProjet = $sondage["CODE_PROJET"];
    } else {
        // Aucun sondage n'a été trouvé avec le numéro fourni
        $erreurSondage = "Aucun sondage associé à ce numéro";
    }
    
    // On va chercher tous les projets disponibles dans TP3_PROJET pour faire une liste déroulante et sélectionne la description dont le code correspond à celui trouvé dans TP3_SONDAGE
    $stid = oci_parse($conn, 'select * from TP3_PROJET');
    oci_execute($stid);
    while(($projet = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
        $erreurProjet = "";
        $codeProjetCourant = $projet["CODE_PROJET"];
        $nomProjet = $projet["NOM_PRO"];
            $listeDeroulante .= '<option value="'.$codeProjetCourant.'" '. ($codeProjetCourant == $codeProjet? 'Selected':'').'>'.$nomProjet.'</option>';

    } 
    $listeDeroulante .= '</select>';
    
    
    // On va chercher toutes les questions associées au sondage et on les met dans une liste
        $listeQuestions = '<select size="10" name="QUESTIONS" id="QUESTIONS">';
        $selectionner = 'selected';
    $where = " where NO_SONDAGE = '" .$noSondage. "'";
    $stid = oci_parse($conn, 'select * from TP3_QUESTION' .$where. 'order by ORDRE_QUESTION');
    oci_execute($stid);
    while(($question = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
        $erreurQuestion = "";
        $idQuestion = $question["ID_QUESTION"];
        $ordreQuestion = $question["ORDRE_QUESTION"];
        $codeTypeQuestion = $question["CODE_TYPE_QUESTION"];
        $descTypeQue = "";
        $texteQuestion = $question["TEXTE_QUE"];
        
        // On converti le code du type de question avec la description du type
        $where = " where CODE_TYPE_QUESTION =  '".$codeTypeQuestion."'";
    
        $conversionType = oci_parse($conn, 'select * from TP3_TYPE_QUESTION ' . $where . ' fetch first 1 rows only');
        oci_execute($conversionType);
        if(($typeQuestion = oci_fetch_array($conversionType, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
            $descTypeQue = $typeQuestion["DESC_TYPE_QUE"];
        }   
        
        $listeQuestions .= '<option value="'.$idQuestion.'"'.$selectionner.'> ID: '.$idQuestion .', ordre: '. $ordreQuestion .', type: '. $descTypeQue .', texte: '. $texteQuestion. '</option>';
        $selectionner = "";
    }
    $listeQuestions .= '</select>';
    
}
 
// Si on est en mode Ajout et qu'on n'a pas encore cliqué sur OK
    if (($mode == "Ajout") & !(isset($_POST["mode"]))){
        $erreurProjet = "";
        $erreurQuestion = "";
        $erreurSondage = "";
        // On va chercher tous les projets disponibles dans TP3_PROJET pour faire une liste déroulante
        $stid = oci_parse($conn, 'select * from TP3_PROJET');
        oci_execute($stid);
        while(($projet = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false){
            $erreurProjet = "";
            $listeDeroulante .= '<option value="'.$projet["CODE_PROJET"].'">'.$projet["NOM_PRO"].'</option>';
        }    
        $listeDeroulante .= '</select>';
      
    }


oci_close($conn);


 if(isset($_POST['detailQuestion'])){
     $lien = "voir_question.php?ID_QUESTION=".$_POST['QUESTIONS'];
     header("location:".$lien);
 }

if (isset($_POST['ajouter'])){
    $titre = "'".$_POST["TITRE_SON"]."'";
    $projetcode = "'".$_POST["NOM_PRO"]."'";
    
    
    if ($_POST["mode"] == "Ajout"){
        
        if ($_POST["DATE_DEBUT_SON"] === ""){
            $colonneDebut = "";
            $debut = "";
        } else {
            $colonneDebut = ", DATE_DEBUT_SON";
            $debut = ", to_date('".$_POST["DATE_DEBUT_SON"]."', 'RRRR-MM-DD')";
        }
        if ($_POST["DATE_FIN_SON"] === ""){
            $colonneFin = "";
            $fin = "";
        }else {
            $colonneFin = ", DATE_FIN_SON";
            $fin = ", to_date('".$_POST["DATE_FIN_SON"]."', 'RRRR-MM-DD')";
        }
        
        $creation = "'" . $_POST["dateCreationSon"] . "'";
        
        $colonnes = "NO_SONDAGE, DATE_CREATION_SON" . $colonneDebut . $colonneFin . ", TITRE_SON, CODE_PROJET";
        $values = "TP3_NO_SONDAGE_SEQ.nextval, to_date(". $creation.", 'RRRR-MM-DD')" . $debut . $fin .", ".$titre.", ".$projetcode;
        
        //$insertInto = "insert into TP3_SONDAGE (:colonnes) values (:values)";
        $insertInto = "insert into TP3_SONDAGE ($colonnes) values ($values)";
        $stid = oci_parse($conn, $insertInto);
        
        //oci_bind_by_name($stid, ":colonnes", $colonnes);
        //oci_bind_by_name($stid, ":values", $values); 

    } else {
        // mode Modification
        if ($_POST["DATE_DEBUT_SON"] === ""){
            $debut = "";
        } else {
            $debut = ", DATE_DEBUT_SON = to_date('".$_POST["DATE_DEBUT_SON"]."', 'RRRR-MM-DD')";
        }
        if ($_POST["DATE_FIN_SON"] === ""){
            $fin = "";
        }else {
            $fin = ", DATE_FIN_SON =  to_date('".$_POST["DATE_FIN_SON"]."', 'RRRR-MM-DD')";
        }
        $updateTable = "update TP3_SONDAGE set   TITRE_SON = ".$titre.", CODE_PROJET = ".$projetcode . $debut . $fin. " where NO_SONDAGE = ".$noSondage;
        $stid = oci_parse($conn, $updateTable);
    } 
   
    oci_execute($stid);
    oci_close($conn);
    
    header("location:liste_sondages.php");
}

?>
<!DOCTYPE html>

<html>
<head>
<title>Détails du sondage</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
<?php include 'header.php'; ?>
 <?php //afficherEtatConnexion(); ?>


<form method="post" action="sondage_edit.php">
	<p>		
		<?php echo $erreurSondage;?>
        <input type="hidden" name="mode" value="<?php echo $mode;?>">
		Numéro du sondage: <br>
		<input type="text" name="NO_SONDAGE" value="<?php echo $noSondage;?>" disabled><br><br>
		<input type="hidden" name="noSondage" value="<?php echo $noSondage;?>">
		Date de création: <br>
		<input type="date" name="DATE_CREATION_SON" value="<?php echo $dateCreation;?>" disabled><br><br>
		<input type="hidden" name="dateCreationSon" value="<?php echo $dateCreation;?>">
		Date de début: <br>
		<input type="date" name="DATE_DEBUT_SON" value="<?php echo $dateDebut;?>" placeholder="AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" ><br>
		Date de fin: <br>
		<input type="date" name="DATE_FIN_SON" value="<?php echo $dateFin;?>" placeholder="AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" ><br>
		Titre: <br>
		<input type="text" name="TITRE_SON" value="<?php echo $titreSondage;?>" required><br>
		
		
		Projet:<br>
		<?php echo $erreurProjet;?>
		<?php echo $listeDeroulante;?><br>

		<?php if($mode == "Modification"){ echo "Questions:<br>". $erreurQuestion. $listeQuestions;}?>
		<br>
		

			<input type="submit" name="detailQuestion" value="Détails question"><br>
			<input type="submit" name="ajouter" value="OK"><br>
			<input type="button" onclick="window.location.href='liste_sondages.php';" value="Annuler"/><br>
	</p>
</form>
		<!-- Boutons -->


</body>

</html>