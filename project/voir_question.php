<?php 
session_start();

include 'init.php';

//include 'barre_etat.php';

//estConnecte();

$noOrdreQuestion = "";
$typeQuestion = "";
$texteQuestion = "";
$noSondage = "";
$ordreReponse = "";
$texteChoix = "";
$titreSondage = "";
$erreurSondage = "";
$descTypeQue = "";
$erreurReponse = "Aucun choix de réponse associé";
$tableauChoixRep = "";

if (isset($_GET['ID_QUESTION'])){

// On trouve la question grâce à son ID provenant de la page sondage_edit.php
$where = " where ID_QUESTION = ". $_GET['ID_QUESTION']; 

$stid = oci_parse($conn, 'select * from TP3_QUESTION ' . $where . ' fetch first 1 rows only');
oci_execute($stid);

if(($question = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
    $noOrdreQuestion = $question["ORDRE_QUESTION"];
    $typeQuestion = $question["CODE_TYPE_QUESTION"];
    $texteQuestion = $question["TEXTE_QUE"];
    $noSondage = $question["NO_SONDAGE"];
    $erreur = "";


    // On trouve les choix de réponses associé au ID de la question

    $tableauChoixRep = '<table>
              <tr>
                <th>Ordre</th>
                <th>Texte</th>
            </tr>';
    
    if ($typeQuestion == 'RB11')
        {
            $erreurReponse = "";
            $texteChoix = "Question à développement";
            $tableauChoixRep = '<table>
                <tr>
                    <td>'.$texteChoix.'</td>
                  </tr>
                </table>';
            
    }else {
        $stid = oci_parse($conn, 'select * from TP3_CHOIX_REPONSE ' . $where . 'order by ORDRE_REPONSE');
        oci_execute($stid);
            while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                $erreurReponse = "";
                $ordreReponse = $row['ORDRE_REPONSE'];
                $texteChoix = $row['TEXTE_CHO'];
                $tableauChoixRep .= '<tr>
                        <td>'.$ordreReponse.'</td>
                        <td>'.$texteChoix.'</td>               
                    </tr>';
            }  
        $tableauChoixRep .= '</table>';

    }
    
    
    // On trouve le titre du sondage
    $where = " where NO_SONDAGE = ". $noSondage;
    $stid = oci_parse($conn, 'select * from TP3_SONDAGE ' . $where . ' fetch first 1 rows only');
    oci_execute($stid);
    
    if (($sondage = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
        $titreSondage = $sondage["TITRE_SON"];
    } else{
        $erreurSondage = "Aucun sondage associé";
    }
    
    
    // On converti le code du type de question avec la description du type
    $where = " where CODE_TYPE_QUESTION =  '".$typeQuestion."'";
    
    $stid = oci_parse($conn, 'select * from TP3_TYPE_QUESTION ' . $where . ' fetch first 1 rows only');
    oci_execute($stid);
    if(($typeQuestion = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
        $descTypeQue = $typeQuestion["DESC_TYPE_QUE"];
    }


}else{
    $erreur = "Aucune question associé avec ce sondage";
}
}

   
    
oci_close($conn);
?>
<!DOCTYPE html>

<html>
<head>
	<title>Détails de la question</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
<?php include 'header.php'; ?>
 <?php //afficherEtatConnexion(); ?>

<!-- On affiche le tout -->

	Sondage:<br>
	<label for="NO_SONDAGE"><?php echo $titreSondage;?></label><br><br>
	<?php echo $erreurSondage;?>
	Question: 
	<?php echo $erreur;?><br>
	<table>
		<tr>
			<th>Ordre</th>
			<th>Type</th>
			<th>Texte</th>
		</tr>
		<tr> 
			<td> <?php echo $noOrdreQuestion;?></td>
			<td> <?php echo $descTypeQue;?></td>
			<td> <?php echo $texteQuestion;?></td>
		</tr>
	</table><br>

	Réponse:
	<?php echo $tableauChoixRep;?> 
    <?php echo $erreurReponse;?><br><br>

    <!-- Bouton contenant l'hyperlien pour revenir à la page sondage_edit -->
	<input type="button" onclick="window.location.href='sondage_edit.php?NO_SONDAGE=<?php echo $noSondage;?>';" value="Retour au sondage"/>



<?php include 'footer.php'; ?>
</body>

</html>
