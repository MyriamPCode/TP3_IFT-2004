<?php 
session_start();

include 'init.php';


$where = " where ID_QUESTION = ". $_POST['ID_QUESTION'];

$stid = oci_parse($conn, 'select * from TP3_QUESTION ' . $where . ' fetch first 1 rows only');
oci_execute($stid);
$noOrdreQuestion = "";
$typeQuestion = "";
$texteQuestion = "";
$titreSondage = "";

if(($question = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
    $noOrdreQuestion = $question["ORDRE_QUESTION"];
    $typeQuestion = $question['CODE_TYPE_QUESTION'];
    $texteQuestion = $question["TEXTE_QUE"];
    $titreSondage = $question["NO_SONDAGE"];
    $erreur = "";
}else{
    $erreur = "Aucune question associé avec ce sondage";
}

$where = " where CODE_TYPE_QUESTION = " . $typeQuestion;

$stid = oci_parse($conn, 'select * from TP3_TYPE_QUESTION ' . $where . ' fetch first 1 rows only');
oci_execute($stid);
$descTypeQue = "";
if(($typeQuestion = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))!= false){
    $descTypeQue = $typeQuestion["DESC_TYPE_QUE"];
}
    
$stid = oci_parse($conn, 'select * from TP3_CHOIX_REPONSE ' . $where . 'order by ORDRE_REPONSE');
oci_execute($stid);

$ordreReponse = "";
$texteChoix = "";
$typeReponse = "";
$tableauChoixRep = '<table>
          <tr>
            <th>Ordre</th>
            <th>Texte</th>
          </tr>';

while(($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {    
    $typeReponse = $row['ID_CHOIX_REPONSE'];
    if ($typeReponse == 'RB11')
    {
        $ordreReponse = "";
        $texteChoix = "Question à développement";
        $tableauChoixRep = '<table>
            <tr>
                <th>Texte</th>
            </tr>
            <tr>
                <td>'.$texteChoix.'</td>
              </tr>';
    }
    else{
        $ordreReponse = $row['ORDRE_REPONSE'];
        $texteChoix = $row['TEXTE_CHO'];
        $tableauChoixRep .= '<tr>
                <td>'.$ordreReponse.'</td>
                <td>'.$texteChoix.'</td>               
              </tr>';
    }
}
$tableauChoixRep .= '</table>';

    
oci_close($conn);
?>

<html>
<head>
<title>Détails de la question</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
<?php include 'header.php'; ?>
<?php include 'barre_etat.php'; ?>

<?php echo $erreur;?><br>
<form method="post" action="voir_question.php">
<p>
Sondage: <input type="text" name="NO_SONDAGE" value="<?php echo $titreSondage?>"><br>
Question<br>
Ordre: <input type="text" name="ORDRE_QUESTION" value="<?php echo $noOrdreQuestion?>"><br>
Type: <input type="text" name="DESC_TYPE_QUE" value="<?php echo $descTypeQue?>"><br>
Texte: <input type="text" name="TEXTE_QUE" value="<?php echo $texteQuestion?>" size="100"><br>
Réponse
<?php echo $tableauChoixRep;?>
    



<input type="button" onclick="window.location.href='liste_sondage.php';" value="Retour aux sondages"/>
<a href="liste_sondage.php">Retour aux sondages</a>
</p>
</form>

<?php include 'footer.php'; ?>
</body>

</html>
