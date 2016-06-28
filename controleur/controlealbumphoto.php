<?php

require_once ('../modele/fonction.php');
$contenuTn = "";
$contenuKer = "";
$contenuKar = "";

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "";
$chemin2 = "../";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "actif";
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
    $content .= '<form method="post" id="connexion_membre" >
		<input type="text" name="pseudo" placeholder="Pseudo" id="pseudo" value="' . $pseudo . '" /><br />
		<input type="password" name="mdp" placeholder="Mot de passe" id="mdp" value="' . $mdp . '" /><br />
		<input type="submit" name="submitMembre" value="Se connecter" id="submit" />
                ' . $erreurmessage . '
                <div id="lien" >
                    <a href="mdpperdu.php" >Mot de passe oublié ?</a>
                    <a href="inscription.php" >S\'inscrire</a>
                </div>
	</form>';
//------------------------------------------------------------------------------------------------------
} else {
    if ($_SESSION['membre']['etat'] == 1) {
        $content .= '<div class="apres_connexion" >';
        $content .= '<div class="indication_membre" ><span class="fa fa-user" ></span><span class="indication" >' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion" ><a class="item_lien" href="vue_admin/v_indexadmin.php" ><span class="fa fa-cog" ></span> &nbsp; Accès au back-office</a></div>';
        $content .= '<div class="lien_parametre_deconnexion" ><a class="item_lien" href="inclusions/deconnexion.php" ><span class="fa fa-power-off" ></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    } else {
        $content .= '<div class="apres_connexion" >';
        $content .= '<div class="indication_membre" ><span class="fa fa-user" ></span><span class="indication" >' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion" ><a class="item_lien" href="parametresmembre.php" ><span class="fa fa-cog" ></span> &nbsp; Mes paramètres</a></div>';
        $content .= '<div class="lien_parametre_deconnexion" ><a class="item_lien" href="inclusions/deconnexion.php" ><span class="fa fa-power-off" ></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    }
}


//AFFICHAGE DES PHOTOS


//variable qui servira à afficher les images
$imagetamil = "";
$imagekerala = "";
$imagekarnataka = "";

//variable qui servira à afficher les flèches
$flechehautamil = "";
$flechebastamil = "";
$flechehautkerala = "";
$flechebaskerala = "";
$flechehautkarnataka = "";
$flechebaskarnataka = "";

/* -------- Tamil nadu ----------- */ 

//on va conditionner l'affichage des images
if (isset($_GET['pageimagetamil']) AND $_GET['pageimagetamil'] != 0) {
    //on vide les variables
    $flechebastamil = "";
    $flechehautamil = "";

    //on récupère les informations envoyées précédemment dans l'url
    $pageimagetamil = $_GET['pageimagetamil']; 
    $debutselection = $_GET['debutselection'];
    $nombreimage = $_GET['nombreimage'];

    //Afficher les trois images suivantes
    $requeteimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'tamilNadu' ORDER BY id_image DESC LIMIT $debutselection,3");
    while ($donneesimage = $requeteimage->fetch(PDO::FETCH_ASSOC)) {
        $imagetamil .= "<a class=\"fancybox-buttons\" data-fancybox-group=\"button\" href=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" title=\"" . $donneesimage['contenu'] . "\" >";
        $imagetamil .= "<div class=\"imagealbum\" >";
        $imagetamil .= "<span class=\"fa fa-camera fa-5x iconephoto\" ></span>";
        $imagetamil .= "<div class=\"fondimage\" ></div>";
        $imagetamil .= "<img src=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" alt=\"photo\" />";
        $imagetamil .= "</div>";
        $imagetamil .= "</a>";
    }

    //on génère le lien vers la page précédente
    //on va décrémenter la valeur de début de sélection dans la base de données
    $_GET['debutselection'] -= 3;
    //on a besoin du numero de page afin de différencier le contenu de chaque affichage
    $_GET['pageimagetamil'] -= 1;
    //on génère ce lien en passant ces informations
    $flechehautamil .= "<a href=\"?pageimagetamil=" .$_GET['pageimagetamil']. "&amp;debutselection=" .$_GET['debutselection']. "&amp;nombreimage=$nombreimage\" >";
    $flechehautamil .= "<div id=\"flechehaut\" >";
    $flechehautamil .= "<span class=\"fa fa-chevron-up\" ></span>";
    $flechehautamil .= "</div>";
    $flechehautamil .= "</a>";

    //si il y a plus de trois images on va générer le lien vers la page suivante
    $debutselection += 3;
    if ($nombreimage > $debutselection) {
        //on a besoin aussi du nombre total d'entrée d'image pour cet état, à savoir $nombreimage
        //on a besoin du numero de page afin de différencier le contenu de chaque affichage
        $pageimagetamil += 1;
        //on génère ce lien en passant ces informations
        $flechebastamil .= "<a href=\"?pageimagetamil=$pageimagetamil&amp;debutselection=$debutselection&amp;nombreimage=$nombreimage\" >";
        $flechebastamil .= "<div class=\"flechebas\" >";
        $flechebastamil .= "<span class=\"fa fa-chevron-down\" ></span>";
        $flechebastamil .= "</div>";
        $flechebastamil .= "</a>";
    }
} else {
//on compte le nombre d'entrées pour cet etat
    $requetenombreimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'tamilNadu'");
    $nombreimage = $requetenombreimage->rowCount();
//on va récupérer les trois premières images pour le tamil Nadu
    $requeteimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'tamilNadu' ORDER BY id_image DESC LIMIT 0,3");
    while ($donneesimage = $requeteimage->fetch(PDO::FETCH_ASSOC)) {
        $imagetamil .= "<a class=\"fancybox-buttons\" data-fancybox-group=\"button\" href=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" title=\"" . $donneesimage['contenu'] . "\" >";
        $imagetamil .= "<div class=\"imagealbum\" >";
        $imagetamil .= "<span class=\"fa fa-camera fa-5x iconephoto\" ></span>";
        $imagetamil .= "<div class=\"fondimage\" ></div>";
        $imagetamil .= "<img src=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" alt=\"photo\" />";
        $imagetamil .= "</div>";
        $imagetamil .= "</a>";
    }

    //si il y a plus de trois images on va générer le lien vers la page suivante
    if ($nombreimage > 3) {
        //on a besoin aussi du nombre total d'entrée d'image pour cet état, à savoir $nombreimage
        //on a besoin de la valeur de début de sélection dans la base de données
        $debutselection = 3;
        //on a besoin du numero de page afin de différencier le contenu de chaque affichage
        $pageimagetamil = 1;
        //on génère ce lien en passant ces informations
        $flechebastamil .= "<a href=\"?pageimagetamil=$pageimagetamil&amp;debutselection=$debutselection&amp;nombreimage=$nombreimage\" >";
        $flechebastamil .= "<div class=\"flechebas\" >";
        $flechebastamil .= "<span class=\"fa fa-chevron-down\" ></span>";
        $flechebastamil .= "</div>";
        $flechebastamil .= "</a>";
    }
}

/* -------- Kérala ----------- */ 

//on va conditionner l'affichage des images
if (isset($_GET['pageimagekerala']) AND $_GET['pageimagekerala'] != 0) {
    //on vide les variables
    $flechebaskerala = "";
    $flechehautkerala = "";

    //on récupère les informations envoyées précédemment dans l'url
    $pageimagekerala = $_GET['pageimagekerala']; 
    $debutselection = $_GET['debutselection'];
    $nombreimage = $_GET['nombreimage'];

    //Afficher les trois images suivantes
    $requeteimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'kerala' ORDER BY id_image DESC LIMIT $debutselection,3");
    while ($donneesimage = $requeteimage->fetch(PDO::FETCH_ASSOC)) {
        $imagekerala .= "<a class=\"fancybox-buttons\" data-fancybox-group=\"button\" href=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" title=\"" . $donneesimage['contenu'] . "\" >";
        $imagekerala .= "<div class=\"imagealbum\" >";
        $imagekerala .= "<span class=\"fa fa-camera fa-5x iconephoto\" ></span>";
        $imagekerala .= "<div class=\"fondimage\" ></div>";
        $imagekerala .= "<img src=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" alt=\"photo\" />";
        $imagekerala .= "</div>";
        $imagekerala .= "</a>";
    }

    //on génère le lien vers la page précédente
    //on va décrémenter la valeur de début de sélection dans la base de données
    $_GET['debutselection'] -= 3;
    //on a besoin du numero de page afin de différencier le contenu de chaque affichage
    $_GET['pageimagekerala'] -= 1;
    //on génère ce lien en passant ces informations
    $flechehautkerala .= "<a href=\"?pageimagekerala=" .$_GET['pageimagekerala']. "&amp;debutselection=" .$_GET['debutselection']. "&amp;nombreimage=$nombreimage\" >";
    $flechehautkerala .= "<div id=\"flechehaut\" >";
    $flechehautkerala .= "<span class=\"fa fa-chevron-up\" ></span>";
    $flechehautkerala .= "</div>";
    $flechehautkerala .= "</a>";

    //si il y a plus de trois images on va générer le lien vers la page suivante
    $debutselection += 3;
    if ($nombreimage > $debutselection) {
        //on a besoin aussi du nombre total d'entrée d'image pour cet état, à savoir $nombreimage
        //on a besoin du numero de page afin de différencier le contenu de chaque affichage
        $pageimagekerala += 1;
        //on génère ce lien en passant ces informations
        $flechebaskerala .= "<a href=\"?pageimagekerala=$pageimagekerala&amp;debutselection=$debutselection&amp;nombreimage=$nombreimage\" >";
        $flechebaskerala .= "<div class=\"flechebas\" >";
        $flechebaskerala .= "<span class=\"fa fa-chevron-down\" ></span>";
        $flechebaskerala .= "</div>";
        $flechebaskerala .= "</a>";
    }
} else {
//on compte le nombre d'entrées pour cet etat
    $requetenombreimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'kerala'");
    $nombreimage = $requetenombreimage->rowCount();
//on va récupérer les trois premières images pour le Kerala
    $requeteimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'kerala' ORDER BY id_image DESC LIMIT 0,3");
    while ($donneesimage = $requeteimage->fetch(PDO::FETCH_ASSOC)) {
        $imagekerala .= "<a class=\"fancybox-buttons\" data-fancybox-group=\"button\" href=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" title=\"" . $donneesimage['contenu'] . "\" >";
        $imagekerala .= "<div class=\"imagealbum\" >";
        $imagekerala .= "<span class=\"fa fa-camera fa-5x iconephoto\" ></span>";
        $imagekerala .= "<div class=\"fondimage\" ></div>";
        $imagekerala .= "<img src=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" alt=\"photo\" />";
        $imagekerala .= "</div>";
        $imagekerala .= "</a>";
    }

    //si il y a plus de trois images on va générer le lien vers la page suivante
    if ($nombreimage > 3) {
        //on a besoin aussi du nombre total d'entrée d'image pour cet état, à savoir $nombreimage
        //on a besoin de la valeur de début de sélection dans la base de données
        $debutselection = 3;
        //on a besoin du numero de page afin de différencier le contenu de chaque affichage
        $pageimagekerala = 1;
        //on génère ce lien en passant ces informations
        $flechebaskerala .= "<a href=\"?pageimagekerala=$pageimagekerala&amp;debutselection=$debutselection&amp;nombreimage=$nombreimage\" >";
        $flechebaskerala .= "<div class=\"flechebas\" >";
        $flechebaskerala .= "<span class=\"fa fa-chevron-down\" ></span>";
        $flechebaskerala .= "</div>";
        $flechebaskerala .= "</a>";
    }
}


/* -------- Karnataka ----------- */ 

//on va conditionner l'affichage des images
if (isset($_GET['pageimagekarnataka']) AND $_GET['pageimagekarnataka'] != 0) {
    //on vide les variables
    $flechebaskarnataka = "";
    $flechehautkarnataka = "";

    //on récupère les informations envoyées précédemment dans l'url
    $pageimagekarnataka = $_GET['pageimagekarnataka']; 
    $debutselection = $_GET['debutselection'];
    $nombreimage = $_GET['nombreimage'];

    //Afficher les trois images suivantes
    $requeteimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'karnataka' ORDER BY id_image DESC LIMIT $debutselection,3");
    while ($donneesimage = $requeteimage->fetch(PDO::FETCH_ASSOC)) {
        $imagekarnataka .= "<a class=\"fancybox-buttons\" data-fancybox-group=\"button\" href=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" title=\"" . $donneesimage['contenu'] . "\" >";
        $imagekarnataka .= "<div class=\"imagealbum\" >";
        $imagekarnataka .= "<span class=\"fa fa-camera fa-5x iconephoto\" ></span>";
        $imagekarnataka .= "<div class=\"fondimage\" ></div>";
        $imagekarnataka .= "<img src=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" alt=\"photo\" />";
        $imagekarnataka .= "</div>";
        $imagekarnataka .= "</a>";
    }

    //on génère le lien vers la page précédente
    //on va décrémenter la valeur de début de sélection dans la base de données
    $_GET['debutselection'] -= 3;
    //on a besoin du numero de page afin de différencier le contenu de chaque affichage
    $_GET['pageimagekarnataka'] -= 1;
    //on génère ce lien en passant ces informations
    $flechehautkarnataka .= "<a href=\"?pageimagekarnataka=" .$_GET['pageimagekarnataka']. "&amp;debutselection=" .$_GET['debutselection']. "&amp;nombreimage=$nombreimage\" >";
    $flechehautkarnataka .= "<div id=\"flechehaut\" >";
    $flechehautkarnataka .= "<span class=\"fa fa-chevron-up\" ></span>";
    $flechehautkarnataka .= "</div>";
    $flechehautkarnataka .= "</a>";

    //si il y a plus de trois images on va générer le lien vers la page suivante
    $debutselection += 3;
    if ($nombreimage > $debutselection) {
        //on a besoin aussi du nombre total d'entrée d'image pour cet état, à savoir $nombreimage
        //on a besoin du numero de page afin de différencier le contenu de chaque affichage
        $pageimagekarnataka += 1;
        //on génère ce lien en passant ces informations
        $flechebaskarnataka .= "<a href=\"?pageimagekarnataka=$pageimagekarnataka&amp;debutselection=$debutselection&amp;nombreimage=$nombreimage\" >";
        $flechebaskarnataka .= "<div class=\"flechebas\" >";
        $flechebaskarnataka .= "<span class=\"fa fa-chevron-down\" ></span>";
        $flechebaskarnataka .= "</div>";
        $flechebaskarnataka .= "</a>";
    }
} else {
//on compte le nombre d'entrées pour cet etat
    $requetenombreimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'karnataka'");
    $nombreimage = $requetenombreimage->rowCount();
//on va récupérer les trois premières images pour le Karnataka
    $requeteimage = $pdo->query("SELECT * FROM image WHERE etat_sud = 'karnataka' ORDER BY id_image DESC LIMIT 0,3");
    while ($donneesimage = $requeteimage->fetch(PDO::FETCH_ASSOC)) {
        $imagekarnataka .= "<a class=\"fancybox-buttons\" data-fancybox-group=\"button\" href=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" title=\"" . $donneesimage['contenu'] . "\" >";
        $imagekarnataka .= "<div class=\"imagealbum\" >";
        $imagekarnataka .= "<span class=\"fa fa-camera fa-5x iconephoto\" ></span>";
        $imagekarnataka .= "<div class=\"fondimage\" ></div>";
        $imagekarnataka .= "<img src=\"img/imgAlbumP/" . $donneesimage['nom_image'] . "\" alt=\"photo\" />";
        $imagekarnataka .= "</div>";
        $imagekarnataka .= "</a>";
    }

    //si il y a plus de trois images on va générer le lien vers la page suivante
    if ($nombreimage > 3) {
        //on a besoin aussi du nombre total d'entrée d'image pour cet état, à savoir $nombreimage
        //on a besoin de la valeur de début de sélection dans la base de données
        $debutselection = 3;
        //on a besoin du numero de page afin de différencier le contenu de chaque affichage
        $pageimagekarnataka = 1;
        //on génère ce lien en passant ces informations
        $flechebaskarnataka .= "<a href=\"?pageimagekarnataka=$pageimagekarnataka&amp;debutselection=$debutselection&amp;nombreimage=$nombreimage\" >";
        $flechebaskarnataka .= "<div class=\"flechebas\" >";
        $flechebaskarnataka .= "<span class=\"fa fa-chevron-down\" ></span>";
        $flechebaskarnataka .= "</div>";
        $flechebaskarnataka .= "</a>";
    }
}




