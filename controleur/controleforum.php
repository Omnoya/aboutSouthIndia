<?php

require_once ('../modele/fonction.php');

$contentalerteconnexionforum = "";
$contenunbresujetforum = "";

$contenuenregistrementmessage = "";
$contenuajoutmessageforum = "";
$messageposte = "";
$message = "";
$id = "";

$href = "";
$focustext = "";
$css = "";

$cube1 = "";
$cube2 = "";
$cube3 = "";


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
$position4 = "actif";
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

    if (isset($_GET['action']) AND $_GET['action'] == "deconnexion") {
        $contentalerteconnexionforum .= '<div id="alerte_connexion">';
        $contentalerteconnexionforum .= '<div id="titre_alerte"><h1>Forum :</h1></div>';
        $contentalerteconnexionforum .= '<div id="verification_connexion"><p>Vous êtes maintenant déconnecté</p></div>';
        $contentalerteconnexionforum .= '</div>';
    } else {
        $contentalerteconnexionforum .= '<div id="alerte_connexion">';
        $contentalerteconnexionforum .= '<div id="titre_alerte"><h1>Forum :</h1></div>';
        $contentalerteconnexionforum .= '<div id="verification_connexion"><p>Vous devez être connecté pour accéder au forum. Pas encore membre ? Inscrivez-vous <a href="inscription.php">ici</a></p></div>';
        $contentalerteconnexionforum .= '</div>';
    }
//------------------------------------------------------------------------------------------------------
} else {
    if ($_SESSION['membre']['etat'] == 1) {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue_admin/v_indexadmin.php"><span class="fa fa-cog"></span> &nbsp; Accès au back-office</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="?action=deconnexion"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    } else {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="parametresmembre.php"><span class="fa fa-cog"></span> &nbsp; Mes paramètres</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="?action=deconnexion"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    }

//----------------------------------------------------------------

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


        $reponse = $pdo->query('SELECT * FROM sujetforum WHERE activer=1 ORDER BY id_sujet DESC LIMIT ' . $debutselection . ',1');

        //--------------------------------------------------------------------------------------
        $comptesujetforum = $pdo->query('SELECT * FROM sujetforum WHERE activer=1');
        $contenunbresujetforum .= '<div id="nbre_sujetforum"><p id="taillenbre">' . $comptesujetforum->rowCount() . '</p><p>sujet(s) actuellement.</p></div>';
        $contenunbresujetforum .= '<a id="lien_creer_sujet" href="ajoutsujetforum.php"><div id="creer_sujet"><p>Créer une nouvelle discussion</p></div></a>';
//--------------------------------------------------------------------------------------

        $sujetforum = $reponse->fetch(PDO::FETCH_ASSOC);
// debug($sujetforum);

        $cube5 .= "<div id=\"cube5\">";
        $cube5 .= "<a id=\"lien_sujet\" href=\"affichagemessageforum.php?id_sujet=$sujetforum[id_sujet]\"><div id=\"titre_sujetforum\"><h1>$sujetforum[titre]</h1></div></a>";
        $cube5 .= "<div id=\"description\"><p>$sujetforum[description]<p></div>";
        $cube5 .= "<div id=\"footer_sujetforum\"></div>";
        $cube5 .= "</div>";
        $id .= $sujetforum['id_sujet'];

//        $contenuforum .= '<div class="sujet_forum">';
//        $contenuforum .= "<div class=\"sujet\">";
//        $contenuforum .= "<h1><a href=\"affichageMessageForum.php?id_sujet=$sujetforum[id_sujet]\">$sujetforum[titre]</a></h1>";
//        $contenuforum .= "<span class=\"sujet_description\"> $sujetforum[description]</span></div>";
//        //$content .= "<div class=\"message\">$sujetforum[description]</div>";
//        $contenuforum .= "<a href=\"ajoutMessageForum.php?id_sujet=$sujetforum[id_sujet]\">deposer un message</a>";
//        $contenuforum .= '</div><hr />';
//    // Puis on fait une boucle pour écrire les liens vers chacune des pages
//    $contenuforum .= 'Page : ';
//    for ($i = 1; $i <= $nb_pages; $i++) {
//        $contenuforum .= '<a href="forum.php?page=' . $i . '">' . $i . '</a> ';
//    }
//on génère le lien vers la page précédente
        $_GET['pagedesc'] -= 1;
        $_GET['debutselection'] -= 1;
        $flechegauche .= "<div id=\"cube4\">";
        $flechegauche .= "<a href=\"?pagedesc=" . $_GET['pagedesc'] . "&debutselection=" . $_GET['debutselection'] . "&nombretotalentree=$nombretotalentree#ancre\">";
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
//la valeur de début de détection dans la base de données
//Puis on génère le lien vers la page suivante
            $flechedroite .= "<div id=\"cube6\">";
            $flechedroite .= "<a href=\"?pagedesc=$pagedesc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
            $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
            $flechedroite .= "</a>";
            $flechedroite .= "</div>";
        } else {
//Sinon, il peut revenir à la première entrée
            $flechedroite .= "<div id=\"cube6\">";
            $flechedroite .= "<a href=\"forum.php#ancre\">";
            $flechedroite .= "<span class=\"fa fa-rotate-right\"></span>";
            $flechedroite .= "</a>";
            $flechedroite .= "</div>";
        }

//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//on va enregistrer dans la table messageForum id_sujet qu'on récupère de le BDD $sujetforum[id_sujet], le membre de la session $_SESSION, le message du formulaire $_POST[message] et la fonction NOW() pour récupérer la date et l'heure.








        /*
          $strMessage = trim($_POST['message']);
          $strPseudo = trim($_POST['pseudo']);

          $result = $pdo->prepare("INSERT INTO commentaires (pseudo, message) VALUES (:pseudo, :message)");
          $result->bindValue(':pseudo', $strPseudo);
          $result->bindValue(':message', $strMessage);
          $result->execute();
         * 
         * $intIdSujet = (int)$_GET["id_sujet"];
         */


//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
    } elseif (isset($_GET['pageasc']) AND $_GET['pageasc'] > 0 AND $_GET['debutselection'] >= 0) {
        //on vide les variables
        $flechedroite = "";
        $flechegauche = "";
        $cube5 = "";

        //on récupère les informations via l'url
        $pageasc = $_GET['pageasc'];
        $debutselection = $_GET['debutselection'];
        $nombretotalentree = $_GET['nombretotalentree'];

        $reponse = $pdo->query('SELECT * FROM sujetforum WHERE activer=1 LIMIT ' . $debutselection . ',1');

        //--------------------------------------------------------------------------------------
        $comptesujetforum = $pdo->query('SELECT * FROM sujetforum WHERE activer=1');
        $contenunbresujetforum .= '<div id="nbre_sujetforum"><p id="taillenbre">' . $comptesujetforum->rowCount() . '</p><p>sujet(s) actuellement.</p></div>';
        $contenunbresujetforum .= '<a id="lien_creer_sujet" href="ajoutsujetforum.php"><div id="creer_sujet"><p>Créer une nouvelle discussion</p></div></a>';
//--------------------------------------------------------------------------------------


        $sujetforum = $reponse->fetch(PDO::FETCH_ASSOC);

        $cube5 .= "<div id=\"cube5\">";
        $cube5 .= "<a id=\"lien_sujet\" href=\"affichagemessageforum.php?id_sujet=$sujetforum[id_sujet]\"><div id=\"titre_sujetforum\"><h1>$sujetforum[titre]</h1></div></a>";
        $cube5 .= "<div id=\"description\"><p>$sujetforum[description]<p></div>";
        $cube5 .= "<div id=\"footer_sujetforum\"></div>";
        $cube5 .= "</div>";
        $id .= $sujetforum['id_sujet'];

//on génère le lien vers la page précédente
        $_GET['pageasc'] -= 1;
        $_GET['debutselection'] -= 1;
        $flechedroite .= "<div id=\"cube6\">";
        $flechedroite .= "<a href=\"?pageasc=" . $_GET['pageasc'] . "&debutselection=" . $_GET['debutselection'] . "&nombretotalentree=$nombretotalentree#ancre\">";
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
            $flechegauche .= "<a href=\"?pageasc=$pageasc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
            $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
            $flechegauche .= "</a>";
            $flechegauche .= "</div>";
        } else {
            //Sinon, il peut revenir à la première entrée
            $flechegauche .= "<div id=\"cube4\">";
            $flechegauche .= "<a href=\"forum.php#ancre\">";
            $flechegauche .= "<span class=\"fa fa-rotate-left\"></span>";
            $flechegauche .= "</a>";
            $flechegauche .= "</div>";
        }

        //------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
    } else {

        $reponse = $pdo->query('SELECT * FROM sujetforum WHERE activer=1 ORDER BY id_sujet DESC LIMIT 0,1');


//--------------------------------------------------------------------------------------
        $comptesujetforum = $pdo->query('SELECT * FROM sujetforum WHERE activer=1');
        $contenunbresujetforum .= '<div id="nbre_sujetforum"><p id="taillenbre">' . $comptesujetforum->rowCount() . '</p><p>sujet(s) actuellement.</p></div>';
        $contenunbresujetforum .= '<a id="lien_creer_sujet" href="ajoutsujetforum.php"><div id="creer_sujet"><p>Créer une nouvelle discussion</p></div></a>';
//--------------------------------------------------------------------------------------

        $sujetforum = $reponse->fetch(PDO::FETCH_ASSOC);
$id .= $sujetforum['id_sujet'];
        $cube5 .= "<div id=\"cube5\">";
        $cube5 .= "<a id=\"lien_sujet\" href=\"affichagemessageforum.php?id_sujet=$id\"><div id=\"titre_sujetforum\"><h1>$sujetforum[titre]</h1></div></a>";
        $cube5 .= "<div id=\"description\"><p>$sujetforum[description]<p></div>";
        $cube5 .= "<div id=\"footer_sujetforum\"></div>";
        $cube5 .= "</div>";
        

//Si il y a plus d'une entrée
        if ($comptesujetforum->rowCount() > 1) {
            //on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
            //on commence par le numero et le type de page
            $pagedesc = 1;
            //la valeur de début de délection dans la base de données
            $debutselection = 1;
            //le nombre total d'entrées
            $nombretotalentree = $comptesujetforum->rowCount();
            //Puis on génère le lien vers la page suivante
            $flechedroite .= "<div id=\"cube6\">";
            $flechedroite .= "<a href=\"?pagedesc=$pagedesc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
            $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
            $flechedroite .= "</a>";
            $flechedroite .= "</div>";

            //Et le lien vers le plus ancien commentaire
            //On enlève la dernière entrée, qui est en fait celle qui est déjà affichée sur la page d'acceuil
            $nombretotalentree -= 1;

            $flechegauche .= "<div id=\"cube4\">";
            $flechegauche .= "<a href=\"?pageasc=$pagedesc&debutselection=0&nombretotalentree=$nombretotalentree#ancre\">";
            $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
            $flechegauche .= "</a>";
            $flechegauche .= "</div>";
        }

//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
    }

    //affichage des 3 premières cubes lorsque le membre est connecté

    $cube1 .= "<div id=\"cube1\">";
    $cube1 .= "<div id=\"smileface\"><span class=\"fa fa-smile-o\"></span></div>";
    $cube1 .= "<div id=\"contenucube1\"><p>DONNEZ VOTRE AVIS SUR LE SUJET</p></div>";
    $cube1 .= "<div id=\"flechecube1\"><span class=\"fa fa-caret-right\"></span></div>";
    $cube1 .= "</div>";



    

    $cube3 .= "<div id=\"cube3\">$contenunbresujetforum</div>";
    


    if (isset($_GET['action']) AND $_GET['action'] == "deconnexion") {
        session_destroy();
        header('Location: forum.php?action=deconnexion');
    }


//----------------------------------------------------------------
    //on définit une variable qui déterminera le contenu

    $contenuavis = "";

    $contenuavis .= "<a id=\"champmessage\"";
    $pageactuelle = $_SERVER['REQUEST_URI'];
    if (stripos($pageactuelle, "?") == 0) {
        $href = 'href="' . $pageactuelle . '?statut=focustext&style=fixe#ancreformulaire"';
        $contenuavis .= $href;
    } else {
        $href = 'href="' . $pageactuelle . '&statut=focustext&style=fixe#ancreformulaire"';
        $contenuavis .= $href;
    }
    $contenuavis .= ">Votre avis...</a>";

//si on clique sur un des supposés input
    if (isset($_GET['style']) AND $_GET['style'] == "fixe") {
        //on vide la variable
        $contenuavis = "";
        //je change le lien en input
        if (isset($_GET['statut']) AND $_GET['statut'] == "focus") {
            $focus = "autofocus";
        } elseif (isset($_GET['statut']) AND $_GET['statut'] == "focustext") {
            $focustext = "autofocus";
        }

        $contenuavis .= '
<textarea name="message" placeholder="Votre message..." id="champmessage"#ancreformtext ' . $focustext . '>' . $message . '</textarea>';
        $css = "fixe";
    }
//}
//---------------------------------------------------------------


//Si le message a été posté, on récupère la donnée qui confirme l'envoi du message grâce à l'url
//    if (isset($_GET['action']) AND $_GET['action'] == "commentaireenvoye") {
//        $css = "fixe";
//        $contenusaisiecommentaire = "";
//        $commentaireposte .= '<div id="verification"><p>votre message a bien été enregistré. Il sera affiché dans les 48h.</p></div>';
//        //Et ensuite, on rafraîchit la page, et on effetcue une redirection vers la page d'acceuil des commentaires
//        header("refresh:5; url=commentaire.php");
//    }
//variable qui servira à la redirection vers la page sur laquelle on se trouvait lors de l'ajout du message
    $pagecourante = $_SERVER['REQUEST_URI'];
    if (isset($_POST['envoyermessage'])) {
        $css = "fixe";
        if (isset($_POST['message'])) {
            $message .= $_POST['message'];
        }
        if (empty($_POST['message'])) {
            $contenuenregistrementmessage .= '<div class="contenu_erreur"><p>Veuillez saisir le message</p></div>';
        } else {
            $strMessage = htmlspecialchars(trim($_POST['message']));
            $result = $pdo->query("INSERT INTO messageForum (id_sujet, id_membre, message, date_message) VALUES ('" . $id ."', '" . $_SESSION['membre']['id_membre'] . "', '" . $strMessage . "', NOW())");
            header("location: $pagecourante&action=messageenvoye");
        }
    }
    
//On affiche le formulaire afin de poster un message
    $contenuajoutmessageforum .= ' <form method="post" action="">' . $contenuavis . '' . $contenuenregistrementmessage . '
                                    <input type="submit" name="envoyermessage" id="submitmessage" />
                                </form>';
    
//Si le message a été posté, on récupère la donnée qui confirme l'envoi du message grâce à l'url
    if (isset($_GET['action']) AND $_GET['action'] == "messageenvoye") {
        $css = "fixe";
        $contenuajoutmessageforum = "";
        $messageposte .= '<div id="verification"><p>votre message a bien été enregistré. Il sera affiché dans les 48h.</p></div>';
        
        // notification par mail
    // Préparation du mail contenant le lien d'activation
    $email = "omnoya@free.fr";
    $sujet = "Nouveau message a valider sur le forum";
    $entete = "From: omnoya@free.fr";

// Le lien d'activation est composé du login(log) et de la clé(cle)
    $message = 'Un nouveau message sur le forum en attente de validation
        
    http://localhost/projetv2/index.php ';

    mail($email, $sujet, $message, $entete);
        
        //Et ensuite, on rafraîchit la page, et on effetcue une redirection vers la page d'acceuil des commentaires
        //on supprime l'action de l'url
        $pagecourante = str_replace("&action=messageenvoye", "", $pagecourante);
        header("refresh:5; url=$pagecourante");
    }
    
    

$cube2 .= "<div id=\"fondcube$css\">";
    $cube2 .= "<span class=\"fa fa-comment iconecube fa-5x\"></span>";
    $cube2 .= "</div>";
    $cube2 .= "<div id=\"cube2$css\">";
    $cube2 .= "<div id=\"formulaire_forum\">";
    $cube2 .= $contenuajoutmessageforum;
    $cube2 .= $messageposte;
    $cube2 .= "</div>";
    $cube2 .= "</div>";
}
?>