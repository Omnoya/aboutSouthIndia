<?php

require_once ('../modele/fonction.php');
$contenusaisiemdpperdu = "";

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "";
$chemin2 = "../";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "inactif";
$position7 = "inactif";

$pseudo = "";
$email = "";
$messageinscriptionemail = "";
$messagereussiteenvoi = "";
$verification = true;

//On va vérifier si l'adresse e-mail est présente dans la base de données
if (isset($_POST['submitemail'])) {
    //On va récupérer la saisie et l'afficher, et j'utilise trim() pour enlever les espaces
    $pseudo .= trim($_POST['pseudo']);
    $email .= trim($_POST['email']);

    $requete = $pdo->prepare("SELECT * FROM membres WHERE pseudo=:pseudo AND etat=0");
    $requete->bindValue(":pseudo", $pseudo);
    $requete->execute();

    $tableau = $requete->fetch(PDO::FETCH_ASSOC);

    if (empty($pseudo)) {
        $messageinscriptionemail .= "<div class=\"contenu_erreur\"><p>Veuillez saisir votre pseudo.</p></div>";
    } elseif ($tableau['pseudo'] != $pseudo) {
        $messageinscriptionemail .= "<div class=\"contenu_erreur\"><p>Le pseudo saisi est incorrect.</p></div>";
    } elseif (empty($email)) {
        $messageinscriptionemail .= "<div class=\"contenu_erreur\"><p>Veuillez saisir votre adresse e-mail.</p></div>";
    } elseif ($tableau['email'] != $email) {
        $messageinscriptionemail .= "<div class=\"contenu_erreur\"><p>Cette adresse mail nous est inconnue, veuillez saisir une adresse e-mail valide.</p></div>";
    } else {
        //Sinon, on va comparer cette saisie aux entrées dans la base de donnéee
        $requetemail = $pdo->prepare("SELECT * FROM membres WHERE email=:email AND pseudo=:pseudo");
        $requetemail->bindValue(":email", $email);
        $requetemail->bindValue(":pseudo", $pseudo);
        $requetemail->execute();
        //On va vérifier si la requete a affecté une ligne dans la base de données
        if ($requetemail->rowCount() >= 1) {
            //On va donc récupérer les informations de l'utilisateur dans un tableau grâce à fetch()
            $tableaumail = $requetemail->fetch(PDO::FETCH_ASSOC);

            //On va rédiger le mail avec les informations de l'utilisateur
            $sujet = "Vos identifiants";
            $entete = "From: omnoya@gmail.com";
            $message = 'Voici un rappel de vos identifiants du site About South India:
                
Pseudo:' . $tableaumail['pseudo'] . '
Nom:' . $tableaumail['nom'] . '
Prenom:' . $tableaumail['prenom'] . '
Mot de passe:' . $tableaumail['mdp'] . '
 
Une fois connecté à About South India, vous pourrez mofidier ce mot de passe à l\'adresse
http://localhost/projetv2/index.php


---------------
Ceci est un mail automatique, Merci de ne pas y repondre';

            //On va envoyer le mail et vérifier si l'envoi s'est bien déroulé
            if (!@mail($email, $sujet, $message, $entete)) {
                //Si l'envoi a échoué
                $messageinscriptionemail .= "<p>Echec de l'envoi du mail, veuillez essayer ultérieurement.</p>";
            } else {
                //On ne va pas afficher le formulaire
                $verification = false;
                //Si l'envoi est un succès
                $messagereussiteenvoi .= "<div id=\"verification\"><p>La vérification s'est bien déroulée, veuillez retrouvez vos informations dans le mail que vous venez de recevoir.</p></div>";
                //Et ensuite, on le redirige vers l'acceuil
                //header("refresh:5; url=../index.php");
            }
        }
    }
}

//------------------------------------------------------------------------------

if ($verification) {
    $contenusaisiemdpperdu .= '
                           <div id="indication"><p> Vous avez oublié votre mot de passe ? Indiquez-nous votre adresse email et nous vous le renverrons par email.</p></div>
    
                           <form method="post" action="' . $_SERVER['PHP_SELF'] . '#ancre">
                                <input type="text" value="' . $pseudo . '" placeholder="Pseudo" class="champmdpperdu" name="pseudo"/>
                                <input type="email" name="email" placeholder="Email" class="champmdpperdu" value="' . $email . '"/>' . $messageinscriptionemail . '
                                <input type="submit" name="submitemail" id="submitemail" />
                           </form><br/>';
}