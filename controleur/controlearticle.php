<?php

require_once ('../modele/fonction.php');

$contenutitre = "";
$contenuarticle = "";
$contenuphoto = "";
$contenudate = "";

$lienarticle = "";
$compte = 0;

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "";
$chemin2 = "../";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "actif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "inactif";
$position7 = "inactif";

$pseudo = "";
$mdp = "";
$erreurmessage = "";

if (!membreEstConnecte()) {

    if (isset($_POST["submitMembre"])) {
        //On récupère la saisie
        $pseudo .= $_POST["pseudo"];
        $mdp .= $_POST["mdp"];
        //On vérifie si la saisie est correct, à savoir qu'elle correspond au contenu de la base de données
        //On commence par récupérer les données de la BDD là où le pseudo correspond
        $resultat = $pdo->prepare("SELECT * FROM membres WHERE pseudo=:pseudo");
        $resultat->bindValue(":pseudo", trim($_POST["pseudo"]));
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

            $oPrepare = recupererdonnees_membre($_POST["pseudo"], $_POST["mdp"]);
            $oPrepare->execute();
            if ($oPrepare->rowCount() >= 1) {      //la fonction PDOStatement::rowcount() retourne le nombre de lignes affectées par le dernier appel à la fonction PDOStatement::execute()
                // Récupération de la ligne trouvée :
                $personne = $oPrepare->fetch(PDO::FETCH_ASSOC);
                // Supression du mot de passe avant le passage dans la session :
                unset($personne["mdp"]);

                $_SESSION['membre'] = $personne;

                if ($_SESSION['membre']['etat'] == 1) {
                    header('Location: vue_admin/v_indexadmin.php'); // redirection vers index de l'admin si 'etat=1' 
                } else {
                    header('Location:' . $_SERVER["PHP_SELF"]); // redirection vers index en tant que membre
                }
            }
        }
    }

    // formulaire de connexion
    $content .= '<form method="post" id="connexion_membre">
		<input type="text" name="pseudo" placeholder="Pseudo" id="pseudo" value="' . $pseudo . '"/><br />
		<input type="password" name="mdp" placeholder="Mot de passe" id="mdp" value="' . $mdp . '" /><br />
		<input type="submit" name="submitMembre" value="Se connecter" id="submit" />
                ' . $erreurmessage . '
                <div id="lien">
                    <a href="mdpperdu.php">Mot de passe oublié ?</a>
                    <a href="inscription.php">S\'inscrire</a>
                </div>
	</form>';
//------------------------------------------------------------------------------------------------------
} else {
    if ($_SESSION['membre']['etat'] == 1) {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue_admin/v_indexadmin.php"><span class="fa fa-cog"></span> &nbsp; Accès au back-office</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="inclusions/deconnexion.php"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    } else {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="parametresmembre.php"><span class="fa fa-cog"></span> &nbsp; Mes paramètres</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="inclusions/deconnexion.php"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    }
}




//---------------------------------------------------------------------------------------------------------
//pagination
// on récupère le nb total d'article
$req = recupererdonnees("article");
$nbre_total_article = $req->rowCount();

$nbre_article_par_page = 1;  //nombre d'article qu'on veut par page

$nbre_pages_max_gauche_et_droite = 2;

$nbre_pages = ceil($nbre_total_article / $nbre_article_par_page); // on récupère le nb de page, ceil() arrondit au nombre supérieur
//savoir sur quelle page on se trouve
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page_num = $_GET['page'];
} else {
    $page_num = 1;
}

if ($page_num < 1) {
    $page_num = 1;
} elseif ($page_num > $nbre_pages) {
    $page_num = $nbre_pages;
}

$limit = 'LIMIT ' . ($page_num - 1) * $nbre_article_par_page . ',' . $nbre_article_par_page;

//cette requete sera utilisée plus tard
$sql = "SELECT id_article, titre, contenu, photo_article, date_article FROM article ORDER BY titre $limit";

$pagination = '';

if ($nbre_pages != 1) {
    if ($page_num > 1) {
        $previous = $page_num - 1;
        $pagination .= '<a class="lien_fleche" href="article.php?page=' . $previous . '"><span class="fa fa-caret-left"></span></a> &nbsp; &nbsp; ';

        for ($i = $page_num - $nbre_pages_max_gauche_et_droite; $i < $page_num; $i++) {
            if ($i > 0) {
                $pagination .= '<a class="lien_num" href="article.php?page=' . $i . '">' . $i . '</a> &nbsp;';
            }
        }
    }

    $pagination .= '<span class="active">' . $page_num . '</span>&nbsp;';
    for ($i = $page_num + 1; $i <= $nbre_pages; $i++) {
        $pagination .= '<a class="lien_num" href="article.php?page=' . $i . '">' . $i . '</a>';
        if ($i >= $page_num + $nbre_pages_max_gauche_et_droite) {
            break;
        }
    }
    if ($page_num != $nbre_pages) {
        $next = $page_num + 1;
        $pagination .= ' &nbsp; &nbsp; <a class="lien_fleche" href="article.php?page=' . $next . '"><span class="fa fa-caret-right"></span></a>';
    }
}

//---------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
$result = $pdo->query($sql);
while ($article = $result->fetch(PDO::FETCH_ASSOC)) {
    $contenutitre .= $article['titre'];
    $contenuarticle .= $article['contenu'];
    $contenuphoto .= "<img src=\"img/imgArticle/$article[photo_article]\" alt=\"Photo article\" />";
    $contenudate .= $article['date_article'];
}

//-----------------------------------------------------------------------------

$resultlien = $pdo->query("SELECT titre FROM article ORDER BY titre");

while ($donneeslien = $resultlien->fetch(PDO::FETCH_ASSOC)) {
        $compte += 1;
        
        $lienarticle .= '<div class="contenu_lien"><div class="lien_article"><a href="article.php?page='.$compte.'"><strong>'.$compte.') </strong>'.$donneeslien['titre'].'</a></div></div>';
        
    }
?>



