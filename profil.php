<?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page profil du projet tutoré, TechTheory">
    <title>Page profil</title>
    <link rel="stylesheet" href="css/page-profil.css" type="text/css">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
    <link rel="icon" href="images/favicon.svg">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
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
                        <img src="<?php echo($_SESSION['avatarUser']) ?>" alt="avatar anonyme" class="profil-avatar">
                        <p class="modif-avatar"><a class="a-modif-avatar btn-lien-page btn-lien-pause" href="modifier-avatar.php" >Modifier mon avatar</a></p>
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
                            <input disabled="disabled" class="texte-input-user" type="email" name="mail" id="mail" spellcheck="false" placeholder="<?php echo($_SESSION['identifiantUser']) ?>">
                        </div>
                        <div class="champs grande-zone">
                            <label for="pseudo">Nom d'utilisateur</label>
                            <input disabled="disabled" class="texte-input-user" type="text" name="pseudo" id="pseudo" spellcheck="false" placeholder="<?php echo($_SESSION['pseudoUser'])?>">
                        </div>
                        <div class="naissance-tel">
                            <div class="champs">
                                <label for="dateNaissance">Date de naissance</label>
                                <input disabled="disabled" class="texte-input-user" type="text" name="dateNaissance" id="dateNaissance" spellcheck="false" placeholder="<?php echo ($_SESSION['dateNaissanceUser'])?>">
                            </div>
                            <div class="champs">
                                <label for="numTel">Num. de téléphone</label>
                                <input disabled="disabled" class="texte-input-user" type="tel" name="numTel" id="numTel" spellcheck="false" placeholder="<?php echo($_SESSION['numTelUser']) ?>">
                            </div>
                        </div>
                        
                        
                        <div class="btn-modif-info"><a id="valider" class="btn-envoyer btn-lien-page btn-lien-stop" href="modifier-profil.php">Modifier mes informations</a></div>
                        
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include("popup.php"); ?>
    <?php include("barreLecture.php"); ?>
</body>

</html>