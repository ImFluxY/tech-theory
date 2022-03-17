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
        
        //Variables de vérification
        $modifPseudo = 0;
        $modifNum = 0;
		if(isset($_POST['formeditionprofil'])) {
            $newpseudo = htmlspecialchars($_POST['newpseudo']);
            $newtel = htmlspecialchars($_POST['newtel']);

            $pseudolength = mb_strlen($newpseudo);
			if($pseudolength <= 16) {
			    if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudoUser']) {
				    
				    $insertpseudo = $bdd->prepare("UPDATE USER SET pseudoUser = ? WHERE idUser = ?");
				    $insertpseudo->execute(array($newpseudo, $_SESSION['idUser']));
				    $_SESSION['pseudoUser'] = $newpseudo ;	
                    $modifPseudo = 1;
			    }
			    if(isset($_POST['newtel']) AND !empty($_POST['newtel']) AND $_POST['newtel'] != $user['numTelUser']) {
				    $inserttel = $bdd->prepare("UPDATE USER SET numTelUser = ? WHERE idUser = ?");
				    $inserttel->execute(array($newtel, $_SESSION['idUser']));
				    $_SESSION['numTelUser'] = $newtel ;
                    $modifNum = 1;
			    }

                if($modifPseudo == 0 AND $modifNum == 0){
                    $erreur = "Aucune modification !";
                }

                if(isset($erreur) == false) {
                    header('Location: profil.php');
                }

                // Etape 4 : ferme la connexion au serveur de base de données
		        $pdo = null ;
            }else{
                $erreur = " Votre pseudo ne doit pas dépasser 16 caractères";
            }
		}
?><!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page changer informations du projet tutoré, TechTheory">
    <title>Changer informations</title>
    <link rel="stylesheet" href="css/page-profil.css" type="text/css">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
</head>

<body>
<?php include("menu.php"); ?>
    <main>
        
        <h1>Mon compte</h1>
        <div class="compte">
            <div class="compte2">
                <div class="nav-compte">
                    <div class="avatar-pers">
                        <img src="<?php echo($_SESSION['avatarUser']) ?>" alt="avatar anonyme" class="profil-avatar">
                        <p class="modif-avatar"><a class="a-modif-avatar btn-lien-page btn-lien-pause" href="modifier-avatar.php">Modifier mon avatar</a></p>
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
                            <label for="mail">E-mail</label>
                            <input disabled="disabled" type="email" name="mail" id="mail" spellcheck="false" class="gris texte-input-user" placeholder="<?php echo($_SESSION['identifiantUser']) ?>" class="gris" >
                        </div>
                        <div class="champs grande-zone">
                            <label for="pseudo">Nom d'utilisateur</label>
                            <input type="text" name="newpseudo" id="pseudo" spellcheck="false" class="texte-input-user" placeholder="<?php echo($_SESSION['pseudoUser']) ?>" >
                        </div>
                        <div class="naissance-tel">
                            <div class="champs">
                                <label for="dateNaissanceUser">Date de naissance</label>
                                <input disabled="disabled" type="text" name="newdateNaissance" id="dateNaissance" class="gris texte-input-user" spellcheck="false" placeholder="<?php echo($_SESSION['dateNaissanceUser']) ?>" class="gris">
                            </div>
                            <div class="champs">
                                <label for="numTelUser">Num. de téléphone</label>
                                <input type="tel" name="newtel" id="numTel" class="texte-input-user" spellcheck="false" placeholder="<?php echo($_SESSION['numTelUser']) ?>"> 
                            </div>

                        </div>
                        <div class="champs grande-zone">
                                <label for="mdp1">Mot de passe <a href="page-profil-modifier-mdp.php" class="mdp-modif btn-lien-page btn-lien-pause">Modifier</a>
                                </label>
                            <input disabled="disabled" type="password" name="mdp1" id="mdp1" spellcheck="false" placeholder="***********" class="gris texte-input-user">
                        </div>
                        
                        <div class="btn-modif-info">
                            <input type="submit" name="formeditionprofil" id="valider" value="Sauvegarder mes informations" class="btn-envoyer btn-lien-page btn-lien-pause">
                        </div>
                    </form>
            <?php
		        if(isset($erreur)) {
            ?>
				<div class="error"><p><?php echo($erreur)  ?></p></div>
            <?php
		        } 
            ?>
                </div>
            </div>
        </div>
    </main>
    
</body>
</html>
<?php
	}
	else {
		header("Location: connexion.php");
	}
?>
				