<?php
// Fichier d'initialisation.
$pdo = new PDO('mysql:host=localhost;dbname=', '', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION)); 
//var_dump($pdo); //la fonction var_dump affiche les informations d'une variable (type et valeur);
session_start(); //crée une session ou restaure celle trouvée
$content = '';
//echo $_SERVER['DOCUMENT_ROOT'];
//define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/projetv2/'); //initialisation de la constante RACINE_SITE en utilisant la fonction define avec pour valeur, la variable superglobal $_SERVER(tableau contenant des informations sur le chemin du script) en demandant l'élément['DOCUMENT_ROOT']pour obtenir la racine sous laquelle le script courant est exécuté, comme défini dans la configuration du serveur, donc jusqu'à C:/wamp/www et nous ajoutons le reste du chemin où le script est exécuté.
//require_once(RACINE_SITE . 'inc/fonction.inc.php');
// debug($_SERVER);     debug($_SERVER);     pour visionner le contenu de la variable $_SERVER, j'ai créé la fonction debug() dans le fichier fonction.inc.php
