<?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");
    if(isset($_SESSION['idUser'])){
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Page créateurs suivis du projet tutoré, TechTheory">
    <title>Créateurs suivis</title>
    <link rel="stylesheet" href="css/global-style.css" type="text/css">
    <link rel="stylesheet" href="css/createur-suivi.css" type="text/css">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
    <link rel="icon" href="images/favicon.svg">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/popup.css" type="text/css" />
    <link rel="stylesheet" href="css/style-barreLecture.css" type="text/css" />
    <script src="js/interaction-suivre.js"></script>
    
</head>

<body>
    <?php include("menu.php"); ?>

    <main>

        <div class="centre">
            <div class="centre2">
                <h1>Créateurs suivis</h1>
                <div class="ensemble-createurs">
                    <?php
                        // Connexion au serveur de base de données
                        $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
                        $bdd->query("SET NAMES utf8");
                        $bdd->query("SET CHARACTER SET 'utf8'");

                        // Envoi de la requête SQL au serveur
                        $id = $_SESSION['idUser'];
                        $requete = "SELECT c.nomEmission, c.imgEmission, descCreateur, c.idCreateur
                        FROM CREATEUR c
                        INNER JOIN SUIVRE s ON s.idCreateur = c.idCreateur
                        WHERE s.idUser = $id;";
                        $statement = $bdd->query($requete);
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

                        while ($ligne != false) {                           
                    ?>
                    <div class="createur-fav">
                        <div class="logo-nom">
                        <a href="createur.php?id=<?php echo($ligne['idCreateur'])?>">
                            <img src="<?php echo($ligne['imgEmission'])?>" alt="logo <?php echo($ligne['nomEmission'])?>" class="logo">
                            <p><?php echo($ligne['nomEmission']); ?></p>
                            </a>
                        </div>
                        <?php
                            //Voir si on suit
                            $idCreateur = $ligne['idCreateur'];
                            $idUser = $_SESSION['idUser'];
                            $suivrerqt = "SELECT *
                            FROM SUIVRE s
                            WHERE s.idCreateur = '$idCreateur' AND s.idUser = '$idUser'";
                            $suivrestatement = $bdd->query($suivrerqt);
                            $suivreCreateur = $suivrestatement->fetch(PDO::FETCH_ASSOC);

                            $suivre = 1;

                            if($suivreCreateur == false)
                            {
                                $suivre = 0;
                            }
                        ?>
                        <img id ="imgCoeur" src="images/coeur-rempli.png" alt="coeur rempli" class="heart-details" title="Ne plus aimer le créateur"  data-aimer="<?php echo($suivre); ?>" data-createur="<?php echo($idCreateur); ?>">
                    </div>
                    <hr class="trait" >
                    <?php
                    
                        $ligne = $statement->fetch(PDO::FETCH_ASSOC);
                        }
                        
                        

                        // Fermer la connexion au serveur de base de données
                        $pdo = null;
                    ?>
                </div>
            </div>
        </div>
        <?php include("popup.php"); ?>
        <?php include("barreLecture.php"); ?>
    </main>

    <footer></footer>
</body></html>
<?php
	}
	else {
		header("Location: connexion.php");
	}
?>
