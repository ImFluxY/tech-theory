<?php
    // connexion.php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
	session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	
	require (__DIR__ . "/param.inc.php");
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
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
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
                <img src="images/personnage_compte.png" alt="Personnage page mot de passe oublié">
            </div>

            <div class="compte">
                <h2>Vérifiez vos mails</h2>

                <form action="" method="post" class="formulaire">
                    <div class="champs">
                        <label for="pseudo"><strong>Cliquez sur le lien envoyé à :</strong></label>
                        <input disabled="disabled" type="text" name="identifiant" id="identifiant" spellcheck="false" placeholder="<?php echo($_SESSION['identifiantUser']); ?>">
                    </div>

                    <div class="champs">
                        <label for="valider"></label>
                        
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
