<?php
// liste_questions.php

// Inclure la barre d'état et le header
include('barre_etat.php');
include('header.php');
include ('init.php');

// Vérifier si l'utilisateur est connecté en tant qu'employé
if (!estConnecte() || $_SESSION['utilisateur']['TYPE_UTI'] !== 'Employé') {
    // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté en tant qu'employé
    header('Location: index.php');
    exit();
}

// Traitement du formulaire de réponses
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Vous devez récupérer le code du sondage à partir de la requête ou d'une variable de session
    $codeSondage = isset($_SESSION['code_sondage']) ? $_SESSION['code_sondage'] : null;
    
    if (!$codeSondage) {
    // Rediriger vers la page principale
    header('Location: principal.php');
    exit();
    }
    
    $bdd = session_start();
    
    // Boucle à travers les questions du formulaire
    foreach ($questions as $question) {
        $idQuestion = $question['ID_QUESTION'];
        $reponseFieldName = 'reponse_' . $idQuestion;
        
        // Récupérer la réponse du formulaire
        $reponse = isset($_POST[$reponseFieldName]) ? $_POST[$reponseFieldName] : null;
        
        // Insérez ou mettez à jour la réponse dans la base de données
        $sql = "INSERT INTO TP3_REPONSE_UTILISATEUR (NO_UTILISATEUR, ID_CHOIX_REPONSE, TEXTE_REP)
                VALUES (:noUtilisateur, :idChoixReponse, :texteRep)
                ON DUPLICATE KEY UPDATE TEXTE_REP = :texteRep";
        
        $requete = $bdd->prepare($sql);
        $requete->bindParam(':noUtilisateur', $_SESSION['utilisateur']['NO_UTILISATEUR']);
        $requete->bindParam(':idChoixReponse', $reponse);
        $requete->bindParam(':texteRep', $_POST['reponse_texte_' . $idQuestion]);  // Si c'est une question à développement
        
        // Exécutez la requête
        $requete->execute();
    }
    
    $questions = $requete->fetchAll(PDO::FETCH_ASSOC);
    
    // Fermez la connexion à la base de données
    $bdd = null;
    return $questions;
    
    // Rediriger vers la page principale
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

<!-- Affichage de l'entête -->
<?php include header.php ?>

<!-- Affichage de la barre d'état -->
<div id="etat-connexion">
    <?php afficherEtatConnexion(); ?>
</div>

<!-- Contenu de la page -->
<h2>Liste des Questions</h2>

<form method="post" action="liste_questions.php">
    <!-- Afficher les questions du sondage -->
    <?php foreach ($questions as $question) : ?>
        <div>
            <p><?php echo $question['ORDRE_QUESTION']; ?>. <?php echo $question['TEXTE_QUE']; ?></p>

            <!-- Afficher les options en fonction du type de question -->
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