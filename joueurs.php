<!-- joueurs.php, Appartient au controleur -->
<?php
    session_start();
    require("connect.inc.php");
    require("tbs_class.php");
    require("controleur.voir_joueurs.php");
    require("joueurs.class.php");
    
    $tbs= new clsTinyButStrong;
    try{
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $login, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cible= $_SERVER["PHP_SELF"];   
        $q_joueur = new RQ1($pdo, $tbs,"SELECT * FROM joueurs", "joueurs.tab.tpl.html"); 
        $acc_joueurs = new AccesJoueurs($pdo,$q_joueur);
        if(!empty($_GET["action"])){        //Les valeurs onshow sont passées directement sur cette page
            switch ($_GET["action"]){
                case "form_ajout" : 
                    $id=$_GET["id_joueur"];
                break;
                case "modifier_com" :
                    $id=$_GET["id_joueur"];
                    $texte=$_GET["texte"];
                    $note=$_GET["note"];
                break;
                case "voir_com" : 
                    $infosJoueur=$acc_joueurs->getInfosJoueur($_GET["id_joueur"]);
                    $id=$infosJoueur["Id_joueur"];
                    $nomJoueur=$infosJoueur["NomJoueur"]." ".$infosJoueur["PrenomJoueur"];
                    if(!empty($_SESSION)){
                        //Cette requete permet d'adapter, sur la template "joueurs_voir_com.tpl.html le lien hypertexte en fonction de si l'utilisateur est connecté ou s'il a déjà (ou non) commenté le joueur concerné
                        $resultat=$acc_joueurs->getCommentairePoste($_GET["id_joueur"],$_SESSION['Id']);
                        if($resultat==0){
                            $message="Commenter";
                            $lien="joueurs.php?action=form_ajout&id_joueur=".$id;
                        }else{
                            $message="";
                            $lien="";
                        }
                    }else{                  //Si il n'est pas connecté, le lien renverra vers la page de connexion
                        $message="Se connecter pour commenter";
                        $lien="inscription.php";
                    }
                    
                break;
            }
        }
        $appli= new Appli ($tbs);
        $appli->moteur($acc_joueurs);
    }catch(PDOException $e){
        $appli->moteur($acc_joueurs);
    }
    //Cela aurait été préférable de mettre une ligne de code comme "require("pieddepage.html");" or cela ne marche pas
    //On suppose que cela soit causé par la présence de LoadTemplate plus tôt dans le script, avec l'utilisation de l'objet $appli 
    //Pour régler ce problème, on a mis dans chaque gabarit le code initialement prévu dans "pieddepage.html", on sait que cette solution n'est pas optimale
?>
 
 