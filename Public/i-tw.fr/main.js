function buildElement(element, index) {
    let string = "<div class='element' data-src='" + element.path + "'" +
    "style='background-image: url(\"http://91.162.251.47:80/" + element.image + "\");'>" +
        "<img src='http://91.162.251.47:80/" + element.image + "'>";
    if (index === 0)
        string +="<video controls=\"controls\" preload=\"metadata\">" +
        "<source data-quality=\"high\" src='http://91.162.251.47:80/" + element.path +"' type='video/mp4'>" +
        "</video>";
    string += "<div class='informations'><h2>" + element.title + "</h2><p><strong>" + element.title + "</strong><br>";
    if (element.author !== undefined)
        string += "<strong>" + element.author + "</strong><br>";
    if (element.season !== undefined)
        string += "<strong>Saison : " + element.season + "</strong><br>";
    if (element.date !== undefined)
        string += "<strong>" + element.date + "</strong><br>";
    string += element.description;
    return string + "</p>" +
        "<a href='#editInformations' class='edit' data-name='" + element.path + "'><i class=\"fas fa-edit\"></i></a>" +
        "<a href='#editInformations' class='delete' data-name='" + element.path + "'><i class=\"fas fa-trash\"></i></a>" +
        "</div></div>"
}

function updateList() {
    unsplash();
    $.ajax({
        type: "GET",
        url: "http://91.162.251.47:80/",
        success: function (data) {
            var string = "",
                i = 0,
                result = Object.values(data);
            while (i < result.length) {
                string += buildElement(result[i], i);
                i++;
            }
            $(".list").html(string);
        },
        complete: splash()
    });
}

function splash() {
    setTimeout(function () {
        $("#splash").fadeOut(500);
    }, 2000)
}
function unsplash() {
    $("#splash").fadeIn(250);
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
    }).on("click", ".element:not(:first-child)", function () {
        $("video").remove();
        $(this).prepend("<video controls=\"controls\" preload=\"metadata\">" +
            "<source data-quality=\"high\" src='http://91.162.251.47:80/" + $(this).attr("data-src") +"' type='video/mp4'>" +
            "</video>");
        $(this).prependTo($(this).parent());
        window.scrollTo({top: 0, behavior: 'smooth'});
    }).on("click", ".exit", function (event) {
        event.preventDefault();
        $("#scene").removeClass("show");
        $($(this).attr("href")).hide();
    });
    $("#refresh").on("click", function (event) {
        event.preventDefault();
        updateList();
    });
});