<?php

require_once ('../../modele/fonction.php');

$contenuindicationsuppressionsujetforum = "";
$contenutableaugestionsujetforum = "";
$contenuformulairegestionsujetforum = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "actif";
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
$valeurdescription = "";
$erreurdescription = "";
$description = "";

echo 'Bonjour ' . $_SESSION['membre']['pseudo'] . ' Bienvenue dans le backoffice<hr />';

//-------------------------------------------------------------------

if(isset($_GET['action']) && $_GET['action'] == 'envoyer')
{
        $intIdSujet = (int)$_GET['id_sujet'];
	$pdo->exec("UPDATE sujetForum SET activer=1 WHERE id_sujet = $intIdSujet");
	
}
//-------------------------------------------------------------------------------------

//Suppression de l'article
if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
//si click sur lien 'supprimer' ci-dessous    
    $contenuindicationsuppressionsujetforum .= "Vous voulez supprimer le message n° $_GET[id_sujet]";
    $pdo->exec("DELETE FROM sujetForum WHERE id_sujet = '$_GET[id_sujet]'"); //suppression de l'article dans la BDD
    // unlink() ... pour supprimer l'image de l'article.
    header("location: v_gestionsujetforum.php");
}

//---------------------------------------------------
//Affichage tableau dans l'admin
$result = $pdo->query('SELECT * FROM sujetforum', PDO::FETCH_ASSOC); // fetch //requete pour resortir tous les champs de la table "sujetforum" avec lecture par ligne
$contenutableaugestionsujetforum .= '<h2>Voici les différents sujets du forum :<hr /></h2><br />';
$contenutableaugestionsujetforum .= '<table cellpadding="5" cellspacing="0"><tr>';
for ($i = 0; $i < $result->ColumnCount(); $i++) { //$content .= $result->ColumnCount(); //pour parcourir chaque colonne de la 1ère ligne afin de récupérer les intitulés dans chaque indice 'name' la valeur donc $colonne['name']
    $colonne = $result->getColumnMeta($i);
    // print '<pre>'; print_r($colonne); print '</pre>';
    $contenutableaugestionsujetforum .= '<th>' . $colonne['name'] . '</th>';
}
$contenutableaugestionsujetforum .= '<th>Envoyer</th>';
$contenutableaugestionsujetforum .= '<th>Modifier</th>';
$contenutableaugestionsujetforum .= '<th>Supprimer</th>';
$contenutableaugestionsujetforum .= '</tr>';
foreach ($result as $indice => $valeur) { // foreach pour chaque sujetforum
    // print '<pre>'; print_r($valeur); print '</pre>';
    $contenutableaugestionsujetforum .= '<tr>';
    foreach ($valeur as $indice2 => $valeur2) { // foreach pour chaque information du sujetforum
        //affichage des informations avec $valeur2
        $contenutableaugestionsujetforum .= '<td>' . $valeur2 . '</td>';
    }

    //Affichage du lien Modification dans chaque ligne du tableau et Récupération dans l'url(click sur lien                    Modification) de l'indice ?action qui a pour valeur 'modifier' et l'indice id_sujet qui a pour valeur $valeur          [id_sujet](de la BDD) dans $_GET
    
    $contenutableaugestionsujetforum .= "<td><a href=\"?action=envoyer&id_sujet=$valeur[id_sujet]\" onClick=\"return confirm('En êtes vous certain ?');\">Envoyer</a></td>";
    
    $contenutableaugestionsujetforum .= "<td><a href=\"?action=modifier&id_sujet=$valeur[id_sujet]\">Modification</a></td>";

    //Affichage du lien Supprimer dans chaque ligne du tableau et Récupération dans l'url(click sur lien Supprimer)            de l'indice ?action qui a pour valeur 'supprimer' et l'indice id_sujet qui a pour valeur $valeur[id_sujet](          de la BDD) dans $_GET
    $contenutableaugestionsujetforum .= "<td><a href=\"?action=supprimer&id_sujet=$valeur[id_sujet]\" onClick=\"return confirm('En êtes vous certain ?');\">Supprimer</a></td>";
    $contenutableaugestionsujetforum .= '</tr>';
}
$contenutableaugestionsujetforum .= '</table>';
//---------------------------------------------------
//Récupération dans le formulaire de la modification du sujetforum
if (isset($_GET['action']) && $_GET['action'] == 'modifier') { //si click sur lien 'Modification' ci-dessus 
    $result = $pdo->query("SELECT * FROM sujetforum WHERE id_sujet = '$_GET[id_sujet]'"); //requete pour resortir les informations de sujetforum à modifier($_GET[id_sujet]).
    $sujet = $result->fetch(PDO::FETCH_ASSOC); //préparation de la lecture de la ligne de sujetforum à modifier.
    // debug($article);
//Lorsque on modifie un article, les champs du formulaire prennent comme valeur le contenu de l'article sélectionné
    $titre = $sujet['titre'];
    $description = $sujet['description'];

//Si on valide la modification effectuée sur l'article
    if (isset($_POST['envoyer'])) {
        //On va assigner le contenu de la saisie aux variables chargées de rappeler ce qui a été saisi
        $titre = $_POST['titre'];
        $description = $_POST['description'];

        if (empty($_POST['titre'])) {
            $erreurtitre .= "<p>Veuillez saisir le titre</p>";
        } elseif (empty($_POST['description'])) {
            $erreurdescription .= "<p>Veuillez saisir la description</p>";
        } else {
            $pdo->exec("UPDATE sujetforum SET titre= '$_POST[titre]', description = '$_POST[description]' WHERE id_sujet = '$_GET[id_sujet]'");
            header('location: v_gestionsujetforum.php');
        }
    }
}
//----------------------------------------
//ajout et modification dans la BDD
if (isset($_POST["envoyer"]) AND ! isset($_GET['action'])) {
    $valeurtitre .= $_POST['titre'];
    $valeurdescription .= $_POST['description'];
    if (empty($_POST['titre'])) {
        $erreurtitre .= "<p>Veuillez saisir le titre</p>";
    } elseif (empty($_POST['description'])) {
        $erreurdescription .= "<p>Veuillez saisir la description</p>";
    } else {

        // debug($_POST); 	debug($_FILES);
        $_POST['description'] = strip_tags(addslashes($_POST['description']));

        $pdo->exec("INSERT INTO sujetForum (titre, description, id_membre) VALUES ('$_POST[titre]', '$_POST[description]', '" . $_SESSION['membre']['id_membre'] . "')");
        header('location: v_gestionsujetforum.php');
    }
}


$contenuformulairegestionsujetforum .= '<h2>Bonjour, vous pouvez ajouter ici un sujet au forum:<hr /></h2><br />';

$contenuformulairegestionsujetforum .= '<form method="post" action="" >';
$contenuformulairegestionsujetforum .= '<label for="">Titre</label>';
$contenuformulairegestionsujetforum .= "<input type=\"text\" name=\"titre\" placeholder=\"titre\" value = \"$valeurtitre$titre\" />$erreurtitre<br />"; //affichage du titre à modifier dans le formulaire 

$contenuformulairegestionsujetforum .= '<label for="">Description</label>';
$contenuformulairegestionsujetforum .= "<textarea name=\"description\">$valeurdescription$description</textarea>$erreurdescription<br />"; //affichage du contenu à modifier dans le formulaire 
//echo  $ckeditor->editor('description');

$contenuformulairegestionsujetforum .= '<input type="submit" value="RAFRAICHIR" name="rafraichir" id="rafraichir"/><br /><br />';

$contenuformulairegestionsujetforum .= '<input type="submit" name="envoyer" value="ENVOYER" />';

$contenuformulairegestionsujetforum .= '</form>';



//----------------------------------------

if (isset($_POST["rafraichir"])) {
    header('Location: v_gestionsujetforum.php');
}
?>