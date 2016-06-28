<?php

require_once ('../modele/fonction.php');
$contenuaffichagemessageforum = "";

$cube1 = "";
$cube2 = "";
$cube3 = "";

$contenunbremessageparsujet = "";
$contenusujet = "";

//variable qui sert à afficher les messages
$cube5 = "";

//variables qui serviront à la pagination
$flechedroite = "";
$flechegauche = "";

$boutonretour = "";

//variable utilisé dans header.php permettant d'atteindre un fichier à partir du fichier vue où on se situe
$chemin = "";
$chemin2 = "../";

// variables permettant d'attribuer une class="actif" ou class="inactif" pour avoir le boutton du menu enfoncé sur la page ou on se trouve (fichier header.php et styleheader.css)
$position = "inactif";
$position2 = "inactif";
$position3 = "inactif";
$position4 = "actif";
$position5 = "inactif";
$position6 = "inactif";
$position7 = "inactif";

if (!membreEstConnecte()) {
    header('Location: forum.php');
} else {
    if ($_SESSION['membre']['etat'] == 1) {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="vue_admin/v_indexadmin.php"><span class="fa fa-cog"></span> &nbsp; Accès au back-office</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="?action=deconnexion"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    } else {
        $content .= '<div class="apres_connexion">';
        $content .= '<div class="indication_membre"><span class="fa fa-user"></span><span class="indication">' . $bonjour = 'Bonjour ' . $_SESSION['membre']['pseudo'] . '</span></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="parametresmembre.php"><span class="fa fa-cog"></span> &nbsp; Mes paramètres</a></div>';
        $content .= '<div class="lien_parametre_deconnexion"><a class="item_lien" href="?action=deconnexion"><span class="fa fa-power-off"></span> &nbsp; Deconnexion</a></div>';
        $content .= '</div>';
    }



    if (isset($_GET["id_sujet"]) && !empty($_GET["id_sujet"])) {

        $intIdSujet = (int) $_GET["id_sujet"];

        //$nombre_de_msg_par_page = 1; // On met dans une variable le nombre de messages qu'on veut par page
        // On récupère le nombre total de messages
        //$reponse = $pdo->query("SELECT COUNT(*) AS nombre FROM messageforum WHERE activer=1 AND id_sujet=$intIdSujet");
        //$total_messages = $reponse->fetch();
        //$nombre_messages = $total_messages['nombre'];
//------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//Si on se trouve à la page une  ou plus
        if (isset($_GET['pagedesc']) AND $_GET['pagedesc'] > 0) {
//on vide les variables
            $flechedroite = "";
            $flechegauche = "";
            $cube5 = "";

//on récupère les informations via l'url
            $pagedesc = $_GET['pagedesc'];
            $debutselection = $_GET['debutselection'];
            $nombretotalentree = $_GET['nombretotalentree'];




            //--------------------------------------------------------------------------------------
            $comptemessageparsujet = $pdo->query("SELECT * FROM messageforum WHERE activer=1 AND id_sujet=$intIdSujet");
            $contenunbremessageparsujet .= '<div id="nbre_messageparsujet"><p id="taillenbre">' . $comptemessageparsujet->rowCount() . '</p><p>avis actuellement.</p></div>';
//--------------------------------------------------------------------------------------

            $donnees = $pdo->query("SELECT * FROM sujetforum WHERE id_sujet=$intIdSujet ");
            $recuptitre = $donnees->fetch(PDO::FETCH_ASSOC);
            
            $contenusujet .= "<div id=\"titre_sujetforum\"><h1>Sujet : $recuptitre[titre]</h1></div>";
            $contenusujet .= "<div id=\"description\"><p>$recuptitre[description]</p></div>";
            
//--------------------------------------------------------------------------------------------


            $reponse = $pdo->query('SELECT m.*, 
                               s.titre AS titreSujet,
                               u.nom,
                               u.prenom,
                               DATE_FORMAT(date_message, "Le %d/%m/%Y à %h:%i:%s") AS date_message
                        FROM messageforum AS m 
                        
                            INNER JOIN sujetforum AS s ON s.id_sujet = m.id_sujet
                            INNER JOIN membres AS u ON m.id_membre = u.id_membre
                        
                        WHERE m.activer=1 AND m.id_sujet=' . $intIdSujet . ' LIMIT ' . $debutselection . ',1');

            $messageforum = $reponse->fetch(PDO::FETCH_ASSOC);

//            while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC)) {
//            $contenuaffichagemessageforum .= '<div class="">';
//            $contenuaffichagemessageforum .= $donnees['prenom'] . ' ' . $donnees['nom'] . '<br/>';
//            $contenuaffichagemessageforum .= "$donnees[titreSujet] <br/>";
//            $contenuaffichagemessageforum .= "$donnees[message] <br/>";
//            $contenuaffichagemessageforum .= "$donnees[date_message] <br/>";
//            $contenuaffichagemessageforum .= '</div>';
//        }


            $cube5 .= "<div id=\"cube5\">";
            $cube5 .= "<div id=\"auteur_messageforum\"><h1>Posté par : $messageforum[prenom] $messageforum[nom]</h1></div>";
            $cube5 .= "<div id=\"messageforum\"><p>$messageforum[message]<p></div>";
            $cube5 .= "<div id=\"date\"><p>$messageforum[date_message]</p></div>";
            $cube5 .= "</div>";

//        $contenuforum .= '<div class="sujet_forum">';
//        $contenuforum .= "<div class=\"sujet\">";
//        $contenuforum .= "<h1><a href=\"affichageMessageForum.php?id_sujet=$sujetforum[id_sujet]\">$sujetforum[titre]</a></h1>";
//        $contenuforum .= "<span class=\"sujet_description\"> $sujetforum[description]</span></div>";
//        //$content .= "<div class=\"message\">$sujetforum[description]</div>";
//        $contenuforum .= "<a href=\"ajoutMessageForum.php?id_sujet=$sujetforum[id_sujet]\">deposer un message</a>";
//        $contenuforum .= '</div><hr />';
//    // Puis on fait une boucle pour écrire les liens vers chacune des pages
//    $contenuforum .= 'Page : ';
//    for ($i = 1; $i <= $nb_pages; $i++) {
//        $contenuforum .= '<a href="forum.php?page=' . $i . '">' . $i . '</a> ';
//    }
//on génère le lien vers la page précédente
            $_GET['pagedesc'] -= 1;
            $_GET['debutselection'] -= 1;
            $flechegauche .= "<div id=\"cube4\">";
            $flechegauche .= "<a href=\"?id_sujet=$intIdSujet&pagedesc=" . $_GET['pagedesc'] . "&debutselection=" . $_GET['debutselection'] . "&nombretotalentree=$nombretotalentree#ancre\">";
            $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
            $flechegauche .= "</a>";
            $flechegauche .= "</div>";


//on affiche le lien vers la page suivante
//Si on se trouve à la dernière entrée, on ne génère aucun lien
            $debutselection += 1;
            if ($debutselection < $nombretotalentree) {
//on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
//on commence par le numero et le type de page
                $pagedesc += 1;
//la valeur de début de détection dans la base de données
//Puis on génère le lien vers la page suivante
                $flechedroite .= "<div id=\"cube6\">";
                $flechedroite .= "<a href=\"?id_sujet=$intIdSujet&pagedesc=$pagedesc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
                $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
                $flechedroite .= "</a>";
                $flechedroite .= "</div>";
            } else {
//Sinon, il peut revenir à la première entrée
                $flechedroite .= "<div id=\"cube6\">";
                $flechedroite .= "<a href=\"affichagemessageforum.php?id_sujet=$intIdSujet#ancre\">";
                $flechedroite .= "<span class=\"fa fa-rotate-right\"></span>";
                $flechedroite .= "</a>";
                $flechedroite .= "</div>";
            }
        } elseif (isset($_GET['pageasc']) AND $_GET['pageasc'] > 0) {
            //on vide les variables
            $flechedroite = "";
            $flechegauche = "";
            $cube5 = "";

            //on récupère les informations via l'url
            $pageasc = $_GET['pageasc'];
            $debutselection = $_GET['debutselection'];
            $nombretotalentree = $_GET['nombretotalentree'];


                       //--------------------------------------------------------------------------------------
            $comptemessageparsujet = $pdo->query("SELECT * FROM messageforum WHERE activer=1 AND id_sujet=$intIdSujet");
            $contenunbremessageparsujet .= '<div id="nbre_messageparsujet"><p id="taillenbre">' . $comptemessageparsujet->rowCount() . '</p><p>avis actuellement.</p></div>';
//--------------------------------------------------------------------------------------
            
            $donnees = $pdo->query("SELECT * FROM sujetforum WHERE id_sujet=$intIdSujet ");
            $recuptitre = $donnees->fetch(PDO::FETCH_ASSOC);
            
            $contenusujet .= "<div id=\"titre_sujetforum\"><h1>Sujet : $recuptitre[titre]</h1></div>";
            $contenusujet .= "<div id=\"description\"><p>$recuptitre[description]</p></div>";
            
//--------------------------------------------------------------------------------------------
            
            
            
             $reponse = $pdo->query('SELECT m.*, 
                               s.titre AS titreSujet,
                               u.nom,
                               u.prenom,
                               DATE_FORMAT(date_message, "Le %d/%m/%Y à %h:%i:%s") AS date_message
                        FROM messageforum AS m 
                        
                            INNER JOIN sujetforum AS s ON s.id_sujet = m.id_sujet
                            INNER JOIN membres AS u ON m.id_membre = u.id_membre
                        
                        WHERE m.activer=1 AND m.id_sujet=' . $intIdSujet . ' LIMIT ' . $debutselection . ',1');
            

            $messageforum = $reponse->fetch(PDO::FETCH_ASSOC);

            $cube5 .= "<div id=\"cube5\">";
            $cube5 .= "<div id=\"auteur_messageforum\"><h1>Posté par : $messageforum[prenom] $messageforum[nom]</h1></div>";
            $cube5 .= "<div id=\"messageforum\"><p>$messageforum[message]<p></div>";
            $cube5 .= "<div id=\"date\"><p>$messageforum[date_message]</p></div>";
            $cube5 .= "</div>";

//on génère le lien vers la page précédente
            $_GET['pageasc'] -= 1;
            $_GET['debutselection'] -= 1;
            $flechedroite .= "<div id=\"cube6\">";
            $flechedroite .= "<a href=\"?id_sujet=$intIdSujet&pageasc=" . $_GET['pageasc'] . "&debutselection=" . $_GET['debutselection'] . "&nombretotalentree=$nombretotalentree#ancre\">";
            $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
            $flechedroite .= "</a>";
            $flechedroite .= "</div>";

            //on affiche le lien vers la page suivante
            //Si on se trouve à la dernière entrée, on ne génère aucun lien
            $debutselection += 1;
            if ($debutselection < $nombretotalentree) {
                //on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
                //on commence par le numero et le type de page
                $pageasc += 1;
                //la valeur de début de délection dans la base de données
                //Puis on génère le lien vers la page suivante
                $flechegauche .= "<div id=\"cube4\">";
                $flechegauche .= "<a href=\"?id_sujet=$intIdSujet&pageasc=$pageasc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
                $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
                $flechegauche .= "</a>";
                $flechegauche .= "</div>";
            } else {
                //Sinon, il peut revenir à la première entrée
                $flechegauche .= "<div id=\"cube4\">";
                $flechegauche .= "<a href=\"affichagemessageforum.php?id_sujet=$intIdSujet#ancre\">";
                $flechegauche .= "<span class=\"fa fa-rotate-left\"></span>";
                $flechegauche .= "</a>";
                $flechegauche .= "</div>";
            }
        } else {

            //$reponse = $pdo->query('SELECT * FROM sujetforum ORDER BY id_sujet DESC LIMIT 0,1');


           

            //--------------------------------------------------------------------------------------
            $comptemessageparsujet = $pdo->query("SELECT * FROM messageforum WHERE activer=1 AND id_sujet=$intIdSujet");
            $contenunbremessageparsujet .= '<div id="nbre_messageparsujet"><p id="taillenbre">' . $comptemessageparsujet->rowCount() . '</p><p>avis actuellement.</p></div>';
//--------------------------------------------------------------------------------------
            
            $donnees = $pdo->query("SELECT * FROM sujetforum WHERE id_sujet=$intIdSujet ");
            $recuptitre = $donnees->fetch(PDO::FETCH_ASSOC);
            
            $contenusujet .= "<div id=\"titre_sujetforum\"><h1>Sujet : $recuptitre[titre]</h1></div>";
            $contenusujet .= "<div id=\"description\"><p>$recuptitre[description]</p></div>";
            
//--------------------------------------------------------------------------------------------
            
            
             $reponse = $pdo->query('SELECT m.*, 
                               s.titre AS titreSujet,
                               u.nom,
                               u.prenom,
                               DATE_FORMAT(date_message, "Le %d/%m/%Y à %h:%i:%s") AS date_message
                        FROM messageforum AS m 
                        
                            INNER JOIN sujetforum AS s ON s.id_sujet = m.id_sujet
                            INNER JOIN membres AS u ON m.id_membre = u.id_membre
                        
                        WHERE m.activer=1 AND m.id_sujet=' . $intIdSujet . ' LIMIT 0,1');
            

            $messageforum = $reponse->fetch(PDO::FETCH_ASSOC);

            $cube5 .= "<div id=\"cube5\">";
            $cube5 .= "<div id=\"auteur_messageforum\"><h1>Posté par : $messageforum[prenom] $messageforum[nom]</h1></div>";
            $cube5 .= "<div id=\"messageforum\"><p>$messageforum[message]<p></div>";
            $cube5 .= "<div id=\"date\"><p>$messageforum[date_message]</p></div>";
            $cube5 .= "</div>";

//Si il y a plus d'une entrée
            if ($comptemessageparsujet->rowCount() > 1) {
                //on détermine les informations dont on aura besoin pour effectuer la pagination ascendante
                //on commence par le numero et le type de page
                $pagedesc = 1;
                //la valeur de début de délection dans la base de données
                $debutselection = 1;
                //le nombre total d'entrées
                $nombretotalentree = $comptemessageparsujet->rowCount();
                //Puis on génère le lien vers la page suivante
                $flechedroite .= "<div id=\"cube6\">";
                $flechedroite .= "<a href=\"?id_sujet=$intIdSujet&pagedesc=$pagedesc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
                $flechedroite .= "<span class=\"fa fa-caret-right\"></span>";
                $flechedroite .= "</a>";
                $flechedroite .= "</div>";

                //Et le lien vers le plus ancien commentaire
                $flechegauche .= "<div id=\"cube4\">";
                $flechegauche .= "<a href=\"?id_sujet=$intIdSujet&pageasc=$pagedesc&debutselection=$debutselection&nombretotalentree=$nombretotalentree#ancre\">";
                $flechegauche .= "<span class=\"fa fa-caret-left\"></span>";
                $flechegauche .= "</a>";
                $flechegauche .= "</div>";
            }
        }



// on détermine le nombre de pages
//        $nb_pages = ceil($nombre_messages / $nombre_de_msg_par_page);
// Maintenant, on va afficher les messages
// ---------------------------------------
//        if (isset($_GET['page'])) {
//            $page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse (livredor.php?page=4)
//        } else { // La variable n'existe pas, c'est la première fois qu'on charge la page
//            $page = 1; // On se met sur la page 1 (par défaut)
//        }
// On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
//        $premierMessageAafficher = ($page - 1) * $nombre_de_msg_par_page;
// On ferme la requête avant d'en faire une autre
//        $reponse->closeCursor();
//        $reponse = $pdo->query('SELECT m.*, 
//                               s.titre AS titreSujet,
//                               u.nom,
//                               u.prenom
//                        FROM messageforum AS m 
//                        
//                            INNER JOIN sujetforum AS s ON s.id_sujet = m.id_sujet
//                            INNER JOIN membres AS u ON m.id_membre = u.id_membre
//                        
//                        WHERE m.activer=1 AND m.id_sujet=' . $intIdSujet . ' LIMIT ' . $premierMessageAafficher . ', ' . $nombre_de_msg_par_page);
        //$contenunbremessageforum .= $reponse->rowCount() . ' message(s) actuellement <br /><br />';
//--------------------------------------------------------------------------------------
//debug($_SESSION);
//        while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC)) {
//            $contenuaffichagemessageforum .= '<div class="">';
//            $contenuaffichagemessageforum .= $donnees['prenom'] . ' ' . $donnees['nom'] . '<br/>';
//            $contenuaffichagemessageforum .= "$donnees[titreSujet] <br/>";
//            $contenuaffichagemessageforum .= "$donnees[message] <br/>";
//            $contenuaffichagemessageforum .= "$donnees[date_message] <br/>";
//            $contenuaffichagemessageforum .= '</div>';
//        }
//        $reponse->closeCursor();
// Puis on fait une boucle pour écrire les liens vers chacune des pages
//        $contenuaffichagemessageforum .= 'Page : ';
//        for ($i = 1; $i <= $nb_pages; $i++) {
//            $contenuaffichagemessageforum .= '<a href="affichageMessageForum.php?page=' . $i . '&id_sujet=' . $intIdSujet . '">' . $i . '</a> ';
//        }
        //affichage des 3 premières cubes lorsque le membre est connecté
        
        $boutonretour .= "<a href=\"forum.php\"><div id=\"lien_retour\"><span class=\"fa fa-reply\"></span> Retour vers les sujets du forum</div></a>";        
        
        $cube1 .= "<div id=\"cube1\">";
        $cube1 .= "<div id=\"smileface\"><span class=\"fa fa-smile-o\"></span></div>";
        $cube1 .= "<div id=\"contenucube1\"><p>VOICI LES AVIS SUR CE SUJET</p></div>";
        $cube1 .= "<div id=\"flechecube1\"><span class=\"fa fa-caret-right\"></span></div>";
        $cube1 .= "</div>";

        $cube2 .= "<div id=\"cube2\">";
        $cube2 .= "$contenusujet";
        $cube2 .= "</div>";

        $cube3 .= "<div id=\"cube3\">$contenunbremessageparsujet</div>";
    } else {
        header('Location: forum.php');
    }
}



if (isset($_GET['action']) AND $_GET['action'] == "deconnexion") {
    session_destroy();

    header('Location: forum.php?action=deconnexion');
}