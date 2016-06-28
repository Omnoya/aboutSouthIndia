<?php

require_once ('../modele/fonction.php');
$contenuparametresmembre = "";

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
$mdp = "";
$erreurmessage = "";

$pseudomodification = "";
$nom = "";
$prenom = "";
$email = "";
$mdpmodification = "";
$messagemodificationpseudo = "";
$messagemodificationnom = "";
$messagemodificationprenom = "";
$messagemodificationemail = "";
$messagemodificationmdp = "";
$messagemodif = "";

$verifier = "";

if (!membreEstConnecte()) {
    header('Location: ../index.php');
} else {
    $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
    $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="inclusions/deconnexion.php"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';

//On affiche les informations de l'utilisateur
    $result = $pdo->prepare("SELECT * FROM membres WHERE pseudo = :pseudomembre");
    $result->bindValue(":pseudomembre", $_SESSION['membre']['pseudo']);
    $result->execute();
    $recupdonnees = $result->fetch(PDO::FETCH_ASSOC);

    $id = $recupdonnees['id_membre'];
    $pseudomodification = $recupdonnees['pseudo'];
    $nom = $recupdonnees['nom'];
    $prenom = $recupdonnees['prenom'];
    $email = $recupdonnees['email'];
    $mdpmodification = $recupdonnees['mdp'];
//------------------------------------------------------------------------------------------------------

    if (isset($_POST["submitmodification"])) {


        $pseudomodification = $_POST['pseudomodification'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mdpmodification = $_POST['mdpmodification'];

        $resultat = $pdo->prepare("SELECT * FROM membres WHERE pseudo != :pseudobdd");
        $resultat->bindValue(":pseudobdd", $_SESSION['membre']['pseudo']);
        $resultat->execute();
        //Pour faire la comparaison, on va effectuer une boucle afin de parcourir TOUS les résultats
        while($recupdonnees = $resultat->fetch(PDO::FETCH_ASSOC)){
            //Si le pseudo saisi correspond à un des résultats
            if($recupdonnees['pseudo'] == $pseudomodification){
                //On stocke alors le résultat dans une variable, qu'on utilisera pour faire la vérification
                $verifier .= $recupdonnees['pseudo'];
            }
        }

        if (empty($pseudomodification)) {
            $messagemodificationpseudo .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un pseudo.</p></div>";
        } elseif ($verifier == $pseudomodification) {
            $messagemodificationpseudo .= "<div class=\"contenu_erreur\"><p>Le pseudo existe déja. Veuillez en saisir une nouvelle.</p></div>";
        } elseif (empty($nom)) {
            $messagemodificationnom .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un nom.</p></div>";
        } elseif (empty($prenom)) {
            $messagemodificationprenom .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un prenom.</p></div>";
        } elseif (empty($email)) {
            $messagemodificationemail .= "<div class=\"contenu_erreur\"><p>Veuillez saisir une adresse mail.</p></div>";
        } elseif (empty($mdpmodification)) {
            $messagemodificationmdp .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un mot de passe</p></div>";
        } else {
            $result = $pdo->prepare("UPDATE membres SET pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, mdp = :mdp WHERE id_membre = :id AND etat=0");
            $result->bindValue(":pseudo", $pseudomodification);
            $result->bindValue(":nom", $nom);
            $result->bindValue(":prenom", $prenom);
            $result->bindValue(":email", $email);
            $result->bindValue(":mdp", $mdpmodification);
            $result->bindValue(":id", $id);
            $result->execute();
            if ($result->rowCount() >= 1) {
                $messagemodif .= "<div class=\"contenu_erreur\"><p>Modification enregistrée.</p></div>";
                $_SESSION['membre']['pseudo'] = $pseudomodification;
                header("refresh:2");
            }
        }
    }

    $contenuparametresmembre .= '<form method="post" action="' . $_SERVER['PHP_SELF'] . '#ancre">
    <input type="text" name="pseudomodification" placeholder="Pseudo" class="champmodification" value="' . $pseudomodification . '" />' . $messagemodificationpseudo . ' 
    <input type="text" name="nom" placeholder="Nom" class="champmodification" value="' . $nom . '"/>' . $messagemodificationnom . '
    <input type="text" name="prenom" placeholder="Prenom" class="champmodification" value="' . $prenom . '"/>' . $messagemodificationprenom . '
    <input type="email" name="email" placeholder="Email" class="champmodification" value="' . $email . '"/>' . $messagemodificationemail . '
    <input type="text" name="mdpmodification" placeholder="Mot de passe" class="champmodification" value="' . $mdpmodification . '"/>' . $messagemodificationmdp . '
        ' . $messagemodif . '
    <input type="submit" name="submitmodification" id="submitmodification" value="Modifier" />
    
</form><br/>';
}