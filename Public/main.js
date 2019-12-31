function buildElement(element) {
    let string = "<div class='element' data-src='" + element.path + "'" +
    "style='background-image: url(\"http://91.162.251.47:80/" + element.image + "\");'><h2>" + element.title + "</h2><p>";
    string += "<strong>" + element.title + "</strong><br>";
    if (element.author !== undefined)
        string += "<strong>" + element.author + "</strong><br>";
    if (element.season !== undefined)
        string += "<strong>Saison : " + element.season + "</strong><br>";
    if (element.date !== undefined)
        string += "<strong>" + element.date + "</strong><br>";
    string += element.description;
    return string + "</p><a href='#editInformations' class='edit' data-name='" + element.path + "'><i class=\"fas fa-edit\"></i></a><a href='#editInformations' class='delete' data-name='" + element.path + "'><i class=\"fas fa-trash\"></i></a></div>"
}

function updateList() {
    $.ajax({
        type: "GET",
        url: "http://91.162.251.47:80/",
        success: function (data) {
            var string = "",
                i = 0,
                result = Object.values(data);
            while (i < result.length) {
                string += buildElement(result[i]);
                i++;
            }
            $(".list").html(string);
        }
    });
}

$(document).ready(function () {
    var apiURL = "http://91.162.251.47/";
    updateList();
    $('select').on("change", function() {
        var src = $(this).next().attr("data-src");
        $("#scene>.screen").html(
            "<video controls=\"controls\" preload=\"metadata\">" +
            "<source data-quality=\"low\" src='http://91.162.251.47:80/" + src + $(this).val() +"' type='video/mp4'>" +
            "</video>")
    });
    $("body").on("click", "a.edit", function (event) {
        event.preventDefault();
        $.ajax({
            type: "GET",
            url: apiURL + "?action=get&file=" + encodeURI($(this).parent().attr("data-src")),
            success: function (data) {
                $('#edition input[type="text"]').each(function () {
                    if (data[$(this).attr("id")] !== undefined)
                        $('#edition input#' + $(this).attr("id")).val(data[$(this).attr("id")]);
                })
            }
        });
        $("#edition").attr("data-name", $(this).attr("data-name")).fadeIn().find("#name").val($(this).attr("data-name"));
    }).on("click", "a.delete", function (event) {
        event.preventDefault();
        if (confirm("Delete file ?")) {
            $.ajax({
                type: "GET",
                url: apiURL + "?action=delete&file=" + encodeURI($(this).parent().attr("data-src")),
                success: function (data) {
                    //alert("Success");
                    //window.location.reload();
                }
            });
        }
    }).on("click", "#edit", function (event) {
        event.preventDefault();
        var urlFinal = "?action=update";
        $(this).parent().find('input[type="text"]').each(function () {
            urlFinal += "&" + $(this).attr("id") + "=" + encodeURI($(this).val());
        });
        $.ajax({
            type: "GET",
            url: apiURL + urlFinal,
            complete: function (data) {
                window.location.reload();
            }
        })
    }).on("click", ".element", function () {
        var src = $(this).attr("data-src");
        $("#scene>.screen").html();
        $("#scene").show().addClass("show");
        $("#scene>.screen").attr("data-src", src);
    }).on("click", ".exit", function (event) {
        event.preventDefault();
        $("#scene").removeClass("show");
        $($(this).attr("href")).hide();
    });
    $("#refresh").on("click", function (event) {
        event.preventDefault();
        updateList();
    })
});