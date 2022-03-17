<?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");
    if(isset($_SESSION['idUser'])){
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Accueil - TechTheory</title>
    <meta name="viewport" content="width=device-width,initialscale=1.0,shrink-to-fit=no" />
    <meta name="description"
        content="Site de TechTheory regroupant une multitude de podcasts sur l'high-tech et les nouvelles technologies">
    <link rel="stylesheet" href="css/global-style.css" type="text/css" />
    <link rel="stylesheet" href="css/profil-style.css" type="text/css" />
    <link rel="stylesheet" href="css/details-podcasts.css" type="text/css" />
    <link rel="stylesheet" href="css/pages-commentaires.css" type="text/css" />
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
</head>

<body>
    <?php include("menu.php"); ?>

    <!----- MAIN ----->

    <main>
        <h2 class="h2-compte">Commentaires laissés</h2>
        <div class="container-comment-personnes">
            <div class="infos-personnes">
                <img src=<?php echo($_SESSION['avatarUser']); ?> alt="avatar de la personne" class="img-personne">
                <p class="nom-avatar"><?php echo($_SESSION['pseudoUser']); ?></p>
            </div>
            <div class="zones-comment">
                <?php
                    // Connexion au serveur de base de données
                    $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                    $bdd->query("SET NAMES utf8");
                    $bdd->query("SET CHARACTER SET 'utf8'");

                    // Envoi de la requête SQL au serveur
                    $id = $_SESSION['idUser'];
                    $requete = "SELECT c.idPodcast, c.contenuCommentaire, c.dateCommentaire, p.titrePodcast

                    FROM COMMENTAIRE c
                    INNER JOIN PODCAST p ON p.idPodcast = c.idPodcast
                    WHERE c.idUser = $id;";
                    $statement = $bdd->query($requete);
                    $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    while ($ligne != false) {
                ?>
                <div class="sec-comment-personnes">
                    <img class="img-avatar-comment" alt="icône avatar" src=<?php echo($_SESSION['avatarUser']); ?>>
                    <div class="infos-comment">
                        <div class="infos-content">
                            <p class="name-date-comment"><?php echo($_SESSION['pseudoUser']); ?> · 
                            <span class="date-color"> <?php echo($ligne['dateCommentaire']); ?></span> · 
                            <span> <a href="podcast.php?id=<?php echo($ligne['idPodcast']);?>" class="btn-lien-page btn-lien-pause" font color="#aab1c3"><?php echo  substr($ligne['titrePodcast'], 0, 15); ?>...</a></span></p>
                            <textarea disabled="disabled" class="person-comment" cols="65"><?php echo($ligne['contenuCommentaire']); ?></textarea>
                        </div>

                    </div>
                </div>
                <?php
                    $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                    }

                    // Fermer la connexion au serveur de base de données
                    $pdo = null;
                ?>

<!--
                <div class="sec-comment-personnes">
                    <img class="img-avatar-comment" alt="icône avatar" src="images/avatar-test.png">
                    <div class="infos-comment">
                        <div class="infos-content">
                            <p class="name-date-comment">Mathieu · <span class="date-color"> 06 / 05 / 2021
                                </span></p>
                            <textarea class="person-comment" cols="65">lllllllllllllefqhhhhhh</textarea>
                        </div>

                    </div>
                </div>
                <div class="sec-comment-personnes">
                    <img class="img-avatar-comment" alt="icône avatar" src="images/avatar-test.png">
                    <div class="infos-comment">
                        <div class="infos-content">
                            <p class="name-date-comment">Mathieu · <span class="date-color"> 06 / 05 / 2021
                                </span></p>
                            <textarea class="person-comment" cols="65">lllllllllllllefqhhhhhh</textarea>
                        </div>

                    </div>
                </div>
                <div class="sec-comment-personnes">
                    <img class="img-avatar-comment" alt="icône avatar" src="images/avatar-test.png">
                    <div class="infos-comment">
                        <div class="infos-content">
                            <p class="name-date-comment">Mathieu · <span class="date-color"> 06 / 05 / 2021
                                </span></p>
                            <textarea class="person-comment" cols="65">lllllllllllllefqhhhhhh</textarea>
                        </div>
                    </div>
                </div>
                -->
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