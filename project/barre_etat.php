<<<<<<< HEAD
=======
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
>>>>>>> c09b5badb435a09152ab4d48ab0d3822808a4d88

<?php include 'init.php' ?>
<?php
// bare-etat.php

// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION["estConnecté"]) && $_SESSION["estConnecté"] == 1;
}

<<<<<<< HEAD


// Fonction pour afficher l'état de connexion
=======
// Fonction pour afficher le lien de connexion ou l'état connecté
>>>>>>> c09b5badb435a09152ab4d48ab0d3822808a4d88
function afficherEtatConnexion() {
    // Si l'utilisateur est connecté
    if (estConnecte()) {
<<<<<<< HEAD
        // Utilisateur connecté
        $prenomNom = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
        $typeUtilisateur = $_SESSION['typeUser'];
=======
        $prenomNom = $_SESSION['utilisateur']['PRENOM_UTI'] . ' ' . $_SESSION['utilisateur']['NOM_UTI'];
        $typeUtilisateur = $_SESSION['utilisateur']['TYPE_UTI'];

>>>>>>> c09b5badb435a09152ab4d48ab0d3822808a4d88
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
<?php afficherEtatConnexion(); ?>

</body>
</html>
