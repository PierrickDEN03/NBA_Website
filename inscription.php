<?php
    session_start();
    require("connect.inc.php");
    require("tbs_class.php");
    require("login.class.php");
    require("controleur.insc.php");
    $tbs= new clsTinyButStrong;
    try{
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $login, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cible= $_SERVER["PHP_SELF"];
        $acc_users = new AccesUsers($pdo);
        $appli= new Appli ($tbs);
        if(!empty($_GET["action"]) && ($_GET["action"]=="modif")){      //On fait passer les variables onshow ici
            $data=$acc_users->recupererProfil($_SESSION['Id']);
            $pseudo=$data["Pseudo"];
            //On ne fait pas passer le mot de passe par soucis de sécurité pour les utilisateurs
            $photo=$data["Photo"];
        }
        $appli->moteur($acc_users);
    }catch(PDOException $e){
        $appli->moteur($acc_users);
    }
    //Cela aurait été préférable de mettre une ligne de code comme "require("pieddepage.html");" or cela ne marche pas
    //On suppose que cela soit causé par la présence de LoadTemplate plus tôt dans le script, avec l'utilisation de l'objet $appli 
    //Pour régler ce problème, on a mis dans chaque gabarit le code initialement prévu dans "pieddepage.html", on sait que cette solution n'est pas optimale
?>