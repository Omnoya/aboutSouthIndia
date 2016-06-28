<?php

require_once ('../modele/fonction.php');

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

//pagination
// on récupère le nb total d'images tamilNadu
$reqTn = $pdo->query('SELECT id_image FROM image WHERE etat_sud = "tamilNadu"');
$nbre_total_imgTn = $reqTn->rowCount();

$nbre_imgTn_par_page = 2;  //nombre d'images qu'on veut par page

$nbre_pages_max_gauche_et_droite_tn = 2;

$nbre_pages_tn = ceil($nbre_total_imgTn / $nbre_imgTn_par_page); // on récupère le nb de page, ceil() arrondit au nombre supérieur
//savoir sur quelle page on se trouve
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page_num_tn = $_GET['page'];
} else {
    $page_num_tn = 1;
}

if ($page_num_tn < 1) {
    $page_num_tn = 1;
} elseif ($page_num_tn > $nbre_pages_tn) {
    $page_num_tn = $nbre_pages_tn;
}

$limit = 'LIMIT ' . ($page_num_tn - 1) * $nbre_imgTn_par_page . ',' . $nbre_imgTn_par_page;

//cette requete sera utilisée plus tard
$sql = "SELECT etat_sud, nom_image, contenu FROM image WHERE etat_sud = \"tamilNadu\" ORDER BY id_image DESC $limit";

$pagination = '';

if ($nbre_pages_tn != 1) {
    if ($page_num_tn > 1) {
        $previous = $page_num_tn - 1;
        $pagination .= '<a href="index.php?page=' . $previous . '">Précédent</a> &nbsp; &nbsp;';

        for ($i = $page_num_tn - $nbre_pages_max_gauche_et_droite_tn; $i < $page_num_tn; $i++) {
            if ($i > 0) {
                $pagination .= '<a href="index.php?page=' . $i . '">' . $i . '</a> &nbsp;';
            }
        }
    }
    
    $pagination .= '<span class="active">'.$page_num_tn.'</span>&nbsp;';
    for($i = $page_num_tn+1; $i <= $nbre_pages_tn; $i++){
        $pagination .= '<a href="index.php?page=' . $i . '">' . $i . '</a>';
        if($i >= $page_num_tn + $nbre_pages_max_gauche_et_droite_tn){
            break;
        }
    }
    if($page_num_tn != $nbre_pages_tn){
        $next = $page_num_tn + 1;
        $pagination .= '<a href="index.php?page=' . $next . '">Suivant</a>';
    }
}

//---------------------------------------------------------------------------