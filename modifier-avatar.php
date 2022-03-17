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
		if(isset($_POST['formeditionprofil'])) {
            header("Location: profil.php");

            if(isset($_POST['avatarChemin']) AND !empty($_POST['avatarChemin']))
            {
                $avatar = $_POST['avatarChemin'];
                $insertavatar = $bdd->prepare("UPDATE USER SET avatarUser = ? WHERE idUser = ?");
				$insertavatar->execute(array($avatar, $_SESSION['idUser']));
				$_SESSION['avatarUser'] = $avatar ;	
            }
			else if(isset($_FILES['newavatar']) AND !empty($_FILES['newavatar']) AND $_FILES['newavatar'] != $user['avatarUser']) 
			{
				$erreurs = array();
				$avatar_nom = $_FILES['newavatar']['name'];
				$avatar_tmp = $_FILES['newavatar']['tmp_name'];
				$avatar = null;
                
				if(empty($erreur) == true)
				{
					$avatar = AVATARDIR.$avatar_nom;
					move_uploaded_file($avatar_tmp, $avatar);
				}
				else{
					print_r($erreur);
				}

				$insertavatar = $bdd->prepare("UPDATE USER SET avatarUser = ? WHERE idUser = ?");
				$insertavatar->execute(array($avatar, $_SESSION['idUser']));
				$_SESSION['avatarUser'] = $avatar ;	
			}

            //Lorsque l'user ne selectionne pas d'avatar
            if($_SESSION['avatarUser'] == "./images/avatars/perso/"){
                $avatar = $user['avatarUser'];
				$insertavatar = $bdd->prepare("UPDATE USER SET avatarUser = ? WHERE idUser = ?");
				$insertavatar->execute(array($avatar, $_SESSION['idUser']));
				$_SESSION['avatarUser'] = $user['avatarUser'];	
            }

            
		
		}
		else {
			$erreur = "Aucune modification !";
		}
		// Etape 4 : ferme la connexion au serveur de base de données
		$pdo = null ;
		
		if(isset($erreur) == false) {
			/*header('Location: page-profil.php');*/
		}
?>


<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil - TechTheory</title>
    <meta name="viewport" content="width=device-width,initialscale=1.0,shrink-to-fit=no" />
    <meta name="description"
        content="Site de TechTheory regroupant une multitude de podcasts sur l'high-tech et les nouvelles technologies">
    <link rel="stylesheet" href="css/profil-style.css" type="text/css" />
    <link rel="stylesheet" href="css/global-style.css" type="text/css" />
    <link rel="stylesheet" href="css/menu.css" type="text/css" />
    <link rel="icon" href="images/favicon.svg">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <script src="js/chargementAvatars.js"></script>
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
</head>

<body>
    <!----- HEADER ----->

    <?php include("menu.php"); ?>

    <!----- MAIN ----->

    <main>
        <section class="sec-compte">
            <h2 class="h2-compte">Mon compte</h2>
            <div class="container-avatar">
                <div class="left-part">
                    <div class="avatar-personne">
                    <img src=<?php echo($_SESSION['avatarUser']); ?> alt="avatar de la personne" class="img-personne">
                        <p class="p-avatar">Modifier mon avatar</p>
                    </div>
                    <div class="selection">
                        <div class="follow-creator">
                            <img src="images/profil/coeur-vide.png" alt="coeur vide créateurs" class="img-left-avatar">
                            <a href="suivis.php" class="p-creator btn-lien-page btn-lien-pause">Créateurs suivis</a>
                        </div>
                        <div class="comment">
                            <img src="images/profil/bulle-comment.png" alt="coeur vide créateurs" class="img-left-avatar">
                            <a href="commentaires.php" class="p-creator btn-lien-page btn-lien-pause">Commentaires laissés</a>
                        </div>
                        <div class="disconnect">
                            <img src="images/profil/deconnexion.png" alt="coeur vide créateurs" class="img-left-avatar">
                            <a href="deconnexion.php" class="p-creator btn-lien-page btn-lien-stop">Déconnexion</a>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="avatarChemin" name="avatarChemin" value="">
                    <div class="right-part">
                        <div class="container-right">
                            <h3 class="h3-avatar">Avatars prédéfinis</h3>
                            <div class="img-predef">
                            <img src="images/avatars/predef/avatar-defaut.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-defaut.png">
                            <img src="images/avatars/predef/avatar-predef1.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef1.png">
                            <img src="images/avatars/predef/avatar-predef2.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef2.png">
                            <img src="images/avatars/predef/avatar-predef3.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef3.png">
                            <img src="images/avatars/predef/avatar-predef4.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef4.png">
                            <img src="images/avatars/predef/avatar-predef5.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef5.png">
                            <img src="images/avatars/predef/avatar-predef6.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef6.png">
                            <img src="images/avatars/predef/avatar-predef7.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef7.png">
                            <img src="images/avatars/predef/avatar-predef8.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef8.png">
                            <img src="images/avatars/predef/avatar-predef9.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef9.png">
                            <img src="images/avatars/predef/avatar-predef10.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef10.png">
                            <img src="images/avatars/predef/avatar-predef11.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef11.png">
                            <img src="images/avatars/predef/avatar-predef12.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef12.png">
                            <img src="images/avatars/predef/avatar-predef13.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef13.png">
                            <img src="images/avatars/predef/avatar-predef14.png" alt="avatar prédéfinis" class="img-avatar"
                                data-image="images/avatars/predef/avatar-predef14.png">
                        </div>
                            <div class="import-section">
								<input type="file" name="newavatar" class="p-import" placeholder="Avatar" value="<?php echo $user['pseudoUser']; ?>" />
							

                            </div>
                            <p class="tips-photo">Il est conseillé de mettre une photo de 150 x 150 pixel</p>
                        </div>
                        <div class="btn-modif">
                            <input type="submit" name="formeditionprofil" class="modif" value="Modifier mon avatar">
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <?php include("popup.php"); ?>
        <?php include("barreLecture.php"); ?>
    </main>
</body>

<?php
	}
	else {
		header("Location: connexion.php");
	}
?>