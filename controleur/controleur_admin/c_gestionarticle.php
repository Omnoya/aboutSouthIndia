<?php

require_once ('../../modele/fonction.php');

$contenuformulairegestionarticle = "";
$contenumessagesuppressionarticle = "";
$contenutableaugestionarticle = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "actif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "inactif";
$position7 = "inactif";
$position8 = "inactif";

//-------------------------------------------------------------------

if (isset($_GET['action']) && $_GET['action'] == 'deconnexionadmin') { // si click sur lien 'deconnexion' ci-dessous    // echo '<h1>vous avez demandé une deconnexion</h1>';session_destroy();
    session_destroy();

    header('Location: ../../index.php');
}

if (!adminEstConnecte()) { // s'il n'est pas connecté
    header('Location: ../../index.php');
}
// print '<pre>'; print_r($_SESSION); print '</pre>';

$valeurtitre = "";
$erreurtitre = "";
$titre = "";
$valeurcontenu = "";
$erreurcontenu = "";
$contenu = "";
$valeurphoto = "";
$erreurphoto = "";
$photo = "";
$photoactuelle = "";

echo 'Bonjour ' . $_SESSION['membre']['pseudo'] . ' Bienvenue dans le backoffice<hr />';

//-------------------------------------------------------------------
//Suppression de l'article
if (isset($_GET['action']) && $_GET['action'] == 'supprimer') { //si click sur lien 'supprimer' ci-dessous  
    $contenumessagesuppressionarticle .= "Vous voulez supprimer l'article n° $_GET[id_article]";
    $pdo->exec("DELETE FROM article WHERE id_article = '$_GET[id_article]'"); //suppression de l'article dans la BDD
    // unlink() ... pour supprimer l'image de l'article.
    header("location: v_gestionarticle.php");
}

//---------------------------------------------------
//Affichage tableau dans l'admin
$result = $pdo->query('SELECT * FROM article', PDO::FETCH_ASSOC); // fetch //requete pour resortir tous les champs de la table "article" avec lecture par ligne
$contenutableaugestionarticle .= '<h2>Voici les différents articles disponibles:</h2><hr /><br />';
$contenutableaugestionarticle .= '<table cellpadding="5" cellspacing="0"><tr>';
for ($i = 0; $i < $result->ColumnCount(); $i++) { //$content .= $result->ColumnCount(); //pour parcourir chaque colonne de la 1ère ligne afin de récupérer les intitulés dans chaque indice 'name' la valeur donc $colonne['name'] 
    $colonne = $result->getColumnMeta($i); // getColumnMeta() : retourne les métadonnées pour une colonne d'un jeu de résultats 
    //print '<pre>'; print_r($colonne); print '</pre>';
    $contenutableaugestionarticle .= '<th>' . $colonne['name'] . '</th>';
}
$contenutableaugestionarticle .= '<th>Modifier</th>';
$contenutableaugestionarticle .= '<th>Supprimer</th>';
$contenutableaugestionarticle .= '</tr>';
foreach ($result as $indice => $valeur) { // foreach pour chaque article 
    //print '<pre>'; print_r($valeur); print '</pre>';
    $contenutableaugestionarticle .= '<tr>';
    foreach ($valeur as $indice2 => $valeur2) { // foreach pour chaque information de l'article
        if ($indice2 == 'photo_article') //si l'indice2 est égale à photo, préparer l'affichage de l'image dans le tableau en récupérant le nom du fichier avec $valeur2 dans la balise <img/> avec une largeur de 100.
            $contenutableaugestionarticle .= "<td class=\"tdimage\"><img src=\"../img/imgArticle/$valeur2\" width=\"120\"/></td>";
        else
            //sinon affichage des autres informations avec $valeur2
            $contenutableaugestionarticle .= '<td><div style="height:80px;word-wrap:break-word;overflow:auto;">' . $valeur2 . '</div></td>';
    }
    //Affichage du lien Modification dans chaque ligne du tableau et Récupération dans l'url(click sur lien Modification) de l'indice ?action qui a pour valeur 'modifier' et l'indice id_article qui a pour valeur $valeur[id_article](de la BDD) dans $_GET
    $contenutableaugestionarticle .= "<td><a href=\"?action=modifier&id_article=$valeur[id_article]\">Modification</a></td>";
    //print '<pre>'; print_r($valeur); print '</pre>';
    //Affichage du lien Supprimer dans chaque ligne du tableau et Récupération dans l'url(click sur lien Supprimer) de l'indice ?action qui a pour valeur 'supprimer' et l'indice id_article qui a pour valeur $valeur[id_article](de la BDD) dans $_GET
    $contenutableaugestionarticle .= "<td><a href=\"?action=supprimer&id_article=$valeur[id_article]\" onClick=\"return confirm('En êtes vous certain ?');\">Supprimer</a></td>";
    $contenutableaugestionarticle .= '</tr>';
}
$contenutableaugestionarticle .= '</table>';

//---------------------------------------------------
//Récupération dans le formulaire de la modification de l'article
if (isset($_GET['action']) && $_GET['action'] == 'modifier') { //si click sur lien 'Modification' ci-dessus

    $photoactuelle = "voici la photo actuelle :";
 
    $result = $pdo->query("SELECT * FROM article WHERE id_article = '$_GET[id_article]'"); //requete pour resortir les informations de l'article à modifier($_GET[id_article]).
    $article = $result->fetch(PDO::FETCH_ASSOC); //préparation de la lecture de la ligne de l'article à modifier.
    // debug($article); 
    //Lorsqu' on modifie un article, les champs du formulaire prennent comme valeur le contenu de l'article sélectionné
    $titre = $article['titre'];
    $contenu = $article['contenu'];
    $photo = $article['photo_article'];
    //Si on valide la modification effectuée sur l'article
    if (isset($_POST['envoyer'])) {
        //On va assigner le contenu de la saisie aux variables chargées de rappeler ce qui a été saisi
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        //Si un fichier a été uploadé, on récupère son nom pour l'afficher, sinon on laisse la valeur par défaut, à savoir celle de l'article sélectionné
        //Si la variable censé comporter le nom du fichier est vide, on assigne la valeur de la photo de l'article sélectionné à la variable qui se charge d'afficher la photo
        if (empty($_FILES['photo']['name'])) {
            $photo = $article['photo_article'];
        } else {
            //Sinon, on lui assigne le nom du fichier qui a été uploadé
            $photo = $_FILES['photo']['name'];
        }

        if (empty($_POST['titre'])) {
            $erreurtitre .= "<p class=\"erreur\">Veuillez saisir le titre</p>";
        } elseif (empty($_POST['contenu'])) {
            $erreurcontenu .= "<p class=\"erreur\">Veuillez saisir le contenu</p>";
        } else {
            if (!empty($_FILES['photo']['name'])) {
                // cas : si photo uploadé. (ajout ou modif).
                //$content .= __DIR__ . '<br />'; //__DIR__ est une constante magique qui renseigne le chemin jusqu'au répertoire où se trouve le fichier où on est actuellement, ici : gestionDesArticles.php qui se trouve : C:\wamp\www\phptoutneuf\site\admin 
                $nomPhoto = $_FILES['photo']['name'];
                $cheminPhoto = __DIR__ . '/../../vue/img/imgArticle/' . $_FILES['photo']['name'];
                // $content .= $cheminPhoto . '<br />';
                copy($_FILES['photo']['tmp_name'], $cheminPhoto); // enreg un fichier.
            } else {
                $nomPhoto = $_POST['photoActuelle'];
            }
            
            $titret = addslashes($_POST[titre]);
            $contenuc = addslashes($_POST[contenu]);
            
            $pdo->exec("UPDATE article SET titre= '$titret', contenu = '$contenuc', photo_article='$nomPhoto' WHERE id_article = '$_GET[id_article]'");
            header('location: v_gestionarticle.php');
        }
    }
}

//---------------------------------------------------
//ajout et modification dans la BDD
if (isset($_POST['envoyer']) AND ! isset($_GET['action'])) {
    $valeurtitre .= $_POST['titre'];
    $valeurcontenu .= $_POST['contenu'];
    $valeurphoto .= $_FILES['photo']['name'];
    if (empty($_POST['titre'])) {
        $erreurtitre .= "<p>Veuillez saisir le titre</p>";
    } elseif (empty($_POST['contenu'])) {
        $erreurcontenu .= "<p>Veuillez saisir le contenu</p>";
    } elseif (empty($_FILES['photo']['name'])) {
        $erreurphoto .= "<p>Veuillez sélectionner une photo</p>";
    } else {
        //on limite le poids du fichier upload a 5MO
        if ($_FILES['photo']['size'] <= 5000000) {
            $infosfichier = pathinfo($_FILES['photo']['name']);
            // strolower permet de transformer une chaine de caractere en minuscule
            $extension_upload = strtolower($infosfichier['extension']);
            // print '<pre>'; print_r($infosfichier['extension']); print '</pre>';
            //le tableau array permet de definir les format autorises
            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
            if (in_array($extension_upload, $extensions_autorisees)) {
                //debug($_POST); 	debug($_FILES);
                $nomPhoto = ''; // cas par défaut 
                // cas : si photo uploadé. (ajout ou modif).
                //$content .= __DIR__ . '<br />'; //__DIR__ est une constante magique qui renseigne le chemin jusqu'au répertoire où se trouve le fichier où on est actuellement, ici : gestionDesArticles.php qui se trouve : C:\wamp\www\phptoutneuf\site\admin 
                $nomPhoto = $_FILES['photo']['name'];
                $cheminPhoto = __DIR__ . '/../../vue/img/imgArticle/' . $_FILES['photo']['name'];
                // $content .= $cheminPhoto . '<br />';
                copy($_FILES['photo']['tmp_name'], $cheminPhoto);
// enreg un fichier.
                $_POST['contenu'] = addslashes($_POST['contenu']);
                $pdo->exec("INSERT INTO article (titre, contenu, photo_article, date_article, id_membre) VALUES ('$_POST[titre]', '$_POST[contenu]', '$nomPhoto', NOW(), '" . $_SESSION['membre']['id_membre'] . "')");
                header('location: v_gestionarticle.php');
            }
        } else {
            $erreurphoto .= "Erreur fichier";
        }
    }
}


$contenuformulairegestionarticle .= '<h2>Bonjour, vous pouvez ajouter ici un article.</h2><hr /><br />';

$contenuformulairegestionarticle .= '<form method="post" action="" enctype="multipart/form-data">';
//enctype="multipart/form-data" est utilisé dans un formulaire lorsqu'on doit gérer l'upload de fichier. 
$contenuformulairegestionarticle .= '<label for="">Titre</label>';
$contenuformulairegestionarticle .= "<input type=\"text\" name=\"titre\" placeholder=\"titre\" value = \"$valeurtitre$titre\"/>$erreurtitre<br />"; //la balise input se ferme ici avant le <br/>;
//affichage du titre à modifier dans le formulaire admin

$contenuformulairegestionarticle .= '<label for="">Contenu</label>';
$contenuformulairegestionarticle .= "<textarea name=\"contenu\">$valeurcontenu$contenu</textarea>$erreurcontenu<br />"; //affichage du contenu à modifier dans le formulaire admin
// echo  $ckeditor->editor('contenu');
$contenuformulairegestionarticle .= '<label for="">Photo : </label>';
$contenuformulairegestionarticle .= $photoactuelle . ' <br />';
if (isset($_GET['action'])) {
    $contenuformulairegestionarticle .= "<img src=\"../img/imgArticle/$photo\" width=\"100\" height=\"100\" />$erreurphoto<br />";
    $contenuformulairegestionarticle .= 'Vous pouvez uploader une nouvelle photo pour la remplacer <br />';
}
//affichage de la photo à modifier
$contenuformulairegestionarticle .= '<input type="hidden" name="photoActuelle" value="' . $photo . '" />'; //le type="hidden" permet de définir des champs dans un formulaire qui ne seront pas affichés chez l'utilisateur. Les champs cachés peuvent contenir des données. Lors de l'envoi d'un formulaire, les données de champs cachés sont elles aussi transmises. Ici, l'indice photoActuelle va contenir la valeur $article['photo'], donc le nom du fichier actuel. 

$contenuformulairegestionarticle .= '<input type="file" name="photo" /><br />' . $erreurphoto . '<br />'; //permet de créer le bouton parcourir avec le message : Aucun fichier sélectionné 
$contenuformulairegestionarticle .= '<input type="submit" name="envoyer" value="ENVOYER"/ id=\"boutonenvoyer\">';
$contenuformulairegestionarticle .= '</form>';
?>

