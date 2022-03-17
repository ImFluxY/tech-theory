<?php
    // inscription.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    /*header("Content-Type: text/html; charset=utf-8") ;*/
    require (__DIR__ . "/param.inc.php");
    
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $mail = $_POST["mail"];
    $objet = $_POST["objet"];
    $message = $_POST["message"];
    $date = date('Y-m-d'); // Format yyyy-mm-dd

    //Connexion à la bdd
    $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
    $bdd->query("SET NAMES utf8");
    $bdd->query("SET CHARACTER SET 'utf8'");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Envoie des infos
    $requser = $bdd->prepare("INSERT INTO CONTACT(nom, prenom, mail, objet, message, date) VALUES( ?, ?, ?, ?, ?, ?)");
    $requser->execute(array($nom, $prenom, $mail, $objet, $message, $date));

    //Redirection
    header('Location: ./retourformulaire.html');

    $bdd = null;
?>