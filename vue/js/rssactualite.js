$(document).ready(function () {
    function loadRSS() {
        $.get("proxy.php", function (data) {
            $("#conteneur_actualite").append("<table border='none' cellspacing='0' id='tbDATA'></table>");
            $(data).children("rss").children("channel").children("item").each(function () {
                //Extraction
                var myItem = this;
                var titre = $(myItem).children("title").text();
                var lien = $(myItem).children("link").text();
                var desc = $(myItem).children("description").text();
                var image = $(myItem).children("enclosure");
                var imgSRC = image.attr("url");
                //Mise en page
                $("#tbDATA").append("<tr></tr>");
                var a = $('<a>').attr({
                    href: lien,
                }).append(titre);
                var img = $('<img>').attr({
                    src: imgSRC,
                });
                var tdIMG = $("<td id='blocnews_img'>").append(img);
                $("#tbDATA").children("tbody").children("tr").last().append(tdIMG);

                var tdA = $("<td id='blocnews_lien'>").append(a);
                $("#tbDATA").children("tbody").children("tr").last().append(tdA);

                var tdDesc = $("<td id='blocnews_contenu'>").append(desc);
                $("#tbDATA").children("tbody").children("tr").last().append(tdDesc);


            });

        });
    }
    loadRSS();
    setInterval("loadRSS();", 10000);
});