<?php

require_once ('../../modele/fonction.php');

$contenuindicationsuppressionmessageforum = "";
$contenugestionmessageforum = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "actif";
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

echo 'Bonjour ' . $_SESSION['membre']['pseudo'] . ' Bienvenue dans le backoffice<hr />';
//-------------------------------------------------------------------

if(isset($_GET['action']) && $_GET['action'] == 'envoyer')
{
        $intIdMessage = (int)$_GET['id_message'];
	$pdo->exec("UPDATE messageforum SET activer=1 WHERE id_message = $intIdMessage");
	
}
//-------------------------------------------------------------------------------------

if(isset($_GET['action']) && $_GET['action'] == 'supprimer')
{
	$contenuindicationsuppressionmessageforum .= "Vous voulez supprimer le message n° $_GET[id_message]";
	$pdo->exec("DELETE FROM messageforum WHERE id_message = '$_GET[id_message]'");
	// unlink() ... pour supprimer l'image de l'article.
}
//---------------------------------------------------
$result = $pdo->query('SELECT * FROM messageforum', PDO::FETCH_ASSOC); // fetch

$contenugestionmessageforum .= '<h2>Bonjour, voici les derniers messages du forum :<hr /></h2><br />';

$contenugestionmessageforum .= '<table cellpadding="5" cellspacing="0"><tr>';
for($i=0; $i < $result->ColumnCount(); $i++) //$content .= $result->ColumnCount();
{
	$colonne = $result->getColumnMeta($i);
	// print '<pre>'; print_r($colonne); print '</pre>';
	$contenugestionmessageforum .= '<th>' . $colonne['name'] . '</th>';
}
$contenugestionmessageforum .= '<th>Supprimer</th>';
$contenugestionmessageforum .= '<th>Envoyer</th>';
$contenugestionmessageforum .= '</tr>';
foreach($result as $indice => $valeur) // pour chaque messageforum
{
	// print '<pre>'; print_r($valeur); print '</pre>';
	$contenugestionmessageforum .= '<tr>';
	foreach($valeur as $indice2 => $valeur2) // pour chaque information
	{
            $contenugestionmessageforum .= '<td>' . $valeur2 . '</td>';
	}
	$contenugestionmessageforum .= "<td><a href=\"?action=supprimer&id_message=$valeur[id_message]\" onClick=\"return confirm('En êtes vous certain ?');\">Supprimer</a></td>";
	
        $contenugestionmessageforum .= "<td><a href=\"?action=envoyer&id_message=$valeur[id_message]\" onClick=\"return confirm('En êtes vous certain ?');\">Envoyer</a></td>";
        
        $contenugestionmessageforum .= '</tr>';
}
$contenugestionmessageforum .= '</table>';