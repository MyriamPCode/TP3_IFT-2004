<?php
// liste_questions.php

include('barre-etat.php');
include('header.php');
include('init.php');

// Redirection vers la page d'accueil si l'utilisateur n'est pas connecté en tant que salarié
if (!estConnecte() || $_SESSION['utilisateur']['TYPE_UTI'] !== 'Employé') {
    header('Location: index.php');
    exit();
}

// Traiter les réponses au formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le code de l'enquête à partir de la requête ou d'une variable de session
    $codeSondage = isset($_SESSION['code_sondage']) ? $_SESSION['code_sondage'] : null;
    
    if (!$codeSondage) {
        header('Location: principal.php');
        exit();
    }
    
    // Démarrer la session si la session n'est pas encore démarré
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Parcourez les questions du formulaire
    foreach ($questions as $question) {
        $idQuestion = $question['ID_QUESTION'];
        $reponseFieldName = 'reponse_' . $idQuestion;
        
        // Obtenir la réponse du formulaire
        $reponse = $_POST[$reponseFieldName] ?? null;
        
        // Insérer ou mettre à jour la réponse dans la base de données
        $sql = "INSERT INTO TP3_REPONSE_UTILISATEUR (NO_UTILISATEUR, ID_CHOIX_REPONSE, TEXTE_REP)
                VALUES (:noUtilisateur, :idChoixReponse, :texteRep)
                ON DUPLICATE KEY UPDATE TEXTE_REP = :texteRep";
        
        $requete = performDatabaseQuery($sql, [
            ':noUtilisateur' => $_SESSION['utilisateur']['NO_UTILISATEUR'],
            ':idChoixReponse' => $reponse,
            ':texteRep' => $_POST['reponse_texte_' . $idQuestion] ?? null, // Si c'est une question ouverte
        ]);
    }
    
    // Rediriger a la page principal
    header('Location: principal.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Questions</title>
</head>
<body>

<?php include 'init.php' ?>
<?php include 'header.php'; ?>
<?php include 'barre_etat.php' ?>


<div id="etat-connexion">
    <?php afficherEtatConnexion(); ?>
</div>

<h2>Liste des Questions</h2>

<form method="post" action="liste_questions.php">
    <?php foreach ($questions as $question) : ?>
        <div>
            <p><?php echo $question['ORDRE_QUESTION']; ?>. <?php echo $question['TEXTE_QUE']; ?></p>

            <?php if ($question['TYPE_QUE'] === 'Vrai/Faux') : ?>
                <label><input type="radio" name="reponse_<?php echo $question['ID_QUESTION']; ?>" value="Vrai"> Vrai</label>
                <label><input type="radio" name="reponse_<?php echo $question['ID_QUESTION']; ?>" value="Faux"> Faux</label>
            <?php elseif ($question['TYPE_QUE'] === 'Choix Multiple') : ?>
                <?php foreach ($question['options'] as $option) : ?>
                    <label><input type="radio" name="reponse_<?php echo $question['ID_QUESTION']; ?>" value="<?php echo $option['ID_CHOIX_REPONSE']; ?>">
                        <?php echo $option['TEXTE_CHO']; ?>
                    </label>
                <?php endforeach; ?>
            <?php elseif ($question['TYPE_QUE'] === 'À Développement') : ?>
                <textarea name="reponse_<?php echo $question['ID_QUESTION']; ?>"></textarea>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <!-- Boutons -->
    <button type="submit" name="action" value="enregistrer">Enregistrer</button>
    <button type="submit" name="action" value="annuler">Annuler</button>
</form>

</body>
</html>