
<?php include 'init.php' ?>
<?php

// Démarrez la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION["estConnecté"]) && $_SESSION["estConnecté"] == 1;
}



// Fonction pour afficher l'état de connexion
function afficherEtatConnexion() {
    if (estConnecte()) {
        // Utilisateur connecté
        $prenomNom = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
        $typeUtilisateur = $_SESSION['typeUser'];
        echo "Bienvenue $prenomNom ($typeUtilisateur) - <a href='index.php'>Déconnecter</a>";
    } else {
        // Utilisateur non connecté
        echo "<a href='index.php'>Connexion</a>";
    }
}
?>
<?php afficherEtatConnexion(); ?>

</body>
</html>