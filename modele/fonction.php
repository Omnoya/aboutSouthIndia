<?php

require_once ('connexionalabasededonnee.php');

//---------------------------------
function debug($arg) {
    print '<pre>';
    print_r($arg);
    print '</pre>';
}

//---------------------------------
function membreEstConnecte() {
    return !empty($_SESSION['membre']);
}

//---------------------------------
function adminEstConnecte() {
    if (membreEstConnecte()) {
        return $_SESSION['membre']['etat'] == 1;
    }
}

/**
 * Requete pour récupérer les données membres
 * @param type $argument1 : pseudo
 * @param type $argument2 : mot de passe
 * @return type
 */
function recupererdonnees_membre($argument1, $argument2) {
    global $pdo;
    $requete = $pdo->prepare("SELECT * FROM membres WHERE (pseudo=:pseudo AND mdp=:mdp) AND actif=1");
    $requete->bindValue(":pseudo", trim($argument1));
    $requete->bindValue(":mdp", trim($argument2));
    return $requete;
}

/**
 * Requete SELECT pour tout récupérer
 * @param string $argument1 : nom de la table
 */
function recupererdonnees($argument1) {
    global $pdo;
    $requete = $pdo->query("SELECT * FROM $argument1");
    return $requete;
}

/**
 * Requete SELECT pour récupérer les photos selon l'Etat du sud  
 * @param string $argument1 : nom de l'etat
 */

function recupererphotos($argument1) {
    global $pdo;
    $requete = $pdo->query("SELECT etat_sud, nom_image, contenu FROM image WHERE etat_sud='$argument1'");
    return $requete;
}