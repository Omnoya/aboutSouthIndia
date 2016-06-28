<?php
require_once ('../modele/fonction.php');
$contenuvalidationinscription = "";

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "";
$chemin2 = "../";

$pseudo = "";
$mdp = "";
$erreurmessage = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "inactif";
$position7 = "inactif";

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
                    header('Location: ../index.php'); // redirection vers index en tant que membre
                }
            }
        }
    }

    // formulaire de connexion
    $content .= '<form method="post" action="" id="connexion_membre">
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

// Récupération des variables nécessaires à l'activation
if(isset($_GET['log']) && isset($_GET['cle'])){
    
$login = $_GET['log'];
$cle = $_GET['cle'];
 
// Récupération de la clé correspondant au $login dans la base de données
$stmt = $pdo->prepare("SELECT cle,actif FROM membres WHERE pseudo = :pseudo ");
if($stmt->execute(array(':pseudo' => $login)) && $row = $stmt->fetch())
  {
    $clebdd = $row['cle'];	// Récupération de la clé
    $actif = $row['actif']; // $actif contiendra alors 0 ou 1
  }
 
 
// On teste la valeur de la variable $actif récupéré dans la BDD
if($actif == '1') // Si le compte est déjà actif on prévient
  {
     $contenuvalidationinscription .= "<div id=\"verification\"><p>Votre compte est déjà actif !</p></div>";
  }
else // Si ce n'est pas le cas on passe aux comparaisons
  {
     if($cle == $clebdd) // On compare nos deux clés	
       {
          // Si elles correspondent on active le compte !	
          $contenuvalidationinscription .= "<div id=\"verification\"><p>Votre compte a bien été activé !</p></div>";
 
          // La requête qui va passer notre champ actif de 0 à 1
          $stmt = $pdo->prepare("UPDATE membres SET actif = 1 WHERE pseudo = :pseudo ");
          $stmt->bindParam(':pseudo', $login);
          $stmt->execute();
       }
     else // Si les deux clés sont différentes on provoque une erreur...
       {
          $contenuvalidationinscription .= "<div id=\"verification\"><p>Erreur ! Votre compte ne peut être activé.</p></div>";
       }
  }
}