<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>

<!-- Votre contenu de page d'accueil ici -->

<div id="menu">
    <ul>
        <!-- Autres éléments de menu ici si nécessaire -->

        <!-- Lien de connexion -->
        <li><a href="index.php">Connexion</a></li>
    </ul>
</div>
<?php include 'init.php'; ?>

<?php
// bare-etat.php

// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION['utilisateur']);
}

// Fonction pour afficher le lien de connexion ou l'état connecté
function afficherEtatConnexion() {
    // Si l'utilisateur est connecté
    if (estConnecte()) {
        $prenomNom = $_SESSION['utilisateur']['PRENOM_UTI'] . ' ' . $_SESSION['utilisateur']['NOM_UTI'];
        $typeUtilisateur = $_SESSION['utilisateur']['TYPE_UTI'];

        echo "Bienvenue $prenomNom ($typeUtilisateur) - <a href='index.php'>Déconnecter</a>";
    } else {
        // Si l'utilisateur n'est pas connecté
        echo "<a href='index.php'>Connexion</a>";
    }
}

// Traitement de la déconnexion
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    // Détruire la session
    session_destroy();
    
    // Rediriger vers la page d'accueil
    header('Location: index.php');
    exit();
}
?>

</body>
</html>