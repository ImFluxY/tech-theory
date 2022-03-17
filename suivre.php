<?php
    // ecoute.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
	header("Content-Type: text/html; charset=utf-8") ;
	require (__DIR__ . "/param.inc.php");

    //Connexion à la base de données
    $bdd = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
    $bdd->query("SET NAMES utf8");
    $bdd->query("SET CHARACTER SET 'utf8'");

    //Récupération des données
    if(isset($_SESSION['idUser']) AND isset($_POST['suivre']))
    {
        $idUser = $_SESSION['idUser'];
        $idCreateur = $_POST['createur'];
        $suivre = $_POST['suivre'];

        //Vérification qu'il n'éxiste pas de lien
        $requete = "SELECT *
                FROM SUIVRE s
                WHERE s.idUser = $idUser AND s.idCreateur = $idCreateur;";
        $statement = $bdd->query($requete);
        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

        if($ligne == false AND $suivre == 1)
        {
            //Insertion des données
            $insertsuivre = $bdd->prepare("INSERT INTO SUIVRE(idUser, idCreateur) VALUES(?, ?)");
            $parametressuivre = array($idUser, $idCreateur);
            $insertsuivre->execute($parametressuivre);
        }
        else if($suivre == 0){
            $requete = "DELETE
            FROM SUIVRE
            WHERE idUser = $idUser AND idCreateur = $idCreateur;";
            $statement = $bdd->query($requete);
        }
    }
?>