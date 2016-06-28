<?php

require_once ('../../modele/fonction.php');

$contenumessagesuppressionphoto = "";
$contenutableaugestionphoto = "";
$contenuformulairegestionphoto = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "inactif";
$position6 = "actif";
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

$valeurphoto = "";
$erreurphoto = "";
$photo = "";
$photoactuelle = "";
$valeurcontenu = "";
$erreurcontenu = "";
$contenu = "";

echo 'Bonjour ' . $_SESSION['membre']['pseudo'] . ' Bienvenue dans le backoffice<hr />';
//-------------------------------------------------------------------
//Suppression de l'article
if (isset($_GET['action']) && $_GET['action'] == 'supprimer') { //si click sur lien 'supprimer' ci-dessous  
    $contenumessagesuppressionphoto .= "Vous voulez supprimer l'image n° $_GET[idImage]";
    $pdo->exec("DELETE FROM image WHERE id_image = '$_GET[idImage]'"); //suppression de l'article dans la BDD
// unlink() ... pour supprimer l'image de l'album photo.
    header("location: v_gestionalbumphoto.php");
}

//---------------------------------------------------
//Affichage tableau dans l'admin
$result = $pdo->query('SELECT * FROM image ORDER BY id_image DESC', PDO::FETCH_ASSOC); // fetch //requete pour resortir tous les champs de la table "article" avec lecture par ligne
$contenutableaugestionphoto .= '<table cellpadding="5" cellspacing="0"><tr>';
for ($i = 0; $i < $result->ColumnCount(); $i++) { //$content .= $result->ColumnCount(); //pour parcourir chaque colonne de la 1ère ligne afin de récupérer les intitulés dans chaque indice 'name' la valeur donc $colonne['name'] 
    $colonne = $result->getColumnMeta($i);
//print '<pre>'; print_r($colonne); print '</pre>';
    $contenutableaugestionphoto .= '<th>' . $colonne['name'] . '</th>';
}
$contenutableaugestionphoto .= '<th>Modification</th>';
$contenutableaugestionphoto .= '<th>Supprimer</th>';
$contenutableaugestionphoto .= '</tr>';
foreach ($result as $indice => $valeur) { // foreach pour chaque article 
//print '<pre>'; print_r($valeur); print '</pre>';
    $contenutableaugestionphoto .= '<tr>';
    foreach ($valeur as $indice2 => $valeur2) { // foreach pour chaque information de l'article
        if ($indice2 == 'nom_image') //si l'indice2 est égale à photo, préparer l'affichage de l'image dans le tableau en récupérant le nom du fichier avec $valeur2 dans la balise <img/> avec une largeur de 100.
            $contenutableaugestionphoto .= "<td><img src=\"../img/imgAlbumP/$valeur2\" width=\"100\" /></td>";
        else //sinon affichage des autres informations avec $valeur2
            $contenutableaugestionphoto .= '<td>' . $valeur2 . '</td>';
    }
//Affichage du lien Modification dans chaque ligne du tableau et Récupération dans l'url(click sur lien Modification) de l'indice ?action qui a pour valeur 'modifier' et l'indice id_article qui a pour valeur $valeur[id_article](de la BDD) dans $_GET
    $contenutableaugestionphoto .= "<td><a href=\"?action=modifier&idImage=$valeur[id_image]\">Modification</a></td>";
//print '<pre>'; print_r($valeur); print '</pre>';
//Affichage du lien Supprimer dans chaque ligne du tableau et Récupération dans l'url(click sur lien Supprimer) de l'indice ?action qui a pour valeur 'supprimer' et l'indice id_article qui a pour valeur $valeur[id_article](de la BDD) dans $_GET
    $contenutableaugestionphoto .= "<td><a href=\"?action=supprimer&idImage=$valeur[id_image]\" onClick=\"return confirm('En êtes vous certain ?');\">Supprimer</a></td>";
    $contenutableaugestionphoto .= '</tr>';
}
$contenutableaugestionphoto .= '</table>';

//---------------------------------------------------
//Récupération dans le formulaire de la modification de l'article
if (isset($_GET['action']) && $_GET['action'] == 'modifier') { //si click sur lien 'Modification' ci-dessus
    $photoactuelle = "voici la photo actuelle :";

    $result = $pdo->query("SELECT * FROM image WHERE id_image = '$_GET[idImage]'"); //requete pour resortir les informations de l'article à modifier($_GET[id_article]).
    $lectureLigneImg = $result->fetch(PDO::FETCH_ASSOC); //préparation de la lecture de la ligne de l'article à modifier.
// debug($article);
//Lorsqu'on modifie une ligne image, les champs du formulaire prennent comme valeur le contenu de la ligne image sélectionnée
    $contenu = $lectureLigneImg['contenu'];
    $photo = $lectureLigneImg['nom_image'];
//Si on valide la modification effectuée sur l'article
    if (isset($_POST['envoyer'])) {
//On va assigner le contenu de la saisie aux variables chargées de rappeler ce qui a été saisi
        $contenu = $_POST['contenu'];
//Si un fichier a été uploadé, on récupère son nom pour l'afficher, sinon on laisse la valeur par défaut, à savoir celle de l'article sélectionné
//Si la variable censé comporter le nom du fichier est vide, on assigne la valeur de la photo de l'article sélectionné à la variable qui se charge d'afficher la photo
        if (empty($_FILES['nom_image']['name'])) {
            $photo = $lectureLigneImg['nom_image'];
        } else {
//Sinon, on lui assigne le nom du fichier qui a été uploadé
            $photo = $_FILES['nom_image']['name'];
        }


        if (empty($_POST['contenu'])) {
            $erreurcontenu .= "<p>Veuillez saisir le contenu</p>";
        } else {
            if (!empty($_FILES['nom_image']['name'])) {
// cas : si photo uploadé. (ajout ou modif).
//$content .= __DIR__ . '<br />'; //__DIR__ est une constante magique qui renseigne le chemin jusqu'au répertoire où se trouve le fichier où on est actuellement, ici : gestionDesArticles.php qui se trouve : C:\wamp\www\phptoutneuf\site\admin 
                $nomPhoto = $_FILES['nom_image']['name'];
                $cheminPhoto = __DIR__ . '/../../vue/img/imgAlbumP/' . $_FILES['nom_image']['name'];
// $content .= $cheminPhoto . '<br />';
                copy($_FILES['nom_image']['tmp_name'], $cheminPhoto); // enreg un fichier.
            } else {
                $nomPhoto = $_POST['imageActuelle'];
            }
            $pdo->exec("UPDATE image SET etat_sud = '$_POST[etat_sud]', nom_image='$nomPhoto', contenu = '$_POST[contenu]' WHERE id_image = '$_GET[idImage]'");
            header('location: v_gestionalbumphoto.php');
        }
    }
}

//---------------------------------------------------
//ajout et modification dans la BDD
if (isset($_POST["envoyer"]) AND ! isset($_GET['action'])) {
    $valeurphoto .= $_FILES['nom_image']['name'];
    $valeurcontenu .= $_POST['contenu'];

    if (empty($_FILES['nom_image']['name'])) {
        $erreurphoto .= "<p>Veuillez sélectionner une photo</p>";
    } elseif (empty($_POST['contenu'])) {
        $erreurcontenu .= "<p>Veuillez saisir le contenu</p>";
    } else {
//on limite le poids du fichier upload a 5MO
        if ($_FILES['nom_image']['size'] <= 5000000) {
            $infosfichier = pathinfo($_FILES['nom_image']['name']);
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
                $nomPhoto = $_FILES['nom_image']['name'];
                $cheminPhoto = __DIR__ . '/../../vue/img/imgAlbumP/' . $_FILES['nom_image']['name'];
// $content .= $cheminPhoto . '<br />';
                copy($_FILES['nom_image']['tmp_name'], $cheminPhoto); // enreg un fichier.

                $_POST['contenu'] = strip_tags(addslashes($_POST['contenu']));

                $pdo->exec("INSERT INTO image (etat_sud, nom_image, contenu, date_image) VALUES ('$_POST[etat_sud]', '$nomPhoto', '$_POST[contenu]', NOW())");
                header('location: v_gestionalbumphoto.php');
            }
        } else {
            $erreurphoto .= "Erreur fichier";
        }
    }
}





$contenuformulairegestionphoto .= '<h2>Bonjour, ici vous pouvez ajouter, modifier ou supprimer une photo :</h2><hr /><br />';

$contenuformulairegestionphoto .= '<form method="post" action="" enctype="multipart/form-data">'; //enctype="multipart/form-data" est utilisé dans un formulaire lorsqu'on doit gérer l'upload de fichier.

$contenuformulairegestionphoto .= '<label for="etatSud">Sélectionner l\'Etat :</label><br />';
$contenuformulairegestionphoto .= '<select name="etat_sud" id="etatSud">
                                        <option value="tamilNadu">Tamil Nadu</option>
                                        <option value="kerala">Kérala</option>
                                        <option value="karnataka">Karnataka</option>
                                    </select><br /><br />';

$contenuformulairegestionphoto .= '<label for="">Photo : </label>';

$contenuformulairegestionphoto .= $photoactuelle . ' <br />';
if (isset($_GET['action'])) {
    $contenuformulairegestionphoto .= "<img src=\"../img/imgAlbumP/$photo\" width=\"100\" height=\"100\" />$erreurphoto<br />";
    $contenuformulairegestionphoto .= 'Vous pouvez uploader une nouvelle photo pour la remplacer <br />';
}
//affichage de la photo à modifier
$contenuformulairegestionphoto .= '<input type="hidden" name="imageActuelle" value="' . $photo . '" />'; //le type="hidden" permet de définir des champs dans un formulaire qui ne seront pas affichés chez l'utilisateur. Les champs cachés peuvent contenir des données. Lors de l'envoi d'un formulaire, les données de champs cachés sont elles aussi transmises. Ici, l'indice photoActuelle va contenir la valeur $article['photo'], donc le nom du fichier actuel. 

$contenuformulairegestionphoto .= '<input type="file" name="nom_image" /><br />' . $erreurphoto . '<br />'; //permet de créer le bouton parcourir avec le message : Aucun fichier sélectionné        

$contenuformulairegestionphoto .= '<label for="">Contenu :</label><br />';
$contenuformulairegestionphoto .= "<textarea name=\"contenu\">$valeurcontenu$contenu</textarea>$erreurcontenu<br />";

$contenuformulairegestionphoto .= '<input type="submit" value="Rafraichir" name="rafraichir" id="rafraichir"/><br /><br />';
$contenuformulairegestionphoto .= '<input type="submit" name="envoyer" value="ENVOYER"/>';
$contenuformulairegestionphoto .= '</form>';

if (isset($_POST["rafraichir"])) {
    header('Location: v_gestionalbumphoto.php');
}
?>