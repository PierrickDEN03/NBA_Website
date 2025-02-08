<!-- login.class.php, Appartient au modèle -->
<?php
    class AccesUsers{
        private $pdo;
    
        function __construct($param_pdo){
            $this->pdo=$param_pdo;
        }    
    
        public function connexion($pseudo,$mdp){
            $res=$this->pdo->prepare("SELECT * FROM utilisateurs WHERE Pseudo =:pseudo AND Mdp=:mdp");
            $res->bindParam(":pseudo",$pseudo);
            $res->bindParam(":mdp",$mdp);
            $res->execute();
            $data=$res->fetch();
            if(empty($data)){   //Infos incorrectes dans ce cas
                return -1;
            }else{
                return $data["Id_user"];       //Profil trouvé
            } 
        }

        public function recupererProfil($id){                           //récupère les infos d'un utilisateur en sachant son id
            $res=$this->pdo->prepare("SELECT * FROM utilisateurs WHERE Id_user = ?");
            $res->execute([$id]);
            $data=$res->fetch();
            return $data; 
        }

        public function CreerProfil($pseudo,$mdp,$files){
            $res=$this->pdo->prepare("INSERT INTO utilisateurs (Pseudo,Mdp,Photo) VALUES (:pseudo,:mdp,:photo)");
            $res->bindParam(":pseudo",$pseudo);
            $res->bindParam(":mdp",$mdp);
            $res->bindParam(":photo",$files['name']);
            $chemin_destination = 'photos_profil/';
            move_uploaded_file($files['tmp_name'], $chemin_destination.$files['name']);
            $res->execute(); 
        }  

        public function ModifierProfil($id,$pseudo,$mdp,$photo){
            $res=$this->pdo->prepare("UPDATE utilisateurs SET Pseudo=:pseudo, Mdp=:mdp, Photo=:photo WHERE Id_user=:id");
            $res->bindParam(":pseudo",$pseudo);
            $res->bindParam(":mdp",$mdp);
            $res->bindParam(":photo",$photo["name"]);
            $res->bindParam(":id",$id);
            $res->execute();
            $chemin_destination = 'photos_profil/';
            move_uploaded_file($photo['tmp_name'], $chemin_destination.$photo['name']);
            $res->execute();
        }

        public function verification($pseudo){              //verifie si les informations ne sont pas déjà repris sur un autre profil
            $res=$this->pdo->prepare("SELECT * FROM utilisateurs WHERE Pseudo=:pseudo");
            $res->bindParam(":pseudo",$pseudo);
            $res->execute();
            $data=$res->fetch();
            if(empty($data)){    //Le pseudo n'est pas utilisé     
                return 1;
            }else{
                return 0;       //Un utilisateur possède déjà ce pseudo
            }
        }

    }
    
    
?>