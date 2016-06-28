<?php

require_once ('../../modele/fonction.php');

$contenumessagesuppressioncommentaire = "";
$contenugestioncommentaire = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "inactif";
$position5 = "actif";
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
        $intIdCommentaire = (int)$_GET['id_commentaire'];
	$pdo->exec("UPDATE commentaires SET activer=1 WHERE id_commentaire = $intIdCommentaire");
	
}
//-------------------------------------------------------------------------------------

if(isset($_GET['action']) && $_GET['action'] == 'supprimer')
{
	$contenumessagesuppressioncommentaire .= "Vous voulez supprimer le commentaire n° $_GET[id_commentaire]";
        $intIdCommentaire = (int)$_GET['id_commentaire'];
	$pdo->exec("DELETE FROM commentaires WHERE id_commentaire = $intIdCommentaire");
	
}
//-------------------------------------------------------------------------------------

$result = $pdo->query('SELECT * FROM commentaires', PDO::FETCH_ASSOC); // fetch

$contenugestioncommentaire .= '<h2>Bonjour, ici vous pouvez publier ou supprimer un commentaire.</h2><hr />';

$contenugestioncommentaire .= '<table cellpadding="5" cellspacing="0"><tr>';
for($i=0; $i < $result->ColumnCount(); $i++) //$content .= $result->ColumnCount();
{
	$colonne = $result->getColumnMeta($i);
	// print '<pre>'; print_r($colonne); print '</pre>';
	$contenugestioncommentaire .= '<th>' . $colonne['name'] . '</th>';
}
$contenugestioncommentaire .= '<th>Supprimer</th>';
$contenugestioncommentaire .= '<th>Envoyer</th>';
$contenugestioncommentaire .= '</tr>';
foreach($result as $indice => $valeur) 
{
	// print '<pre>'; print_r($valeur); print '</pre>';
	$contenugestioncommentaire .= '<tr>';
	foreach($valeur as $indice2 => $valeur2) 
	{
            $contenugestioncommentaire .= '<td>' . $valeur2 . '</td>';
	}
	$contenugestioncommentaire .= "<td><a href=\"?action=supprimer&id_commentaire=$valeur[id_commentaire]\" onClick=\"return confirm('En êtes vous certain ?');\">Supprimer</a></td>";
        
        $contenugestioncommentaire .= "<td><a href=\"?action=envoyer&id_commentaire=$valeur[id_commentaire]\" onClick=\"return confirm('En êtes vous certain ?');\">Envoyer</a></td>";
        
	$contenugestioncommentaire .= '</tr>';
}
$contenugestioncommentaire .= '</table>';
?>