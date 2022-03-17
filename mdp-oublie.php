<?php
    // connexion.php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
	session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	
	require (__DIR__ . "/param.inc.php");
	 
	if(isset($_POST['sendMdpOublie'])) {
		$mailconnect = htmlspecialchars($_POST['identifiant']);

		if(!empty($mailconnect)) {
			// Etape 1 : connexion au serveur de base de données
			$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
			$bdd->query("SET NAMES utf8");
			$bdd->query("SET CHARACTER SET 'utf8'");
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Etape 2 : envoi de la requête SQL au serveur
			$requser = $bdd->prepare("SELECT mdpUser, pseudoUser, identifiantUser FROM USER WHERE identifiantUser = ?");
			$requser->execute(array($mailconnect));
			$ligne = $requser->fetch(PDO::FETCH_ASSOC) ;
            
            $mdpRecup = $ligne['mdpUser'];
            $pseudo = $ligne['pseudoUser'];
            
			if($ligne != false) {

                $_SESSION['identifiantUser'] = $ligne['identifiantUser'];
                $_SESSION['mdpUser'] = $ligne['mdpUser'];

                // Le message
                $message = "Bonjour $pseudo !\r\n\r\nVous avez fait une demande de récupération de mot de passe pour votre compte \r\nVotre mot de passe (haché/hashed) TechTheory est : $mdpRecup\r\n\r\nCliquez sur le lien ci-dessous pour vous connecter, puis choisissez un nouveau mot de passe.\r\nhttps://la-projets.univ-lemans.fr/~mmi1pj04/connexion-mdp-oublie.php \r\n\r\nBonne journée et bonne écoute, sur TechTheory !";

                // Dans le cas où nos lignes comportent plus de 70 caractères, nous les coupons en utilisant wordwrap()
                $message = wordwrap($message, 500, "\r\n");
                
                // Envoi du mail
                mail($mailconnect, 'Récupération de mot de passe', $message);

				header("Location: recuperation-mdp.php");
			} else {
				$erreur = "Mauvais mail !";
			}
			
			// Etape 4 : ferme la connexion au serveur de base de données
			$pdo = null ;
		} else {
			$erreur = "Renseignez votre adresse mail !";
		}
	}
?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page mot de passe oublié du projet tutoré, TechTheory">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <script src="js/histereg.js"></script>
</head>
<body>
    <header>
    </header>
    <main>
        <div class="container-connect">
        <div class="cadre">
            <div class="acceuil">
                <div class="content-text">
                	<h1 class="tt-titre">TechTheory</h1>
                	<p class="tt-desc">Découvrez l'high-tech sous un nouvel angle !</p>
				</div>
                <img src="images/personnage_compte-oublie.png" alt="Personnage page mot de passe oublié">
            </div>

            <div class="compte">
                <h2 class="connect-text">Mot de passe oublié ?</h2>

                <form action="" method="post" class="formulaire">
                    <div class="champs">
                        <label for="pseudo">E-mail</label>
                        <input type="text" name="identifiant" id="identifiant" spellcheck="false" placeholder="nom@gmail.com ou Aurelien77">
                    </div>

                    <div class="champs">
                        <label for="valider"></label>
                        <button type="submit" name="sendMdpOublie" id="valider">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </main>
<footer>
</footer>

</body>

</html>