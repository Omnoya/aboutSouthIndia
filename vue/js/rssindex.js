$(document).ready(function () {
    function loadRSS() {
        $.get("vue/proxy.php", function (data) {
            $("#cube9").append("<table border='0' cellspacing='0' id='tbDATA'></table>");
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
                var a = $('<div id="titre_news">').append(titre);
                var img = $('<img>').attr({
                    src: imgSRC,
                });
                var tdIMG = $("<td>").append(img).append(a);
                $("#tbDATA").children("tbody").children("tr").last().append(tdIMG);

                //var tdA = $('<td>').append(a);
                //$("#tbDATA").children("tbody").children("tr").last().append(tdA);
            });

        });
    }
    loadRSS();
    setInterval("loadRSS();", 10000);
});