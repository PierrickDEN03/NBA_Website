<!-- joueurs.class.php, Appartient au modèle -->
<?php
class Requete {
    protected $pdo; // Identifiant de connexion
     protected $tbs; // Moteur de template 
     protected $req; // Requête SQL 
     protected $gab; // Nom de gabarit 

     public function getReq(){
         return $this->req;
     }
     public function getTbs(){
         return $this->tbs;
     }
     public function getGab(){
        return $this->gab;
    }

     function __construct($param_pdo,$param_tbs, $param_req, $param_gab) {
         $this->pdo = $param_pdo;
         $this->tbs = $param_tbs;
         $this->req = $param_req;
         $this->gab = $param_gab;
     }

     public function executer() {
         $res = $this->pdo->prepare($this->req);
         $res->execute();
         $this->data = $res->fetchAll();
     }
}

 class RQ1 extends Requete {


     public function afficher() {
     // Préparation des données
         $i = 0;
         $listeNom = array();
         $listeJoueurs = array(); 
         $listeBague= array();
         $listeAnneeDraft = array();
         $listeNbAnnee =array();
         foreach($this->data as $ligne) {
             $listeNom[$i++] = $ligne["NomJoueur"];
             $listePrenom[$i++] = $ligne["PrenomJoueur"];
             $listeBague[$i++] = $ligne["NbAnnee"];
             $listeAnneeDraft[$i++] = $ligne["AnneeDraft"];
             $listeNbAnnee[$i++] = $ligne["NbAnnee"];

         } 
         // Affichage du gabarit
         $this->tbs->LoadTemplate($this->gab);
         $this->tbs->MergeBlock("nom", $listeNom);
         $this->tbs->MergeBlock("prenom", $listePrenom);
         $this->tbs->MergeBlock("bague", $listeBague);
         $this->tbs->MergeBlock("draft", $listeAnneeDraft);
         $this->tbs->MergeBlock("nb_annee", $listeNbAnnee);
         $this->tbs->Show(); 
     }
 }
 
    class AccesJoueurs{
        private $pdo;
        private $qjoueur;

        public function getQjoueur(){
            return $this->qjoueur;
        }
    
        function __construct($param_pdo, $param_tbs){
            $this->pdo=$param_pdo;
            $this->qjoueur=$param_tbs;
        }    
    
        public function liste($req){        //exécute et affiche en fonction du critère de classement sélectionné, rien par défaut 
            $res = $this->pdo->prepare($req);
            $res->execute();
            $data = $res->fetchAll();
            //Partie affichage
            $i = 0;
            $listeNom = array();
            $listePrenom = array(); 
            $listeBague= array();
            $listeAnneeDraft = array();
            $listeNbAnnee = array();
            $listeId = array();
            $listeNbLike = array();
            $listeLikeUser = array();
            $listeAction = array();
            $listeNote = array();
            foreach($data as $ligne) {
                $listeNom[$i++] = $ligne["NomJoueur"];
                $listePrenom[$i++] = $ligne["PrenomJoueur"];
                $listeBague[$i++] = $ligne["NbBague"];
                $listeAnneeDraft[$i++] = $ligne["AnneeDraft"];
                $listeNbAnnee[$i++] = $ligne["NbAnnee"];
                $listeId[$i++] = $ligne["Id_joueur"];

                 //On compte le nombre de like pour chaque joueur
                $res2 = $this->pdo->prepare("SELECT COUNT(Id_joueur) AS NbLike FROM likes WHERE Id_joueur=:id_joueur GROUP BY Id_joueur");      
                $res2->bindParam(":id_joueur",$ligne["Id_joueur"]);
                $res2->execute();
                $data2= $res2->fetch();
                if(empty($data2)){
                    $nbLike=0;
                }else{
                    $nbLike=$data2["NbLike"];
                }
                $listeNbLike[$i++] = $nbLike;

                //Pour chaque joueur, on regarde si l'utilisateur à liké ou non le joueur
                if(!empty($_SESSION)){
                    $res3 = $this->pdo->prepare("SELECT * FROM likes WHERE Id_joueur=:id_joueur AND Id_user=:id_user");      
                    $res3->bindParam(":id_joueur",$ligne["Id_joueur"]);
                    $res3->bindParam(":id_user",$_SESSION['Id']);
                    $res3->execute();
                    $data3= $res3->fetch();
                    if(empty($data3)){                          //Affiche un pouce rempli si il a déja liké ou un pouce vide sinon
                        $LikeTrue="images/pouce_vide.png";             //L'action changera en fonction : l'utilisateur sera redirigé sur la page de connexion s'il n'est pas connecté
                        $action="like";
                    }else{
                        $LikeTrue="images/pouce_plein.png";
                        $action="delike";
                    }
                    $listeLikeUser[$i++] = $LikeTrue;
                    $listeAction[$i++] = $action;
                }else{                                          //Si l'utilisateur n'est pas connecté, on affiche un pouce vide pour tous les joueurs
                    $listeLikeUser[$i++] = "images/pouce_vide.png";
                    $listeAction[$i++] = "connexion";
                }

                //On regarde la note moyenne pour chaque joueur
                $res4 = $this->pdo->prepare("SELECT Avg(Note) AS MoyNote FROM commentaire WHERE Id_joueur=:id_joueur GROUP BY Id_joueur");   
                $res4->bindParam(":id_joueur",$ligne["Id_joueur"]);
                $res4->execute();
                $data4= $res4->fetch();
                if(empty($data4)){
                    $note=0;
                }else{
                    $note=$data4["MoyNote"];
                }
                $listeNote[$i++] = $note;
            } 

            //Dans le cas où on classe selon le nombre de likes ou la note moyenne, il faut faire une deuxième requete pour insérer les données des joueurs sans like ou note
            //On appelle les fonctions listeNonLike() et listeNonNote();
            if($req=="SELECT joueurs.Id_joueur,NomJoueur,PrenomJoueur,NbBague,AnneeDraft,NbAnnee, Count(likes.Id_joueur) AS NbLike FROM joueurs,likes WHERE joueurs.Id_joueur=likes.Id_joueur GROUP BY joueurs.Id_joueur ORDER BY NbLike DESC"){
                $this->listeNonLike($listeNom,$listePrenom,$listeBague,$listeAnneeDraft,$listeNbAnnee,$listeId,$listeNbLike,$listeLikeUser,$listeAction,$listeNote,$i);
            }else{
                if($req=="SELECT joueurs.Id_joueur,NomJoueur,PrenomJoueur,NbBague,AnneeDraft,NbAnnee, Avg(Note) AS MoyNote FROM joueurs,commentaire WHERE joueurs.Id_joueur=commentaire.Id_joueur GROUP BY joueurs.Id_joueur ORDER BY MoyNote DESC"){
                    $this->listeNonNote($listeNom,$listePrenom,$listeBague,$listeAnneeDraft,$listeNbAnnee,$listeId,$listeNbLike,$listeLikeUser,$listeAction,$listeNote,$i);
                }else{
                     // Affichage du gabarit
                     //Le gabarit est déjà affiché avec les requetes listeNonLike() et listeNonNote() pour éviter l'affichage de plusieurs gabarits
                    require("entete.html");
                    require("session.php");
                    $this->qjoueur->getTbs()->LoadTemplate($this->qjoueur->getGab());
                    $this->qjoueur->getTbs()->MergeBlock("nom",$listeNom);
                    $this->qjoueur->getTbs()->MergeBlock("prenom",$listePrenom);
                    $this->qjoueur->getTbs()->MergeBlock("bague",$listeBague);
                    $this->qjoueur->getTbs()->MergeBlock("draft",$listeAnneeDraft);
                    $this->qjoueur->getTbs()->MergeBlock("nbAnnee",$listeNbAnnee);
                    $this->qjoueur->getTbs()->MergeBlock("idjoueur",$listeId);
                    $this->qjoueur->getTbs()->MergeBlock("nbLike",$listeNbLike);
                    $this->qjoueur->getTbs()->MergeBlock("photo",$listeLikeUser);
                    $this->qjoueur->getTbs()->MergeBlock("action",$listeAction);
                    $this->qjoueur->getTbs()->MergeBlock("note",$listeNote);
                    $this->qjoueur->getTbs()->Show(); 
                }
            }
        }

        public function listeNonNote($listeNom,$listePrenom,$listeBague,$listeAnneeDraft,$listeNbAnnee,$listeId,$listeNbLike,$listeLikeUser,$listeAction,$listeNote,$i){
            $res = $this->pdo->prepare("SELECT * FROM joueurs");
            $res->execute();
            $data = $res->fetchAll();
            //Partie affichage
            foreach($data as $ligne) {
                //On regarde la note moyenne pour chaque joueur
                //On affiche les informations seulement si le joueur n'a pas de moyenne
                $res2 = $this->pdo->prepare("SELECT Avg(Note) AS MoyNote FROM commentaire WHERE Id_joueur=:id_joueur GROUP BY Id_joueur");      
                $res2->bindParam(":id_joueur",$ligne["Id_joueur"]);
                $res2->execute();
                $data2= $res2->fetch();
                if(empty($data2)){
                    $listeNom[$i++] = $ligne["NomJoueur"];
                    $listePrenom[$i++] = $ligne["PrenomJoueur"];
                    $listeBague[$i++] = $ligne["NbBague"];
                    $listeAnneeDraft[$i++] = $ligne["AnneeDraft"];
                    $listeNbAnnee[$i++] = $ligne["NbAnnee"];
                    $listeId[$i++] = $ligne["Id_joueur"];
                    $listeNote[$i++]=0;                     //On sait que sa moyenne n'est pas renseignée, on met 0 par défaut

                    //On compte le nombre de like pour chaque joueur
                    $res3 = $this->pdo->prepare("SELECT COUNT(Id_joueur) AS NbLike FROM likes WHERE Id_joueur=:id_joueur GROUP BY Id_joueur");      
                    $res3->bindParam(":id_joueur",$ligne["Id_joueur"]);
                    $res3->execute();
                    $data3= $res3->fetch();
                    if(empty($data3)){
                        $nbLike=0;
                    }else{
                        $nbLike=$data3["NbLike"];
                    }
                    $listeNbLike[$i++] = $nbLike;

                    //Pour chaque joueur, on regarde si l'utilisateur à liké ou non le joueur
                    if(!empty($_SESSION)){
                        $res4 = $this->pdo->prepare("SELECT * FROM likes WHERE Id_joueur=:id_joueur AND Id_user=:id_user");      
                        $res4->bindParam(":id_joueur",$ligne["Id_joueur"]);
                        $res4->bindParam(":id_user",$_SESSION['Id']);
                        $res4->execute();
                        $data4= $res4->fetch();
                        if(empty($data4)){                          //Affiche un pouce rempli si il a déja liké ou un pouce vide sinon
                            $LikeTrue="images/pouce_vide.png";             //L'action changera en fonction : l'utilisateur sera redirigé sur la page de connexion s'il n'est pas connecté
                            $action="like";
                        }else{
                            $LikeTrue="images/pouce_plein.png";
                            $action="delike";
                        }
                        $listeLikeUser[$i++] = $LikeTrue;
                        $listeAction[$i++] = $action;
                    }else{                                          //Si l'utilisateur n'est pas connecté, on affiche un pouce vide pour tous les joueurs
                        $listeLikeUser[$i++] = "images/pouce_vide.png";
                        $listeAction[$i++] = "connexion";
                    }
                }  
            }
 
         // Affichage du gabarit
         require("entete.html");
         require("session.php");
         $this->qjoueur->getTbs()->LoadTemplate($this->qjoueur->getGab());
         $this->qjoueur->getTbs()->MergeBlock("nom",$listeNom);
         $this->qjoueur->getTbs()->MergeBlock("prenom",$listePrenom);
         $this->qjoueur->getTbs()->MergeBlock("bague",$listeBague);
         $this->qjoueur->getTbs()->MergeBlock("draft",$listeAnneeDraft);
         $this->qjoueur->getTbs()->MergeBlock("nbAnnee",$listeNbAnnee);
         $this->qjoueur->getTbs()->MergeBlock("idjoueur",$listeId);
         $this->qjoueur->getTbs()->MergeBlock("nbLike",$listeNbLike);
         $this->qjoueur->getTbs()->MergeBlock("photo",$listeLikeUser);
         $this->qjoueur->getTbs()->MergeBlock("action",$listeAction);
         $this->qjoueur->getTbs()->MergeBlock("note",$listeNote);
         $this->qjoueur->getTbs()->Show(); 
        }

        public function listeNonLike($listeNom,$listePrenom,$listeBague,$listeAnneeDraft,$listeNbAnnee,$listeId,$listeNbLike,$listeLikeUser,$listeAction,$listeNote,$i){
            $res = $this->pdo->prepare("SELECT * FROM joueurs");
            $res->execute();
            $data = $res->fetchAll();
            //Partie affichage
            foreach($data as $ligne) {
                //On compte le nombre de like pour chaque joueur
                //On prend les informations de tous les joueurs sans like
                $res2 = $this->pdo->prepare("SELECT COUNT(Id_joueur) AS NbLike FROM likes WHERE Id_joueur=:id_joueur GROUP BY Id_joueur");      
                $res2->bindParam(":id_joueur",$ligne["Id_joueur"]);
                $res2->execute();
                $data2= $res2->fetch();
                if(empty($data2)){
                    $listeNom[$i++] = $ligne["NomJoueur"];
                    $listePrenom[$i++] = $ligne["PrenomJoueur"];
                    $listeBague[$i++] = $ligne["NbBague"];
                    $listeAnneeDraft[$i++] = $ligne["AnneeDraft"];
                    $listeNbAnnee[$i++] = $ligne["NbAnnee"];
                    $listeId[$i++] = $ligne["Id_joueur"];
                    $listeNbLike[$i++] = 0;

                    //Pour chaque joueur, on regarde si l'utilisateur à liké ou non le joueur
                    //Comme on sait que les joueurs de cette liste n'ont pas de like, listeLikeUser et listeAction sont plus facile à déterminer
                    if(!empty($_SESSION)){
                        $listeLikeUser[$i++] = "images/pouce_vide.png";
                        $listeAction[$i++] = "like";
                    }else{              //Si l'utilisateur n'est pas connecté, on affiche un pouce vide pour tous les joueurs
                        $listeLikeUser[$i++] = "images/pouce_vide.png";
                        $listeAction[$i++] = "connexion";
                    }
                    //On regarde la note moyenne pour chaque joueur
                    $res3 = $this->pdo->prepare("SELECT Avg(Note) AS MoyNote FROM commentaire WHERE Id_joueur=:id_joueur GROUP BY Id_joueur");   
                    $res3->bindParam(":id_joueur",$ligne["Id_joueur"]);
                    $res3->execute();
                    $data3= $res3->fetch();
                    if(empty($data3)){
                        $note=0;
                    }else{
                        $note=$data3["MoyNote"];
                    }
                    $listeNote[$i++] = $note;
                }  
            }
 
         // Affichage du gabarit
         require("entete.html");
         require("session.php");
         $this->qjoueur->getTbs()->LoadTemplate($this->qjoueur->getGab());
         $this->qjoueur->getTbs()->MergeBlock("nom",$listeNom);
         $this->qjoueur->getTbs()->MergeBlock("prenom",$listePrenom);
         $this->qjoueur->getTbs()->MergeBlock("bague",$listeBague);
         $this->qjoueur->getTbs()->MergeBlock("draft",$listeAnneeDraft);
         $this->qjoueur->getTbs()->MergeBlock("nbAnnee",$listeNbAnnee);
         $this->qjoueur->getTbs()->MergeBlock("idjoueur",$listeId);
         $this->qjoueur->getTbs()->MergeBlock("nbLike",$listeNbLike);
         $this->qjoueur->getTbs()->MergeBlock("photo",$listeLikeUser);
         $this->qjoueur->getTbs()->MergeBlock("action",$listeAction);
         $this->qjoueur->getTbs()->MergeBlock("note",$listeNote);
         $this->qjoueur->getTbs()->Show(); 
        }

        public function ajouterLike($id_joueur,$id_user){  
            try{
                $res=$this->pdo->prepare("INSERT INTO likes (Id_joueur,Id_user) VALUES (:idjoueur,:iduser)");
                $res->bindParam(":idjoueur",$id_joueur);
                $res->bindParam(":iduser",$id_user);
                $res->execute();
            }catch(exception $e){
                $this->liste($this->qjoueur->getReq());
            }          
        }

        public function supprimerLike($id_joueur,$id_user){
            try{
                $res=$this->pdo->prepare("DELETE FROM likes WHERE Id_joueur=:idjoueur AND Id_user=:iduser");
                $res->bindParam(":idjoueur",$id_joueur);
                $res->bindParam(":iduser",$id_user);
                $res->execute();
            }catch(exception $e){
                $this->liste($this->qjoueur->getReq());
            }
        }


        public function ajouterCommentaire($id_joueur,$id_user,$texte,$note){ 
            try{
                $res=$this->pdo->prepare("INSERT INTO commentaire (Id_joueur,Id_user,Texte,Note) VALUES (:idjoueur,:iduser,:texte,:note)");
                $res->bindParam(":idjoueur",$id_joueur);
                $res->bindParam(":iduser",$id_user);
                $res->bindParam(":texte",$texte);
                $res->bindParam(":note",$note);
                $res->execute();
            }catch(exception $e){
                $this->liste($this->qjoueur->getReq());
            }          
        }

        public function modifierCommentaire($id_joueur,$id_user,$texte,$note){  
            try{
                $res=$this->pdo->prepare("UPDATE commentaire SET Texte=:texte, Note=:note WHERE Id_user=:id_user AND Id_joueur=:id_joueur");
                $res->bindParam(":id_joueur",$id_joueur);
                $res->bindParam(":id_user",$id_user);
                $res->bindParam(":texte",$texte);
                $res->bindParam(":note",$note);
                $res->execute();
            }catch (exception $e){
                $this->liste($this->qjoueur->getReq());
            }              
        }

        public function supprimerCommentaire($id_joueur,$id_user){  
            try{
                $res=$this->pdo->prepare("DELETE FROM commentaire WHERE Id_user=:id_user AND Id_joueur=:id_joueur");
                $res->bindParam(":id_joueur",$id_joueur);
                $res->bindParam(":id_user",$id_user);
                $res->execute();
            }catch (exception $e){
                $this->liste($this->qjoueur->getReq());
            }              
        }

        public function afficherCommentaire($id_joueur){  
            try{
                $res=$this->pdo->prepare("SELECT utilisateurs.Id_user,utilisateurs.Photo,utilisateurs.Pseudo,commentaire.Texte,commentaire.Note FROM commentaire,utilisateurs,joueurs WHERE joueurs.Id_joueur=commentaire.Id_joueur AND utilisateurs.Id_user=commentaire.Id_user AND commentaire.Id_joueur=:id");
                $res->bindParam(":id",$id_joueur);
                $res->execute();
                $data=$res->fetchAll();
                //Partie affichage
                $i=0;
                $listeId= array();
                $listePseudo = array();
                $listePhoto = array(); 
                $listeCommentaire= array();
                $listeNote = array();
                $listeAccesCom = array();
                $listeAction = array();
                foreach($data as $ligne) {
                    $listeId[$i++] = $id_joueur;
                    $listePhoto[$i++] = "photos_profil/".$ligne["Photo"];
                    $listeCommentaire[$i++] = $ligne["Texte"];
                    $listeNote[$i++] = $ligne["Note"];
                    if(!empty($_SESSION) && ($_SESSION['Id']==$ligne['Id_user'])){              //Si l'utilisateur est connecté, affiche le lien pour modifier supprimer
                        $action="modifier_com";
                        $acces = "Modifier - Supprimer"; 
                        $pseudo = $ligne["Pseudo"]." (Vous)";
                    }else{
                        $acces="";
                        $action="";
                        $pseudo=$ligne["Pseudo"];
                    }
                    $listeAccesCom[$i++] = $acces;
                    $listeAction[$i++] = $action;
                    $listePseudo[$i++] = $pseudo;
                }
            
                // Affichage du gabarit
                require("entete.html");
                require("session.php");
                $this->qjoueur->getTbs()->LoadTemplate("joueurs_voir_com.tpl.html");
                $this->qjoueur->getTbs()->MergeBlock("id",$listeId);
                $this->qjoueur->getTbs()->MergeBlock("pseudo",$listePseudo);
                $this->qjoueur->getTbs()->MergeBlock("photo",$listePhoto);
                $this->qjoueur->getTbs()->MergeBlock("commentaire",$listeCommentaire);
                $this->qjoueur->getTbs()->MergeBlock("note",$listeNote);
                $this->qjoueur->getTbs()->MergeBlock("acces",$listeAccesCom);
                $this->qjoueur->getTbs()->MergeBlock("action",$listeAction);
                $this->qjoueur->getTbs()->Show(); 
            }catch (exception $e){
                $this->liste($this->qjoueur->getReq());
            }         
        }

        public function getInfosJoueur($id_joueur){           //Récupère les infos du joueur concerné 
            try{
                $res=$this->pdo->prepare("SELECT * FROM joueurs WHERE Id_joueur=:id_joueur");
                $res->bindParam(":id_joueur",$id_joueur);
                $res->execute();
                $data=$res->fetch();
                return $data;
            }catch (exception $e){
                $this->liste($this->qjoueur->getReq());
            }           
        }

        public function getCommentairePoste($id_joueur,$id_user){   //regarde si l'utilisateur a posté         
            try{
                $res=$this->pdo->prepare("SELECT * FROM commentaire WHERE Id_joueur=:id_joueur AND Id_user=:id_user");
                $res->bindParam(":id_joueur",$id_joueur);
                $res->bindParam(":id_user",$id_user);
                $res->execute();
                $data=$res->fetchAll();
                if(empty($data)){
                    return 0;
                }else{
                    return 1;
                }
                return $nom;
            }catch (exception $e){
                $this->liste($this->qjoueur->getReq());
            }   
        }
    }
?>