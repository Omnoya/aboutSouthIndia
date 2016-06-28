<?php require_once ('../../controleur/controleur_admin/c_gestionsujetforum.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../css/styleadmin.css" />
        <title>Inde du sud</title>

        <!-- script tinymce -->
        <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinymce.init({
                selector: "textarea",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                language: "fr_FR",
                convert_urls: false
            });
        </script>

    </head>
    <body>
        <div id="bloc_page">
<?php include_once ('headeradmin.php'); ?>
            <div id="conteneur_indexadmin">
                <div id="conteneur_gestion_sujetforum">
                    <section>
<?php echo $contenuformulairegestionsujetforum ?>
                        <?php echo $contenuindicationsuppressionsujetforum ?>
                        <?php echo $contenutableaugestionsujetforum ?>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>

