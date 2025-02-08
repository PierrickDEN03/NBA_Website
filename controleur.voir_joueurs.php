<!-- controleur.voir_joueurs.php, Appartient au controleur -->
<?php
    class Appli{
        private $tbs;

        function __construct ($param_tbs){
            $this->tbs=$param_tbs;
        }

        private function formulaireCommentaire(){
            require("entete.html");
            require("session.php");
            $this->tbs->LoadTemplate("joueurs_com.form.tpl.html");
            $this->tbs->Show();
        }

        private function modifierCommentaire(){
            require("entete.html");
            require("session.php");
            $this->tbs->LoadTemplate("modif_com.form.tpl.html");
            $this->tbs->Show();
        }

        private function Connexion(){           //redirige sur la page de connexion
            header("Location:inscription.php?action=connexion");
        }

        public function moteur($acc_joueurs){       
            if (isset($_GET["action"])){
                $action=$_GET["action"];
            }else{
                $action="";        
            }
            switch ($action){
                case "form_ajout" : 
                    if(empty($_SESSION)){       //REDIRIGE SUR LA CONNEXION / INSCRIPTION
                        $this->Connexion();
                    }else{
                        $this->formulaireCommentaire();
                    }
                break;
                case "form_modif" : 
                    if(!empty($_SESSION) && (!empty($_GET["id_joueur"]) && (!empty($_GET["commentaire"])) && (!empty($_GET["note"])))){
                        $acc_joueurs->modifierCommentaire($_GET["id_joueur"],$_SESSION['Id'],$_GET["commentaire"],$_GET["note"]);
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    }else{
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    }
                case "voir_com" :               //Affiche les commentaires en fonction du joueur sélectionné
                    if(!empty($_GET) && (!empty($_GET["id_joueur"]))){
                        $acc_joueurs->afficherCommentaire($_GET["id_joueur"]);
                    }else{                      //Si l'id du joueur n'est pas trouvé -> renvoie au tableau
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    } 
                break;
                case "commenter" :               //rajoute un commentaire
                    if(!empty($_GET["note"]) && (!empty($_GET["commentaire"]))){
                        $acc_joueurs->ajouterCommentaire($_GET['id_joueur'],$_SESSION['Id'],$_GET["commentaire"],$_GET["note"]);
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    }else{
                        $this->formulaireCommentaire();
                    }
                break;
                case "modifier_com":            //Affiche le formulaire pour modifier un commentaire
                    $this->modifierCommentaire();
                break;
                case "suppr_com" :
                    if(!empty($_SESSION) && (!empty($_GET["id_joueur"]))){
                        $acc_joueurs->supprimerCommentaire($_GET["id_joueur"],$_SESSION['Id']);
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    }else{
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    } 
                break;
                case "like" : 
                    if(!empty($_SESSION)){
                        $acc_joueurs->ajouterLike($_GET['id_joueur'],$_SESSION['Id']);
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    }
                break;
                case "delike" : 
                    if(!empty($_SESSION)){
                        $acc_joueurs->supprimerLike($_GET['id_joueur'],$_SESSION['Id']);
                        $acc_joueurs->liste($acc_joueurs->getQjoueur()->getReq());
                    }
                break;
                case "connexion" : 
                    $this->Connexion();
            default:                            //On reste sur la page avec le tableau des joueurs                  
                if(isset($_POST["classer"])){   //On classe en fonction du résultat du formulaire (celui à droite de la liste des joueurs)
                    $classer=$_POST["classer"];
                    switch ($classer){
                        case "nom" : 
                            $req="SELECT * FROM joueurs ORDER BY NomJoueur,PrenomJoueur ASC"; 
                        break;
                        case "note" : 
                            $req="SELECT joueurs.Id_joueur,NomJoueur,PrenomJoueur,NbBague,AnneeDraft,NbAnnee, Avg(Note) AS MoyNote FROM joueurs,commentaire WHERE joueurs.Id_joueur=commentaire.Id_joueur GROUP BY joueurs.Id_joueur ORDER BY MoyNote DESC";
                        break;
                        case "nblike" : 
                            $req="SELECT joueurs.Id_joueur,NomJoueur,PrenomJoueur,NbBague,AnneeDraft,NbAnnee, Count(likes.Id_joueur) AS NbLike FROM joueurs,likes WHERE joueurs.Id_joueur=likes.Id_joueur GROUP BY joueurs.Id_joueur ORDER BY NbLike DESC";
                        break;
                        case "nbbague" :
                            $req="SELECT * FROM joueurs ORDER BY NbBague DESC";        
                        break;
                        case "nbannee" : 
                            $req="SELECT * FROM joueurs ORDER BY NbAnnee DESC"; 
                        break;
                        case "draft" :
                            $req="SELECT * FROM joueurs ORDER BY AnneeDraft DESC";
                        break;
                        case " " : 
                            $req="SELECT * FROM joueurs";
                        break;
                    }
                }else{
                    $req=$acc_joueurs->getQjoueur()->getReq();
                }
                $acc_joueurs->liste($req);
            }
        }
    }
?>