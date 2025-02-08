<!-- controleur.insc.php, Appartient au controleur -->
<?php
    class Appli{
        private $tbs;

        function __construct ($param_tbs){
            $this->tbs=$param_tbs;
        }
        private function retourInscription(){   //renvoie sur la page d'inscription/connexion
            header("Location:inscription.php");
        }

        private function formulaireInscription(){
            require("entete.html");
            $this->tbs->LoadTemplate("insc.form.tpl.html");
            $this->tbs->Show();
        }

        private function formulaireConnexion(){
            require("entete.html");
            $this->tbs->LoadTemplate("connexion.form.tpl.html");
            $this->tbs->Show();
        }

        private function EchecCreation(){        //renvoie au formulaire d'inscription avec un message d'erreur
            require("entete.html");
            $this->tbs->LoadTemplate("inscNotOk.form.tpl.html");
            $this->tbs->Show();
        } 

        private function EchecModif(){          //renvoie de nouveau au formulaire de modification de profil
            header("Location:inscription.php?action=modif");
        } 
        
        private function EchecConnexion(){       //renvoie au formulaire de connexion avec un message d'erreur
            require("entete.html");
            $this->tbs->LoadTemplate("connexionNotOk.form.tpl.html");
            $this->tbs->Show();
        }

        public function Accueil(){
            require("entete.html");
            $this->tbs->LoadTemplate("index.html");
            $this->tbs->Show();
        }

        public function dejaConnecte(){
            require("entete.html");
            $this->tbs->LoadTemplate("deja_connecte.html");
            $this->tbs->Show();
        }

        public function moteur($acc_users){
            if (isset($_GET["action"])){
                $action=$_GET["action"];
            }else{
                if (isset($_POST["action"])){
                    $action=$_POST["action"];
                }else{
                    $action="";
                }
            }  
            switch ($action){                   //En cas d'exception, l'utilisateur est renvoyé sur la page d'inscription
                case "inscription" :
                    $this->formulaireInscription();
                break;
                case "connexion" :
                    $this->formulaireConnexion();
                break;
                case "form_connect" : 
                    if(!empty($_SESSION)){
                        $this->dejaConnecte();
                    }else{
                        try{
                            $res=$acc_users->connexion($_POST["pseudo"],$_POST["password"]);        //permet de récupérer l'id de la personne connectée
                            if($res==-1){
                                $this->EchecConnexion();
                            }else{
                                $_SESSION['Id']=$res;       
                                $data=$acc_users->recupererProfil($_SESSION['Id']);                 //on récupère le reste des informations
                                $_SESSION['Pseudo']=$data["Pseudo"];  
                                $this->Accueil();
                            }
                        }catch(exception $e){
                            $this->retourInscription();
                        }
                    }   
                break; 
                case "form_insc" :                  //Créer le profil + se connecte avec les infos saisies lors de la création du compte
                    if(!empty($_SESSION)){
                        $this->dejaConnecte();
                    }else{
                        try{
                            if(!empty($_POST["pseudo"]) && (!empty($_POST["password"])) &&(!empty($_POST["confirmation"])) && (isset($_FILES['photo']['name']) && ($_FILES['photo']['error'] == UPLOAD_ERR_OK))){
                                if($_POST["password"]==$_POST["confirmation"]){
                                    $res=$acc_users->verification($_POST["pseudo"]);
                                    if($res==1){            //Le pseudo renseignée est nouveau
                                        $acc_users->CreerProfil($_POST["pseudo"],$_POST["password"],$_FILES['photo']);
                                        $_SESSION['Pseudo']=$_POST["pseudo"];   
                                        $_SESSION['Id']=$acc_users->connexion($_POST["pseudo"],$_POST["password"]);             //On récupère son id avec la même fonction que pour la connexion
                                        $this->Accueil();
                                    }else{          //Quelqu'un possède déjà le même pseudo
                                        $this->EchecCreation();
                                    }  
                                }else{
                                    $this->EchecCreation();                 //Le mot de passe renseigné n'est pas identique à la confirmation
                                }
                                
                            }else{
                                $this->EchecCreation();
                            }
                        }catch(exception $e){
                            $this->retourInscription();
                        } 
                    }
                break;
                case "deconnexion" :
                    try{
                        if(!empty($_SESSION)){
                            $_SESSION=array();
                            session_destroy();
                            $this->Accueil();
                        } 
                    }catch(exception $e){
                        $this->retourInscription();
                    } 
                case "modif" :              //affiche le formulaire pour modifier le profil
                    if(!empty($_SESSION)){
                        require("entete.html");
                        $this->tbs->LoadTemplate("modif.form.tpl.html");
                        $this->tbs->Show();   
                    } 
                case "form_modif" :         //modifie le profil
                    try{
                        if(empty($_SESSION)){
                            $this->retourInscription();
                        }else{
                            if(!empty($_SESSION) && (!empty($_POST["pseudo"]) && (!empty($_POST["password"])) && (isset($_FILES['photo']['name']) && ($_FILES['photo']['error'] == UPLOAD_ERR_OK)))){
                                $acc_users->ModifierProfil($_SESSION['Id'],$_POST["pseudo"],$_POST["password"],$_FILES["photo"]);
                                $_SESSION['Pseudo']=$_POST["pseudo"];     //on modifie les infos de la session
                                $this->Accueil();
                            }else{
                                $this->EchecModif(); 
                            }
                        }
                    }catch(exception $e){
                        $this->retourInscription();
                    } 
                break;  
            default:
                if(!empty($_SESSION)){
                    $this->dejaConnecte();
                }else{
                    require("entete.html");
                    $this->tbs->LoadTemplate("insc.tpl.html");
                    $this->tbs->Show();
                }
            }
        }
    }
?>