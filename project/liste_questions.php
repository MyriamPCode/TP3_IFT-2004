<?php
// liste_questions.php

// Vérifier si l'utilisateur est connecté en tant qu'employé
session_start();
if (!estConnecte() || $_SESSION['utilisateur']['TYPE_UTI'] !== 'Employé') {
    // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté en tant qu'employé
    header('Location: index.php');
    exit();
}

// Inclure la barre d'état et le header
include 'barre_etat.php';
include 'header.php';
include 'init.php';

// Traiter les réponses au formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeSondage = isset($_SESSION['code_sondage']) ? $_SESSION['code_sondage'] : null;
    
    if (!$codeSondage) {
        header('Location: principal.php');
        exit();
    }
    
    $bdd = session_start();
    
    foreach ($questions as $question) {
        $idQuestion = $question['ID_QUESTION'];
        $reponseFieldName = 'reponse_' . $idQuestion;
        
        $reponse = isset($_POST[$reponseFieldName]) ? $_POST[$reponseFieldName] : null;
        
        $sql = "INSERT INTO TP3_REPONSE_UTILISATEUR (NO_UTILISATEUR, ID_CHOIX_REPONSE, TEXTE_REP)
                VALUES (:noUtilisateur, :idChoixReponse, :texteRep)
                ON DUPLICATE KEY UPDATE TEXTE_REP = :texteRep";
        
        $requete = performDatabaseQuery($sql, [
            ':noUtilisateur' => $_SESSION['utilisateur']['NO_UTILISATEUR'],
            ':idChoixReponse' => $reponse,
            ':texteRep' => isset($_POST['reponse_texte_' . $idQuestion]) ? $_POST['reponse_texte_' . $idQuestion] : null,
        ]);
    }
    
    // Fermez la connexion à la base de données
    $bdd = null;
    
    // Rediriger vers la page principale
    header('Location: principal.php');
    exit();
}

// Sélectionner les questions depuis la base de données
$selectQuestion = "SELECT * FROM TP3_QUESTION WHERE ID_QUESTION = :idQuestion";
$queryResult = performDatabaseQuery($selectQuestion, [':idQuestion' => $question['ID_QUESTION']]);
$questions = $queryResult->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Questions</title>
</head>
<body>

<!-- Affichage de l'entête -->
<?php include 'header.php'; ?>

<!-- Affichage de la barre d'état -->
<div id="etat-connexion">
    <?php afficherEtatConnexion(); ?>
</div>

<h2>Liste des Questions</h2>

<form method="post" action="liste_questions.php">
    <!-- Afficher les questions du sondage -->
    <?php foreach ($questions as $question) : ?>
        <div>
            <p><?php echo $question['ORDRE_QUESTION']; ?>. <?php echo $question['TEXTE_QUE']; ?></p>
            <?php if ($question['CODE_TYPE_QUESTION'] === 'BD18') : ?>
                <!-- Afficher les options en fonction du type de question -->
                <?php if ($question['TYPE_QUE'] === 'Vrai/Faux') : ?>
                    <label><input type="radio" name="reponse_<?php echo $question['ID_QUESTION']; ?>" value="Vrai"> Vrai</label>
                    <label><input type="radio" name="reponse_<?php echo $question['ID_QUESTION']; ?>" value="Faux"> Faux</label>
                <?php elseif ($question['CODE_TYPE_QUESTION'] === 'MC04') : ?>
                    <?php foreach ($question['options'] as $option) : ?>
                        <label><input type="radio" name="reponse_<?php echo $question['ID_QUESTION']; ?>" value="<?php echo $option['ID_CHOIX_REPONSE']; ?>">
                            <?php echo $option['TEXTE_CHO']; ?>
                        </label>
                    <?php endforeach; ?>
                <?php elseif ($question['CODE_TYPE_QUESTION'] === 'RB11') : ?>
                    <textarea name="reponse_<?php echo $question['ID_QUESTION']; ?>"></textarea>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <!-- Boutons -->
    <button type="submit" name="action" value="enregistrer">Enregistrer</button>
    <button type="submit" name="action" value="annuler">Annuler</button>
</form>

<?php include 'footer.php'; ?>
</body>
</html>