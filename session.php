<!-- session.php, Appartient à la vue/controleur -->
<?php
    if(!empty($_SESSION)){
        echo 'Vous êtes connecté(e) en tant que '.$_SESSION['Pseudo'].'. Cliquez sur les liens suivants pour vous déconnecter ou modifier votre profil : <a href="inscription.php?action=modif">Modifier mon profil</a> - <a href="inscription.php?action=deconnexion">Me déconnecter</a>';
    }else{
        echo 'Cliquez sur le lien suivant pour accéder à la page d\'inscription/connexion : <a href="inscription.php">Se rendre sur la page</a>';
    }
?>