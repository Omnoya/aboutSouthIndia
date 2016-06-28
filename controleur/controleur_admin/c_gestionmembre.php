<?php

require_once ('../../modele/fonction.php');

$contenumessagesuppressionmembre = "";
$contenugestionmembre = "";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "actif";
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

if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
    $contenumessagesuppressionmembre .= "Vous voulez supprimer le membre n° $_GET[id_membre]";
    $pdo->exec("DELETE FROM membres WHERE id_membre = '$_GET[id_membre]'");
}

//On va afficher les données dans un tableau, comme avant
//On commence par récupérer les données
$result = $pdo->query('SELECT * FROM membres', PDO::FETCH_ASSOC);

$contenugestionmembre .= '<h2>Bonjour, ici vous pouvez modifier ou supprimer les membres :<hr /></h2><br />';
//On commence par afficher les titres des colonnes du tableau
$contenugestionmembre .= '<table cellspacing="0"><tr>';
for ($i = 0; $i < $result->ColumnCount(); $i++) {
    $colonne = $result->getColumnMeta($i);
    $contenugestionmembre .= '<th>' . $colonne['name'] . '</th>';
}
//Et on ajoute la colonne qui correspond à l'action de modifier
$contenugestionmembre .= '<th>Modifier</th>';
$contenugestionmembre .= '<th>Supprimer</th>';
$contenugestionmembre .= '</tr>';

//On va afficher les données à modifier
foreach ($result as $indice => $valeur) {
    //On va déclarer la balise qui initialise le formulaire
    $contenugestionmembre .= '<form method="post">';
    $contenugestionmembre .= '<tr>';
//On déclare une variable qui s'incrémentera à chaque itération de la boucle
    $i = 0;
    foreach ($valeur as $indice2 => $valeur2) {
        $contenugestionmembre .= '<td><input type="text" value="' . $valeur2 . '" name="champs' . $i . '"/></td>';
        $i++;
    }

    $contenugestionmembre .= "<td><button type=\"submit\" name=\"validermo\" id=\"modifier\"/>Modification</button></td>";
    $contenugestionmembre .= "<td><a href=\"?action=supprimer&id_membre=$valeur[id_membre]\" onClick=\"return confirm('En êtes vous certain ?');\">Supprimer</a></td>";
    $contenugestionmembre .= '</tr>';
    $contenugestionmembre .= '</form>';
}
$contenugestionmembre .= '</table>';

//Si on modifie un ligne
if (isset($_POST['validermo'])) {
    $requete = $pdo->exec("UPDATE membres SET etat='" . $_POST['champs1'] . "', pseudo='" . $_POST['champs2'] . "', nom='" . $_POST['champs3'] . "', prenom='" . $_POST['champs4'] . "', email='" . $_POST['champs5'] . "', mdp='" . $_POST['champs6'] . "', cle='" . $_POST['champs7'] . "', actif='" . $_POST['champs8'] . "' WHERE id_membre = '" . $_POST['champs0'] . "'");
    header("refresh:0");
}
?>

