<?php

require_once ('modele/fonction.php');

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "vue/";
$chemin2 = "";

$contenutitreforum = "";
$contenudescriptionforum = "";

$contenudatecommentaire = "";
$contenupseudocommentaire = "";
$contenucommentaire = "";

$contenuphotoarticle = "";
$contenudatearticle = "";
$contenutitrearticle = "";
$contenuarticle = "";

$contenualbumphoto = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "actif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "inactif";
$position7 = "inactif";

$pseudo = "";
$mdp = "";
$erreurmessage = "";

$test = basename($_SERVER["PHP_SELF"]);
if ($test != "index.php") {
    header("location: /projetv2/index.php");
}

if (!membreEstConnecte()) {

    if (isset($_POST["submitMembre"])) {
//On récupère la saisie
        $pseudo .= $_POST["pseudo"];
        $mdp .= $_POST["mdp"];
//On vérifie si la saisie est correct, à savoir qu'elle correspond au contenu de la base de données
//On commence par récupérer les données de la BDD là où le pseudo correspond
        $resultat = $pdo->prepare("SELECT * FROM membres WHERE pseudo=:pseudo");
        $resultat->bindValue(":pseudo", trim($pseudo));
        $resultat->execute();
//Ensuite, on parcourt les données récupérées
//$donnees est alors un tableau dont les indices correspondent aux colonnes dans la BDD
        $donnees = $resultat->fetch(PDO::FETCH_ASSOC);
//On va vérifier le contenu de la saisie
        if (empty($pseudo)) {
            $erreurmessage .= "<p class=\"message_erreur\">Veuillez saisir le pseudo.</p>";
        } elseif ($donnees['pseudo'] != $pseudo) {
            $erreurmessage .= "<p class=\"message_erreur\">Le pseudo saisi est incorrect.</p>";
        } elseif (empty($mdp)) {
            $erreurmessage .= "<p class=\"message_erreur\">Veuillez saisir le mot de passe.</p>";
        } elseif ($donnees['mdp'] != $mdp) {
            $erreurmessage .= "<p class=\"message_erreur\">Le mot de passe saisi est incorrect.</p>";
        } elseif ($donnees['actif'] != 1) {
            $erreurmessage .= "<p class=\"message_erreur\">Veuillez vérifier votre mail pour activer votre compte.</p>";
        } else {

            /*
              Préparation de la requete
              $oPrepare = $pdo->prepare("SELECT * FROM membres WHERE (pseudo=:pseudo AND mdp=:mdp) AND actif=1");
              $oPrepare->bindValue(":pseudo", trim($_POST["pseudo"])); //la fonction PDOStatement::bindValue() associe une valeur à un nom correspondant (ici, paramètre :pseudo) dans la requête SQL qui a été utilisé ci-dessus pour préparer la requête. trim() supprime les espaces (ou d'autres caractères) en début et fin de chaîne.
              $oPrepare->bindValue(":mdp", trim($_POST["mdp"]));
              //execution de la requete
              $oPrepare->execute(); */

            $oPrepare = recupererdonnees_membre($pseudo, $mdp);
            $oPrepare->execute();
            if ($oPrepare->rowCount() >= 1) {      //la fonction PDOStatement::rowcount() retourne le nombre de lignes affectées par le dernier appel à la fonction PDOStatement::execute()
// Récupération de la ligne trouvée :
                $personne = $oPrepare->fetch(PDO::FETCH_ASSOC);
// Supression du mot de passe avant le passage dans la session :
                unset($personne["mdp"]);

                $_SESSION['membre'] = $personne;

                if ($_SESSION['membre']['etat'] == 1) {
                    header('Location: vue/vue_admin/v_indexadmin.php'); // redirection vers index de l'admin si 'etat=1' 
                } else {
                    header('Location:' . $_SERVER["PHP_SELF"]); // redirection vers index en tant que membre
                }
            }
        }
    }

// formulaire de connexion
    $content .= '<form method="post" id="connexion_membre" >
		<input type="text" name="pseudo" placeholder="Pseudo" id="pseudo" value="' . $pseudo . '"/><br />
		<input type="password" name="mdp" placeholder="Mot de passe" id="mdp" value="' . $mdp . '" /><br />
		<input type="submit" name="submitMembre" value="Se connecter" id="submit" />
                ' . $erreurmessage . '
                <div id="lien">
                    <a href="vue/mdpperdu.php">Mot de passe oublié ?</a>
                    <a href="vue/inscription.php">S\'inscrire</a>
                </div>
	</form>';

//------------------------------------------------------------------------------------------------------
} else {
    if ($_SESSION['membre']['etat'] == 1) {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue/vue_admin/v_indexadmin.php"><span class="fa fa-cog"></span> &nbsp; Accès au back-office</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue/inclusions/deconnexion.php"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    } else {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue/parametresmembre.php"><span class="fa fa-cog"></span> &nbsp; Mes paramètres</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue/inclusions/deconnexion.php"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    }
}

//----------------------------------------------------------------------------------------------------------
//cube forum

$requeteforum = $pdo->query('SELECT titre, description FROM sujetforum WHERE activer=1 ORDER BY id_sujet DESC LIMIT 0,1 ');

$donneesforum = $requeteforum->fetch(PDO::FETCH_ASSOC);

$contenutitreforum .= $donneesforum['titre'];
$contenudescriptionforum .= $donneesforum['description'];

//---------------------------------------------------------------------------------------------------------
//cube commentaire

$requetecommentaire = $pdo->query('SELECT pseudo, message, DATE_FORMAT(date_commentaire,  "Le %d/%m/%Y à %h:%i:%s") AS date_commentaire FROM commentaires WHERE activer=1 ORDER BY id_commentaire DESC LIMIT 0,1 ');

$donneescommentaire = $requetecommentaire->fetch(PDO::FETCH_ASSOC);

$contenudatecommentaire .= $donneescommentaire['date_commentaire'];
$contenupseudocommentaire .= $donneescommentaire['pseudo'];
$contenucommentaire .= $donneescommentaire['message'];

//---------------------------------------------------------------------------------------------------------
//cube article

$requetearticle = $pdo->query('SELECT titre,DATE_FORMAT(date_article, "Le %d/%m/%Y à %h:%i:%s") AS date_article FROM article ORDER BY id_article DESC LIMIT 0,1 ');

$donneesarticle = $requetearticle->fetch(PDO::FETCH_ASSOC);

/* $contenuphotoarticle .= "src=\"vue/img/imgArticle/$donneesarticle[photo_article]\" "; */
$contenudatearticle .= $donneesarticle['date_article'];
$contenutitrearticle .= $donneesarticle['titre'];


$resultlien = $pdo->query("SELECT titre FROM article ORDER BY titre");
$compte = 0;
while ($donneeslien = $resultlien->fetch(PDO::FETCH_ASSOC)) {
    if ($donneesarticle['titre'] != $donneeslien['titre']) {
        $compte += 1;
    } else {
        break;
    }
}
$compte += 1;

//---------------------------------------------------------------------------------------------------------
//cube album photo

$requetealbumphoto = $pdo->query('SELECT nom_image FROM image ORDER BY id_image DESC LIMIT 0,4 ');

while ($donneesalbumphoto = $requetealbumphoto->fetch(PDO::FETCH_ASSOC)) {
    $contenualbumphoto .= "<img src=\"vue/img/imgAlbumP/$donneesalbumphoto[nom_image]\" alt=\"album photos\" />";
}

//---------------------------------------------------------------------------------------------------------
//cube kerala

$resultlienkerala = $pdo->query("SELECT titre FROM article ORDER BY titre");
$comptekerala = 0;
while ($donneeslienkerala = $resultlienkerala->fetch(PDO::FETCH_ASSOC)) {
    if ($donneeslienkerala['titre'] != 'Le Kérala') {
        $comptekerala += 1;
    } else {
        break;
    }
}
$comptekerala += 1;

//---------------------------------------------------------------------------------------------------------
//cube karnataka

$resultlienkarnataka = $pdo->query("SELECT titre FROM article ORDER BY titre");
$comptekarnataka = 0;
while ($donneeslienkarnataka = $resultlienkarnataka->fetch(PDO::FETCH_ASSOC)) {
    if ($donneeslienkarnataka['titre'] != 'Le Karnataka') {
        $comptekarnataka += 1;
    } else {
        break;
    }
}
$comptekarnataka += 1;

//---------------------------------------------------------------------------------------------------------
//cube tamil nadu

$resultlientamil = $pdo->query("SELECT titre FROM article ORDER BY titre");
$comptetamil = 0;
while ($donneeslientamil = $resultlientamil->fetch(PDO::FETCH_ASSOC)) {
    if ($donneeslientamil['titre'] != 'Le Tamil Nadu') {
        $comptetamil += 1;
    } else {
        break;
    }
}
$comptetamil += 1;

