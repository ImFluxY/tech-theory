<?php
    // editionprofil.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");

    if(isset($_SESSION['idUser'])) {

     // Etape 1 : connexion au serveur de base de données
		$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
		$bdd->query("SET NAMES utf8");
		$bdd->query("SET CHARACTER SET 'utf8'");
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$requser = $bdd->prepare("SELECT * FROM USER WHERE idUser = ?");
		$requser->execute(array($_SESSION['idUser']));
		$user = $requser->fetch(PDO::FETCH_ASSOC);
		
        if(!empty($_POST['mdpconnect']) AND !empty($_POST['mdp2'])) {
			$mdpconnect = sha1($_POST['mdpconnect']);
            $nouveauMdp = sha1($_POST['mdp2']);
			
			// Etape 2 : envoi de la requête SQL au serveur
			$requser = $bdd->prepare("SELECT idUser FROM USER WHERE mdpUser = ? AND idUSer = ?");
			
			$requser->execute(array($mdpconnect, $_SESSION['idUser']));
			$ligne = $requser->fetch(PDO::FETCH_ASSOC) ;
            
			if($ligne != false) {
                //verif la longueur du mdp
            $mdplenght = mb_strlen($_POST['mdp2']);
			if($mdplenght >= 8){
					//1 majuscule dans le mdp
					if (preg_match("#[A-Z]#", $_POST['mdp2'])){
                        // verif la différence entre l'ancien et le nouveau mdp
                        if($_POST['mdpconnect'] != $_POST['mdp2']){

                            //changer le mdp dans la bdd
				$requser = $bdd->prepare("UPDATE USER  SET mdpUser = ? WHERE idUSer = ?");
			    $requser->execute(array($nouveauMdp, $_SESSION['idUser']));
                $erreur = "Mot de passe actualisé !";
                        }else{
                            $erreur = "Votre nouveau mot de passe doit être différent de l'ancien !";
                        }  
                    }else{
                        $erreur = "Votre mot de passe doit posséder au moins 1 majuscule !";
                    }
                }else{
                    $erreur = "Votre mot de passe doit posséder au moins 8 caractères !";
                }
            }else{
			    $erreur = "Mauvais  mot de passe !";
		    }
            
			// Etape 4 : ferme la connexion au serveur de base de données
			$pdo = null ;
		} else {
			$erreur = "Tous les champs doivent être complétés !";
		}
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Page modification du mot de passe du projet tutoré, TechTheory">
    <title>Modification mot de passe</title>
    <link rel="stylesheet" href="css/page-profil.css" type="text/css">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
</head>
<body>
<?php include("menu.php"); ?>
    <main>
        <h1>Mon compte</h1>
        <div class="compte">
            <div class="compte2">
                <div class="nav-compte">
                <div class="avatar-pers">
                    <img src="<?php echo($_SESSION['avatarUser']); ?>" alt="avatar anonyme" class="profil-avatar">
                    <p class="modif-avatar"><a class="a-modif-avatar" href="modifier-avatar.php">Modifier mon avatar</a></p>
                    </div>
                    <div class="sec-under-img">
                        <div class="onglets-nav">
                            <img src="images/profil/coeur-vide.png" alt="coeur de suivi" class="img-under-avatar">
                            <a href="suivis.php" class="btn-lien-page btn-lien-pause onglets-nav-modif"> Créateurs suivis</a>
                        </div>
                        <div class="onglets-nav">
                            <img src="images/profil/bulle-comment.png" alt="bulle de commentaire" class="img-under-avatar">
                            <a href="commentaires.php" class="btn-lien-page btn-lien-pause onglets-nav-modif">Commentaires laissés</a>
                        </div>
                        <div class="onglets-nav">
                            <img src="images/profil/deconnexion.png" alt="flèche de déconnexion" class="img-under-avatar">
                            <a href="deconnexion.php" class="btn-lien-page btn-lien-stop onglets-nav-modif">Déconnexion</a>
                        </div>
                    </div>  
                </div>
                <div class="form-right-compte">
                    <form action="" method="post" class="form-modifier-compte">
                        <div class="champs grande-zone">
                            <label for="mdpconnect">Ancien mot de passe</label>
                            <input type="password" class="texte-input-user" name="mdpconnect" id="mdpconnect" spellcheck="false" placeholder="***********">
                        </div>
                        <div class="champs grande-zone">
                            <label for="mdp2">Nouveau mot de passe</label>
                            <input type="password" class="texte-input-user" name="mdp2" id="mdp2" spellcheck="false" placeholder="***********">
                        </div>
                        <div class="btn-modif-info">
                            <input type="submit" name="formeditionprofil" id="valider" value="Enregistrer" class="btn-envoyer btn-lien-page btn-lien-pause">
                        </div>
                    </form>
                    <?php
		if(isset($erreur)) {
                    ?>
				<div class="error"><?php echo($erreur) ; ?></div>
<?php
		} 
?>
                </div>
            </div>
        </div>
        <?php include("popup.php"); ?>
    <?php include("barreLecture.php"); ?>
    </main>
    
</body>

</html>
<?php
	}
	else {
		header("Location: connexion.php");
	}
?>

