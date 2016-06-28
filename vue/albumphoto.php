<?php require_once ('../controleur/controlealbumphoto.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/styleheader.css" />
        <link rel="stylesheet" href="css/stylealbumphoto.css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css"/>
        <title>Inde du sud</title>

        <!-- Script Fancybox pour album photos -->

        <!-- Add jQuery library -->
        <script type="text/javascript" src="js/fancybox/jquery-1.10.1.min.js"></script>

        <!-- Add mousewheel plugin (this is optional) -->
        <script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>

        <!-- Add fancyBox main JS and CSS files -->
        <script type="text/javascript" src="js/fancybox/jquery.fancybox.js?v=2.1.5"></script>
        <link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />

        <!-- Add Button helper (this is optional) -->
        <link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox-buttons.css?v=1.0.5" />
        <script type="text/javascript" src="js/fancybox/jquery.fancybox-buttons.js?v=1.0.5"></script>

        <!-- Add Thumbnail helper (this is optional) -->
        <link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox-thumbs.css?v=1.0.7" />
        <script type="text/javascript" src="js/fancybox/jquery.fancybox-thumbs.js?v=1.0.7"></script>

        <!-- Add Media helper (this is optional) -->
        <script type="text/javascript" src="js/fancybox/jquery.fancybox-media.js?v=1.0.6"></script>


        <script type="text/javascript">
            $(document).ready(function () {
                /*
                 *  Button helper. Disable animations, hide close button, change title type and content
                 */

                $('.fancybox-buttons').fancybox({
                    openEffect: 'fade',
                    closeEffect: 'fade',
                    prevEffect: 'elastic',
                    nextEffect: 'elastic',
                    closeBtn: true,
                    helpers: {
                        title: {
                            type: 'inside'
                        },
                        buttons: {}
                    },
                    afterLoad: function () {
                        this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                    }
                });
            });
        </script>

    </head>

    <body>
        <div id="bloc_page">
            <?php include_once ('inclusions/header.php'); ?>
            <div id="backgroundalbumphoto">
                <section id="bloc_albumphoto">

                    <article>
                        <div id="colonneTamilnadu">
                            <div class="titreTn">
                                <h1>Tamil Nadu</h1>
                            </div>
                            <?php echo $flechehautamil; ?>
                            <?php echo $imagetamil; ?>
                            <?php echo $flechebastamil; ?>
                        </div>

                        <div id="colonneKerala">
                            <div class="titreKer">
                                <h1>Kerala</h1>
                            </div>
                            <?php echo $flechehautkerala; ?>
                            <?php echo $imagekerala; ?>
                            <?php echo $flechebaskerala; ?>
                        </div>

                        <div id="colonneKarnataka">
                            <div class="titreKar">
                                <h1>Karnataka</h1>
                            </div>
                            <?php echo $flechehautkarnataka; ?>
                            <?php echo $imagekarnataka; ?>
                            <?php echo $flechebaskarnataka; ?>
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
