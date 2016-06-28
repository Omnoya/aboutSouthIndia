<?php require_once ('controleur/controleindex.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="vue/css/styleheader.css" />
        <link rel="stylesheet" href="vue/css/style.css" />
        <link rel="stylesheet" href="vue/css/font-awesome/css/font-awesome.min.css"/>

        <!-- Script Actualités -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="vue/js/rssindex.js"></script>

        <title>Inde du sud</title>
    </head>

    <body>
        <div id="bloc_page">

            <?php include_once ('vue/inclusions/header.php'); ?>

            <div id="slider">
                <figure>
                    <img src="vue/img/imgSlide/slider1.jpg" alt="slider1" >
                    <img src="vue/img/imgSlide/slider2.jpg" alt=" slider2">
                    <img src="vue/img/imgSlide/slider3.jpg" alt="slider3" >
                    <img src="vue/img/imgSlide/slider4.jpg" alt="slider4">
                    <img src="vue/img/imgSlide/slider1.jpg" alt="slider1">
                </figure>
            </div>

            <div id="backgroundcubes">
                <section id="bloc_cube">

                    <article class="cube">
                        <div id="cube1">
                            <img src="vue/img/imgAdmin/cube1.jpg" alt="cube1" />
                        </div>

                        <div id="cube_t1">
                            <a href="vue/article.php?page=<?php echo $comptetamil ?>">
                                <h1>Tamil Nadu</h1>
                                <img src="vue/img/imgAdmin/iconemonde.png" alt="iconemonde"/>
                            </a>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube2">
                            <div id="bloc_titre_forum"><h1>Dernier sujet posté sur le forum :</h1></div>
                            <div id="titre_forum"><h2><?php echo $contenutitreforum ?></h2></div>
                            <div id="contenu_forum"><p><?php echo $contenudescriptionforum ?></p></div>
                            <div id="lien_forum">
                                <a href="vue/forum.php">
                                    <span class="fa fa-plus fa-3x"></span>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube3">
                            <img src="vue/img/imgAdmin/cube3.jpg" alt="cube3" />
                        </div>

                        <div id="cube_t3">
                            <a href="vue/article.php?page=<?php echo $comptekerala ?>">
                                <h1>Kérala</h1>
                                <img src="vue/img/imgAdmin/iconemonde.png" alt="iconemonde" />
                            </a> 
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube4">
                            <div id="titre_commentaire"><h1>Dernier commentaire posté :</h1></div>
                            <div id="date_commentaire"><p><?php echo $contenudatecommentaire ?></p></div>
                            <div id="pseudo_commentaire"><p><?php echo "par $contenupseudocommentaire" ?></p></div>
                            <div id="contenu_commentaire"><p><?php echo $contenucommentaire ?></p></div>
                            <div id="lien_commentaire">
                                <a href="vue/commentaire.php">
                                    <span class="fa fa-plus fa-3x"></span>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube5"><img src="vue/img/imgAdmin/cube5.jpg" alt="cube5" /></div>
                        <div id="cube_t5">
                            <div id="contenucube5">
                                <a href="vue/article.php">
                                    <h1>Découvrez l’Inde du Sud !</h1>
                                    <p>
                                        About South India est un site dédié à l’Inde du Sud. Il rassemble les informations                                        de base qui vous fourniront une vue d’ensemble sur les 3 états du sud : le Tamil                                          Nadu, le Kérala et le Karnataka. Vous découvrirez ...
                                    </p>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube6">
                            <div id="bloc_titre_article"><h1>Dernier article posté :</h1></div>
                            <div id="date_article"><p><?php echo $contenudatearticle ?></p></div>
                            <div id="titre_article"><h2><?php echo $contenutitrearticle ?></h2></div>
                            <div id="lien_article">
                                <a href="vue/article.php?page=<?php echo $compte ?>">
                                    <span class="fa fa-plus fa-3x"></span>
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube7">
                            <img src="vue/img/imgAdmin/cube7.jpg" alt="cube7" />
                        </div>

                        <div id="cube_t7">
                            <a href="vue/article.php?page=<?php echo $comptekarnataka ?>">
                                <h1>Karnataka</h1>
                                <img src="vue/img/imgAdmin/iconemonde.png" alt="iconemonde" />
                            </a> 
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube8">
                            <h1>Album Photos</h1>
                            <div id="albphoto"><?php echo $contenualbumphoto ?></div>
                            <a href="vue/albumphoto.php">
                                <span class="fa fa-plus fa-3x"></span>
                            </a>
                        </div>
                    </article>

                    <article class="cube">
                        <div id="cube9"></div>
                        <div id="cube_t9">
                            <div id="contenucube9">
                                <a href="vue/actualite.php">
                                    <h1>NEWS</h1>
                                    <p>Consultez les dernières actualités indiennes...</p>
                                    <span class="fa fa-rss fa-3x"></span>
                                </a>
                            </div>
                        </div>
                    </article>
                </section>
            </div>
            <footer id="footer">
                <p>&copy; Copyright About South India 2015<p>
            </footer>
        </div>
    </body>
</html>