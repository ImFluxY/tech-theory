<?php
    // connexion.php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
	session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	
	require (__DIR__ . "/param.inc.php");
	 
	if(isset($_POST['formconnexion'])) {
		$mailconnect = htmlspecialchars($_POST['mailconnect']);
		$mdpconnect = sha1($_POST['mdpconnect']);
		if(!empty($mailconnect) AND !empty($mdpconnect)) {
			// Etape 1 : connexion au serveur de base de données
			$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
			$bdd->query("SET NAMES utf8");
			$bdd->query("SET CHARACTER SET 'utf8'");
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Etape 2 : envoi de la requête SQL au serveur

			$requser = $bdd->prepare("SELECT idUser, pseudoUser, identifiantUser, dateNaissanceUser, numTelUser, avatarUser FROM USER WHERE identifiantUser = ? AND mdpUser = ?");
			
			$requser->execute(array($mailconnect, $mdpconnect));
			$ligne = $requser->fetch(PDO::FETCH_ASSOC) ;
			if($ligne != false) {
				$_SESSION['idUser'] = $ligne['idUser'];
				$_SESSION['pseudoUser'] = $ligne['pseudoUser'];
				$_SESSION['identifiantUser'] = $ligne['identifiantUser'];
				$_SESSION['dateNaissanceUser'] = $ligne['dateNaissanceUser'];
				$_SESSION['numTelUser'] = $ligne['numTelUser'];
                $_SESSION['avatarUser'] = $ligne['avatarUser'];

				header("Location: index.php");
			} else {
				$erreur = "Mauvais mail ou mot de passe !";
			}
			
			// Etape 4 : ferme la connexion au serveur de base de données
			$pdo = null ;
		} else {
			$erreur = "Tous les champs doivent être complétés !";
		}
	}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Page de connexion du projet tutoré, TechTheory">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
</head>
	<body>
    <main>
		<div class="container-connect">
        <div class="cadre">
            <div class="acceuil">
				<div class="content-text">
                	<h1 class="tt-titre">TechTheory</h1>
                	<p class="tt-desc">Découvrez l'high-tech sous un nouvel angle !</p>
				</div>
                <img src="images/personnage_compte.png" alt="Personnage page de connexion">
            </div>
			<div class="connect">
            <div class="compte">
                <h2 class="connect-text">Connectez vous</h2>
                <form action="" method="post" class="formulaire">
					<div class="champs">
						<label for="mail">E-mail</label>
						<input type="email" name="mailconnect" spellcheck="false" placeholder="nom@gmail.com">
					</div>
					<div class="champs">
						<label for="mdp">Mot de passe</label>
						<input type="password" name="mdpconnect" spellcheck="false" placeholder="8 caractères minimum, une lettre majuscule">
					</div>
					<p>Mot de passe oublié ? <a href="mdp-oublie.php">Cliquez ici</a></p>
					<p>Pas encore membre ? Alors inscrivez-vous <a href="inscription-site.php">par ici.</a></p>
					<div class="champs">
						<label for="formconnexion"></label>
						<button type="submit" name="formconnexion" id="formconnexion">Connexion</button>
					</div>
				</form>
            </div>
			</div>
        </div>
		</div>
    </main>

</body>
</html>