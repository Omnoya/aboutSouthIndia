<?php

require_once ('../modele/fonction.php');

$contenusaisiecommentaire = "";
$contenunbrecommentaire = "";
$focus = "";
$href = "";
$focustext = "";
$hreftext = "";
$css = "";

//variable qui sert à afficher les commentaires
$cube5 = "";
//variables qui serviront à la pagination
$flechedroite = "";
$flechegauche = "";

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "";
$chemin2 = "../";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "actif";
$position7 = "inactif";

$pseudo = "";
$mdp = "";
$erreurmessage = "";
$pseudocommentaire = "";
$message = "";
$erreurpseudocommentaire = "";
$erreurcommentaire = "";
$commentaireposte = "";

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

//on définit une variable qui déterminera le contenu
$contenupseudo = "";
$contenuavis = "";

$contenupseudo .= "<a id=\"champpseudo\" ";
//on récupère l'url de la page actuelle
$pageactuelle = $_SERVER['REQUEST_URI'];
if (stripos($pageactuelle, "?") == 0) {
    //$contenupseudo .= "<a id=\"champpseudo\" ";
    $href = "href=\"".$pageactuelle."?statut=focus&amp;style=fixe#ancreformulaire\"";
    $contenupseudo .= $href;
} else {
    $href = "href=\"".$pageactuelle."&amp;statut=focus&amp;style=fixe#ancreformulaire\"";
    $contenupseudo .= $href;
}
$contenupseudo .= " >Votre pseudo...</a>";

$contenuavis .= "<a id=\"champcommentaire\" ";
$pageactuelle = $_SERVER['REQUEST_URI'];
if (stripos($pageactuelle, "?") == 0) {
    $href = "href=\"".$pageactuelle."?statut=focustext&amp;style=fixe#ancreformulaire\"";
    $contenuavis .= $href;
} else {
    $href = "href=\"" . $pageactuelle . "&amp;statut=focustext&amp;style=fixe#ancreformulaire\"";
    $contenuavis .= $href;
}
$contenuavis .= " >Votre message...</a>";

//------------------------------------------------------------------------------------------------------
//Si on poste un commentaire
if (isset($_POST['boutonmessage'])) {
    $css = "fixe";

    //on vide les variables
    $contenupseudo = "";
    $contenuavis = "";
    if (isset($_POST['pseudocommentaire'])) {
        $pseudocommentaire .= htmlspecialchars(trim($_POST['pseudocommentaire']));
        $message .= htmlspecialchars(trim($_POST['message']));
    }
    //on vide les variables pour remplacer les liens par des input
    $contenupseudo = '<input type="text" name="pseudocommentaire" placeholder="Pseudo" id="champpseudo" value="' . $pseudocommentaire . '"#ancreformulaire ' . $focus . '/>';
    $contenuavis .= '
<textarea name="message" placeholder="Votre message..." id="champcommentaire"#ancreformtext ' . $focustext . '>' . $message . '</textarea>';


//On vérifie les champs, et on assigne une valeur aux variables qui serviront à afficher les erreurs
    if (empty($pseudocommentaire)) {
        $erreurpseudocommentaire .= "<div class=\"contenu_erreur\"><p>Veuillez saisir le pseudo.</p></div>";
    } elseif (empty($message)) {
        $erreurcommentaire .= "<div class=\"contenu_erreur\"><p>Veuillez saisir le message.</p></div>";
    } else {
//Sinon, on enregistre les données et on effectue une redirection vers une autre page, en envoyant une donnée via l'url
        $result = $pdo->prepare("INSERT INTO commentaires (pseudo, message) VALUES (:pseudo, :message)");
        $result->bindValue(':pseudo', $pseudocommentaire);
        $result->bindValue(':message', $message);
        $result->execute();

        header("location: commentaire.php?action=commentaireenvoye");
    }
}



//si on clique sur un des supposés input
if (isset($_GET['style']) AND $_GET['style'] == "fixe") {
    //on vide la variable
    $contenupseudo = "";
    $contenuavis = "";
    //je change le lien en input
    if (isset($_GET['statut']) AND $_GET['statut'] == "focus") {
        $focus = "autofocus";
    } elseif (isset($_GET['statut']) AND $_GET['statut'] == "focustext") {
        $focustext = "autofocus";
    }

    $contenupseudo = '<input type="text" name="pseudocommentaire" placeholder="Pseudo" id="champpseudo" value="' . $pseudocommentaire . '"#ancreformulaire ' . $focus . '/>';
    $contenuavis .= '
<textarea name="message" placeholder="Votre message..." id="champcommentaire"#ancreformtext ' . $focustext . '>' . $message . '</textarea>';
    $css = "fixe";
}
//}
//---------------------------------------------------------------
//On affiche le formulaire afin de poster un message
$contenusaisiecommentaire .= ' <form method="post" >' .
        $contenupseudo . '' . $erreurpseudocommentaire . '
                                    ' . $contenuavis . '' . $erreurcommentaire . '
                                    <input type="submit" value="Envoyer" id="submitcommentaire" name="boutonmessage"/>
                                </form>';

//------------------------------------------------------------------------
//Si le message a été posté, on récupère la donnée qui confirme l'envoi du message grâce à l'url
if (isset($_GET['action']) AND $_GET['action'] == "commentaireenvoye") {
    $css = "fixe";
    $contenusaisiecommentaire = "";
    $commentaireposte .= '<div id="verification"><p>votre message a bien été enregistré. Il sera affiché dans les 48h.</p></div>';

    // notification par mail
    // Préparation du mail contenant le lien d'activation
    $email = "omnoya@free.fr";
    $sujet = "Nouveau commentaire a valider";
    $entete = "From: omnoya@free.fr";

// Le lien d'activation est composé du login(log) et de la clé(cle)
    $message = 'Un nouveau commentaire en attente de validation
        
    http://localhost/projetv2/index.php ';

    mail($email, $sujet, $message, $entete);
    //Et ensuite, on rafraîchit la page, et on effetcue une redirection vers la page d'acceuil des commentaires
    header("refresh:5; url=commentaire.php");
}

//------------------------------------------------------------------------
//Si on se trouve à la page une  ou plus
if (isset($_GET['pagedesc']) AND $_GET['pagedesc'] > 0) {
    //on vide les variables
    $flechedroite = "";
    $flechegauche = "";
    $cube5 = "";

    //on récupère les informations via l'url
    $pagedesc = $_GET['pagedesc'];
    $debutselection = $_GET['debutselection'];
    $nombretotalentree = $_GET['nombretotalentree'];

    $reponse = $pdo->query('SELECT pseudo, message, DATE_FORMAT(date_commentaire, "Le %d/%m/%Y à %h:%i:%s") AS date_commentaire FROM commentaires WHERE activer=1 ORDER BY id_commentaire DESC LIMIT ' . $debutselection . ',1');

    $comptecommentaire = $pdo->query('SELECT * FROM commentaires WHERE activer=1');
    $contenunbrecommentaire .= '<div id="nbre_commentaire"><p id="taillenbre">' . $comptecommentaire->rowCount() . '</p><p>commentaire(s) actuellement.</p></div>';
//---------------------------------------------------------------------
    $donnees = $reponse->fetch(PDO::FETCH_ASSOC);

    $cube5 .= "<div id=\"cube5\">";
    $cube5 .= "<div id=\"auteur\"><h1>Posté par : $donnees[pseudo]</h1></div>";
    $cube5 .= "<div id=\"message\"><p>$donnees[message]<p></div>";
    $cube5 .= "<div id=\"date\"><p>$donnees[date_commentaire]<p></div>";
    $cube5 .= "</div>";

    //on génère le lien vers la page précédente
    $_GET['pagedesc'] -= 1;
    $_GET['debutselection'] -= 1;
    $flechegauche .= "<div id=\"cube4\">";
    $flechegauche .= "<a href=\"?pagedesc=" . $_GET['pagedesc'] . "&amp;debutselection=" . $_GET['debutselection'] . "&amp;nombretotalentree=$nombretotalentree\">";
    $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
    $flechegauche .= "</a>";
    $flechegauche .= "</div>";

    //on affiche le lien vers la page suivante
    //Si on se trouve à la dernière entrée, on ne génère aucun lien
    $debutselection += 1;
    if ($debutselection < $nombretotalentree) {
        //on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
        //on commence par le numero et le type de page
        $pagedesc += 1;
        //la valeur de début de délection dans la base de données
        //Puis on génère le lien vers la page suivante
        $flechedroite .= "<div id=\"cube6\">";
        $flechedroite .= "<a href=\"?pagedesc=$pagedesc&amp;debutselection=$debutselection&amp;nombretotalentree=$nombretotalentree\" >";
        $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
        $flechedroite .= "</a>";
        $flechedroite .= "</div>";
    } else {
        //Sinon, il peut revenir à la première entrée
        $flechedroite .= "<div id=\"cube6\">";
        $flechedroite .= "<a href=\"commentaire.php\">";
        $flechedroite .= "<span class=\"fa fa-rotate-right\"></span>";
        $flechedroite .= "</a>";
        $flechedroite .= "</div>";
    }
} elseif (isset($_GET['pageasc']) AND $_GET['pageasc'] > 0) {
    //on vide les variables
    $flechedroite = "";
    $flechegauche = "";
    $cube5 = "";

    //on récupère les informations via l'url
    $pageasc = $_GET['pageasc'];
    $debutselection = $_GET['debutselection'];
    $nombretotalentree = $_GET['nombretotalentree'];

    $reponse = $pdo->query('SELECT pseudo, message, DATE_FORMAT(date_commentaire, "Le %d/%m/%Y à %h:%i:%s") AS date_commentaire FROM commentaires WHERE activer=1 ORDER BY id_commentaire DESC LIMIT ' . $debutselection . ',1');

    $comptecommentaire = $pdo->query('SELECT * FROM commentaires WHERE activer=1');
    $contenunbrecommentaire .= '<div id="nbre_commentaire"><p id="taillenbre">' . $comptecommentaire->rowCount() . '</p><p>commentaire(s) actuellement.</p></div>';
//---------------------------------------------------------------------
    $donnees = $reponse->fetch(PDO::FETCH_ASSOC);


    $cube5 .= "<div id=\"cube5\">";
    $cube5 .= "<div id=\"auteur\"><h1>Posté par : $donnees[pseudo]</h1></div>";
    $cube5 .= "<div id=\"message\"><p>$donnees[message]<p></div>";
    $cube5 .= "<div id=\"date\"><p>$donnees[date_commentaire]<p></div>";
    $cube5 .= "</div>";

    //on génère le lien vers la page précédente
    $_GET['pageasc'] -= 1;
    $_GET['debutselection'] -= 1;
    $flechedroite .= "<div id=\"cube6\">";
    $flechedroite .= "<a href=\"?pageasc=" . $_GET['pageasc'] . "&amp;debutselection=" . $_GET['debutselection'] . "&amp;nombretotalentree=$nombretotalentree\">";
    $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
    $flechedroite .= "</a>";
    $flechedroite .= "</div>";

    //on affiche le lien vers la page suivante
    //Si on se trouve à la dernière entrée, on ne génère aucun lien
    $debutselection += 1;
    if ($debutselection < $nombretotalentree) {
        //on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
        //on commence par le numero et le type de page
        $pageasc += 1;
        //la valeur de début de délection dans la base de données
        //Puis on génère le lien vers la page suivante
        $flechegauche .= "<div id=\"cube4\">";
        $flechegauche .= "<a href=\"?pageasc=$pageasc&amp;debutselection=$debutselection&amp;nombretotalentree=$nombretotalentree\" >";
        $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
        $flechegauche .= "</a>";
        $flechegauche .= "</div>";
    } else {
        //Sinon, il peut revenir à la première entrée
        $flechegauche .= "<div id=\"cube4\">";
        $flechegauche .= "<a href=\"commentaire.php\">";
        $flechegauche .= "<span class=\"fa fa-rotate-left\"></span>";
        $flechegauche .= "</a>";
        $flechegauche .= "</div>";
    }
} else {
    $reponse = $pdo->query('SELECT pseudo, message, DATE_FORMAT(date_commentaire, "Le %d/%m/%Y à %h:%i:%s") AS date_commentaire FROM commentaires WHERE activer=1 ORDER BY id_commentaire DESC LIMIT 0,1');

    $comptecommentaire = $pdo->query('SELECT * FROM commentaires WHERE activer=1');
    $contenunbrecommentaire .= '<div id="nbre_commentaire"><p id="taillenbre">' . $comptecommentaire->rowCount() . '</p><p>commentaire(s) actuellement.</p></div>';
//---------------------------------------------------------------------
    $donnees = $reponse->fetch(PDO::FETCH_ASSOC);

//$contenucommentaire .= '<div class="commentaires">';
//$contenucommentaire .= "<div class=\"pseudo\">Pseudo :  <span class=\"floatRight\">Date : </span></div>";
//$contenucommentaire .= "<div id=\"contenu_message\">Message : </div>";
//$contenudatecommentaire .= "<div id=\"contenu_date\">Message : $donnees[date_commentaire]</div>";
//$contenucommentaire .= '</div><br/>';

    $cube5 .= "<div id=\"cube5\">";
    $cube5 .= "<div id=\"auteur\"><h1>Posté par : $donnees[pseudo]</h1></div>";
    $cube5 .= "<div id=\"message\"><p>$donnees[message]<p></div>";
    $cube5 .= "<div id=\"date\"><p>$donnees[date_commentaire]<p></div>";
    $cube5 .= "</div>";

//Si il y a plus d'une entrée
    if ($comptecommentaire->rowCount() > 1) {
        //on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
        //on commence par le numero et le type de page
        $pagedesc = 1;
        //la valeur de début de délection dans la base de données
        $debutselection = 1;
        //le nombre total d'entrées
        $nombretotalentree = $comptecommentaire->rowCount();
        //Puis on génère le lien vers la page suivante
        $flechedroite .= "<div id=\"cube6\">";
        $flechedroite .= "<a href=\"?pagedesc=$pagedesc&amp;debutselection=$debutselection&amp;nombretotalentree=$nombretotalentree\">";
        $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
        $flechedroite .= "</a>";
        $flechedroite .= "</div>";

        //Et le lien vers le plus ancien commentaire
        $flechegauche .= "<div id=\"cube4\">";
        $flechegauche .= "<a href=\"?pageasc=$pagedesc&amp;debutselection=$debutselection&amp;nombretotalentree=$nombretotalentree\" >";
        $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
        $flechegauche .= "</a>";
        $flechegauche .= "</div>";
    }
}
?>

