function updateList() {
    $.ajax({
        type: "GET",
        url: "http://91.162.251.47:80/",
        success: function (data) {
            var string = "",
                i = 0,
                result = Object.values(data);
            while (i < result.length) {
                console.log(result[i]);
                string += "<div class='element' data-src='" + result[i].path + "'><h2>" + result[i].title + "</h2>" +
                    "<p><strong>" + result[i].title + "</strong><br><strong>" + result[i].author + " - " + result[i].date + "</strong><br>" + result[i].description + "</p></div>";
                i++;
            }
            console.log("HERE '" + string + "'");
            $(".list").html(string);
        }
    });
}

$(document).ready(function () {
    updateList();
    $("body").on("click", ".element", function () {
        $("#scene").toggleClass("show");
        $("#scene>.screen").html("<video controls=\"controls\" preload=\"metadata\"><source src='http://91.162.251.47:80/" + $(this).attr("data-src") + "' type='video/mp4'></video>")

    }).on("click", ".exit", function (event) {
        event.preventDefault();
        $($(this).attr("href")).toggleClass("show");
        $("#scene>.screen").html()
    })
    $("#refresh").on("click", function (event) {
        event.preventDefault();
        updateList();
    })
});