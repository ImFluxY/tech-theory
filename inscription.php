<?php
    // inscription.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	header("Content-Type: text/html; charset=utf-8") ;
	
	require (__DIR__ . "/param.inc.php");
	 
	if(isset($_POST['forminscription'])) {
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		$dateNaissance = $_POST['dateNaissance'];
		$numTel = $_POST['numTel'];
		$mdp1 = sha1($_POST['mdp1']);
		$mdp2 = sha1($_POST['mdp2']);
		// expression régulière majuscule
		$patern = '[A-Z]';

		if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mdp1']) AND !empty($_POST['mdp2'])) {
			if(isset($_POST['interet'])){
				$pseudolength = mb_strlen($pseudo);
				if($pseudolength <= 16) {
					$mdplenght = mb_strlen($_POST['mdp1']);
					if($mdplenght >= 8){

						//1 majuscule dans le mdp
						if (preg_match("#[A-Z]#", $_POST['mdp1'])){

						if(ageValide($dateNaissance, 13)){
							if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
								// Connexion au serveur de base de données
								$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
								$bdd->query("SET NAMES utf8");
								$bdd->query("SET CHARACTER SET 'utf8'");
								$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

								$reqmail = $bdd->prepare("SELECT * FROM USER WHERE identifiantUser = ?");
								$reqmail->execute(array($mail));
								$ligne = $reqmail->fetch(PDO::FETCH_ASSOC);
								if($ligne == false) {
									if($mdp1 == $mdp2) {
										if(!empty($_POST['politique-donnees'])){
											echo('!empty');

										// Envoi de la requête SQL au serveur
										$insertmbr = $bdd->prepare("INSERT INTO USER(pseudoUser, identifiantUser, mdpUser, dateNaissanceUser, numTelUser) VALUES(?, ?, ?, ?, ?)");
										$parametres = array($pseudo, $mail, $mdp1, $dateNaissance, $numTel);
										$insertmbr->execute($parametres);

										//Récupérer l'id de l'utilisateur ajouté
										$dataid = $bdd->lastInsertId();
										// Récupérer l'id de l'intérêt choisit
										$interet = $_POST['interet'];
										$sqlinteret = $bdd->prepare("SELECT idFiltre FROM CATEGORIES WHERE nomFiltre = ?"); 
										$sqlinteret->execute(array($interet));
										$datainteret = $sqlinteret->fetch(PDO::FETCH_NUM);

										//Insérer la liaison entre le nouvel USER et sa CATEGORIE dans EST_INTERRESSE
										$insertinteret = $bdd->prepare("INSERT INTO EST_INTERRESSE(idFiltre, idUser) VALUES(?, ?)");
										$insertinteret->execute(array($datainteret[0], $dataid));

										//Insérer un avatar prédéfini aléatoirement

										// Renvoyer vers la page de connexion une fois l'inscription finie
										header("Location: connexion.php");

										}else{
											$erreur = "Vous devez acceptez notre politique de données pour vous inscrire";
										}
									} else {
										$erreur = "Vos mots de passes ne correspondent pas !";
									}
								} else {
									$erreur = "Adresse mail déjà utilisée !";
								}
								// Ferme la connexion au serveur de base de données
								$pdo = null ;
							} else {
								$erreur = "Votre adresse mail n'est pas valide !";
							}
						}else{
							$erreur = "Vous devez avoir au moins 13 ans pour vous inscrire !";
						}
					} else {
						$erreur = "Votre mot de passe doit posséder au moins 1 majuscule !";
					}

					} else {
						$erreur = "Votre mot de passe doit posséder au moins 8 caractères !";
					}
				} else{
					$erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
				}
			}else{
				$erreur = "Vous devez renseigner un intérêt !";
			}
		} else {
			$erreur = "Tous les champs doivent être complétés !";
		}
	}

	function ageValide($dateNaissance, $age)
	{
		if(is_string($dateNaissance))
		{
			$dateNaissance = strtotime($dateNaissance);
		}

		if(time() - $dateNaissance < $age * 31536000)
		{
			return false;
		}

		return true;
	}
	
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page d'inscription du projet tutoré, TechTheory">
    <title>Inscription</title>
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
                <img src="images/personnage_compte.png" alt="Personnage page d'inscription">
            </div>
            <div class="compte">
			<h2 class="connect-text">Inscrivez-vous</h2>
                <form method="post" enctype="multipart/form-data" class="formulaire">
                    <div class="champs">
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" placeholder="Votre pseudo" class="entre" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
                    </div>
                    <div class="champs">
                        <label for="mail">Mail :</label>
                        <input type="email" placeholder="Votre mail" class="entre" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                    </div>
					<div class="date-tel">
                    <div class="champs">
                        <label for="age" class="label-change-ins">Votre date de naissance :</label>
                        <input type="date" placeholder="Votre date de naissance" id="age" class="entre" name="dateNaissance" value="<?php if(isset($dateNaissance)) { echo $dateNaissance; } ?>" />
                    </div>
					
                    <div class="champs">
                        <label for="numTel" class="label-change-ins">Votre numéro de téléphone :</label>
                        <input type="tel" placeholder="Votre numéro de téléphone" class="entre" id="numTel" name="numTel" value="<?php if(isset($numTel)) { echo $numTel; } ?>" />
                    </div>
					</div>
					<div class="mdp-line">
                    <div class="champs">
                        <label for="mdp" class="label-change-ins">Mot de passe :</label>
                        <input type="password" placeholder="8 caractères minimum, une lettre majuscule" class="entre" id="mdp" name="mdp1" />
                    </div>
                    <div class="champs">
                        <label for="mdp2" class="label-change-ins">Confirmation du mot de passe :</label>
                        <input type="password" placeholder="Confirmez votre mot de passe" class="entre" id="mdp2" name="mdp2" />
                    </div>
					</div>
                    <div class="champs">
                        <label for="interet">Intérêt</label>
						
                        <select name="interet" id="interet" spellcheck="false">
                            <option value="" disabled selected hidden>-- Sélectionnez 1 interêt --</option>
                            <?php
    						// Connexion au serveur de base de données
    						$bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
    						$bdd->query("SET NAMES utf8");
    						$bdd->query("SET CHARACTER SET 'utf8'");

    						// Envoi de la requête SQL au serveur
    						$requete = "SELECT nomFiltre, afficherFiltre FROM CATEGORIES ORDER BY nomFiltre ASC";
    						$statement = $bdd->query($requete);

    						// Affiche données retournées
    						$ligne = $statement->fetch(PDO::FETCH_ASSOC);
    						while ($ligne != false) {
								echo '<script>';
  								echo 'console.log('. json_encode( $ligne['afficherFiltre'] ) .')';
  								echo '</script>';
								if($ligne['afficherFiltre'] > 0){
    					?>
                            <option value="<?php echo ($ligne["nomFiltre"]) ; ?>">
                                <?php echo ($ligne["nomFiltre"]) ; ?>
                            </option>
                            <?php
								}
								var_dump($ligne['afficherFiltre']);
        						$ligne = $statement->fetch(PDO::FETCH_ASSOC);
    						}
    
    						// Fermer la connexion au serveur de base de données
    						$pdo = null;
						?>
                        </select>
						
                    </div>
                    <p>Déja inscrit ? Alors connectez-vous <a href="connexion.php">par ici.</a></p>
					<div class="checkbox-po">
						<input type="checkbox" id="politique-donnees" name="politique-donnees" class="po-input-checkbox">
						<label for="politique-donnees" class="label-po">En cochant cette case, vous acceptez notre <a href="po-donnees.html" class="strong-po">politique de données</strong></label>
					</div>
                    <div>
                        <label for="forminscription"></label>
                        <button type="submit" id="forminscription" name="forminscription">Créer un compte</button>
                    </div>
					<?php 
					if(isset($erreur)){
						?>
                    <div class="erreur">
                        <p class="pErreur"><?php echo($erreur);?></p>
                    </div>
					<?php 
					}
					?>
            	</div>
            	</form>
        	</div>
    	</div>
	</main>
</body>

</html>
