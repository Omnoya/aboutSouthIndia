<?php

require_once ('../modele/fonction.php');

$contentalerteconnexionforum = "";
$contenunbresujetforum = "";
$contenudescription = "";
$valeurtitre = "";
$valeurdescription = "";
$contenutitre = "";
$erreurtitre = "";

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

$boutonretour = "";

//variable qui sert à afficher les commentaires
//$cube5 = "";

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
        $id = $_SESSION['membre']['id_membre'];
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

        $comptesujetforum = recupererdonnees("sujetforum");
        $contenunbresujetforum .= '<div id="nbre_sujetforum"><p id="taillenbre">' . $comptesujetforum->rowCount() . '</p><p>sujet(s) actuellement.</p></div>';
        
//--------------------------------------------------------------------------------------

    //affichage des 3 premières cubes lorsque le membre est connecté

    $cube1 .= "<div id=\"cube1\">";
    $cube1 .= "<div id=\"smileface\"><span class=\"fa fa-smile-o\"></span></div>";
    $cube1 .= "<div id=\"contenucube1\"><p>CREER UNE NOUVELLE DISCUSSION</p></div>";
    $cube1 .= "<div id=\"flechecube1\"><span class=\"fa fa-caret-right\"></span></div>";
    $cube1 .= "</div>";



    

    $cube3 .= "<div id=\"cube3\">$contenunbresujetforum</div>";
    


    if (isset($_GET['action']) AND $_GET['action'] == "deconnexion") {
        session_destroy();
        header('Location: forum.php?action=deconnexion');
    }


$contenutitre .= '<input type="text" name="titre" id="titre" placeholder="Titre..."/>';

        $contenudescription .= '
<textarea name="description" placeholder="Votre message..." id="champdescription"#ancreformtext ' . $focustext . '>' . $message . '</textarea>';
    

//---------------------------------------------------------------

//variable qui servira à la redirection vers la page sur laquelle on se trouvait lors de l'ajout du message
    $pagecourante = $_SERVER['REQUEST_URI'];
    if (isset($_POST["envoyer"]) AND ! isset($_GET['action'])) {
    $valeurtitre .= $_POST['titre'];
    $valeurdescription .= $_POST['description'];
    if (empty($_POST['titre'])) {
        $erreurtitre = "<div class=\"contenu_erreur\"><p>Veuillez saisir le titre</p></div>";
    } elseif (empty($_POST['description'])) {
        $contenuenregistrementmessage .= "<div class=\"contenu_erreur\"><p>Veuillez saisir la description</p></div>";
    } else {

        // debug($_POST); 	debug($_FILES);
        $_POST['description'] = strip_tags(addslashes($_POST['description']));

        $pdo->exec("INSERT INTO sujetForum (titre, description, id_membre) VALUES ('$_POST[titre]', '$_POST[description]', '" . $_SESSION['membre']['id_membre'] . "')");
        header('refresh:0; url=ajoutsujetforum.php?statut=reussi#ancre');
    }
}
    
//On affiche le formulaire afin de poster un message
if(isset($_GET['statut']) AND $_GET['statut'] == 'reussi'){
    $contenuajoutmessageforum .= '<div id="verification"><p>votre sujet a bien été enregistré.</p></div>';
    
    // notification par mail
    // Préparation du mail contenant le lien d'activation
    $email = "omnoya@free.fr";
    $sujet = "Nouveau sujet sur le forum a valider";
    $entete = "From: omnoya@free.fr";

// Le lien d'activation est composé du login(log) et de la clé(cle)
    $message = 'Un nouveau sujet sur le forum en attente de validation
        
    http://localhost/projetv2/index.php ';

    mail($email, $sujet, $message, $entete);
    //Et ensuite, on rafraîchit la page, et on effetcue une redirection vers la page d'acceuil des commentaires
    
    header('refresh:5; url=ajoutsujetforum.php#ancre');
}else{
    $contenuajoutmessageforum .= ' <form method="post" action="' . $_SERVER['PHP_SELF']. '#ancre"><input type="text" name="titre" id="titre" placeholder="Titre..." value="'.$valeurtitre.'"/>'.$erreurtitre.'<textarea name="description" placeholder="Votre message..." id="champdescription"#ancreformtext >'.$valeurdescription.'</textarea>' . $contenuenregistrementmessage.'
                                    <input type="submit" name="envoyer" id="submitmessage" />
</form>';}
    
$boutonretour .= "<a href=\"forum.php\"><div id=\"lien_retour\"><span class=\"fa fa-reply\"></span> Retour vers les sujets du forum</div></a>";   

$cube2 .= "<div id=\"fondcubefixe\">";
    $cube2 .= "<span class=\"fa fa-comment iconecube fa-5x\"></span>";
    $cube2 .= "</div>";
    $cube2 .= "<div id=\"cube2fixe\">";
    $cube2 .= "<div id=\"formulaire_forum\">";
    $cube2 .= $contenuajoutmessageforum;
    $cube2 .= $messageposte;
    $cube2 .= "</div>";
    $cube2 .= "</div>";
}
?>