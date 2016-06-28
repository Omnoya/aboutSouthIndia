<?php

require_once ('../modele/fonction.php');
$contenusaisieinscription = "";

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
$position7 = "actif";

$pseudo = "";
$mdp = "";
$erreurmessage = "";

$pseudoinscription = "";
$nom = "";
$prenom = "";
$email = "";
$mdpinscription = "";
$messageinscriptionpseudo = "";
$messageinscriptionnom = "";
$messageinscriptionprenom = "";
$messageinscriptionemail = "";
$messageinscriptionmdp = "";

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

//------------------------------------------------------------------------------------------------------

if (isset($_POST["submitinscription"])) {
// Récupération des variables nécessaires au mail de confirmation

    $pseudoinscription .= $_POST['pseudoinscription'];
    $nom .= $_POST['nom'];
    $prenom .= $_POST['prenom'];
    $email .= $_POST['email'];
    $mdpinscription .= $_POST['mdpinscription'];



    $result = $pdo->prepare("SELECT * FROM membres WHERE pseudo=:pseudoinscription");
    $result->bindValue(":pseudoinscription", trim($pseudoinscription));
    $result->execute();
    $recupdonnees = $result->fetch(PDO::FETCH_ASSOC);
    if (empty($pseudoinscription)) {
        $messageinscriptionpseudo .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un pseudo.</p></div>";
    } elseif ($recupdonnees['pseudo'] == $pseudoinscription) {
        $messageinscriptionpseudo .= "<div class=\"contenu_erreur\"><p>Le pseudo existe déja. Veuillez en saisir une nouvelle.</p></div>";
    } elseif (empty($nom)) {
        $messageinscriptionnom .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un nom.</p></div>";
    } elseif (empty($prenom)) {
        $messageinscriptionprenom .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un prenom.</p></div>";
    } elseif (empty($email)) {
        $messageinscriptionemail .= "<div class=\"contenu_erreur\"><p>Veuillez saisir une adresse mail.</p></div>";
    } elseif (empty($mdpinscription)) {
        $messageinscriptionmdp .= "<div class=\"contenu_erreur\"><p>Veuillez saisir un mot de passe</p></div>";
    } else {
// Génération aléatoire d'une clé
        $cle = md5(microtime(TRUE) * 100000);


// Insertion de la clé dans la base de données (à adapter en INSERT si besoin)
        $stmt = $pdo->prepare("INSERT INTO membres(pseudo, nom, prenom, email, mdp, cle)  VALUES(:pseudoinscription, :nom, :prenom, :email, :mdpinscription, :cle)");

        $stmt->bindParam(':pseudoinscription', $pseudoinscription);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdpinscription', $mdpinscription);
        $stmt->bindParam(':cle', $cle);

        $stmt->execute();


// Préparation du mail contenant le lien d'activation
        $sujet = "Activer votre compte";
        $entete = "From: omnoya@gmail.com";

// Le lien d'activation est composé du login(log) et de la clé(cle)
        $message = 'Bienvenue sur Votre Site,
 
Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.
 
http://localhost/projetv2/vue/validationinscription.php?log=' . urlencode($pseudoinscription) . '&cle=' . urlencode($cle) . '
 
 
---------------
Ceci est un mail automatique, Merci de ne pas y repondre';


        if (!@mail($email, $sujet, $message, $entete)) { // Envoi du mail
            $messageinscriptionmdp .= "<div class=\"contenu_erreur\"><p>Echec de l'envoi du mail</p></div>";
        } else {
            header('location: inscription.php?statut=inscrit');
        }
    }
}


if (isset($_GET['statut']) && $_GET['statut'] == 'inscrit') {
    $contenusaisieinscription = "<div id=\"verification\"><p>Vérifier votre mail pour valider l'inscription</p></div>";
} else {
    $contenusaisieinscription .= '<form method="post" action="' . $_SERVER['PHP_SELF']. '#ancre">
    <input type="text" name="pseudoinscription" placeholder="Pseudo" class="champinscription" value="' . $pseudoinscription . '" />' . $messageinscriptionpseudo . ' 
    <input type="text" name="nom" placeholder="Nom" class="champinscription" value="' . $nom . '"/>' . $messageinscriptionnom . '
    <input type="text" name="prenom" placeholder="Prenom" class="champinscription" value="' . $prenom . '"/>' . $messageinscriptionprenom . '
    <input type="email" name="email" placeholder="Email" class="champinscription" value="' . $email . '"/>' . $messageinscriptionemail . '
    <input type="password" name="mdpinscription" placeholder="Mot de passe" class="champinscription" value="' . $mdpinscription . '"/>' . $messageinscriptionmdp . '
    <input type="submit" name="submitinscription" id="submitinscription" />
</form><br/>';
}
?>