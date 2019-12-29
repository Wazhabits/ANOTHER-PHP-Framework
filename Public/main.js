$(document).ready(function () {
    $("body").on("click", ".element", function () {
        $("#scene").toggleClass("show");
        $("#scene>.screen").html("<video controls=\"controls\" preload=\"metadata\"><source src='http://91.162.251.47:80/" + $(this).attr("data-src") + "' type='video/mp4'></video>")

    }).on("click", ".exit", function (event) {
        event.preventDefault();
        $($(this).attr("href")).toggleClass("show");
        $("#scene>.screen").html()
    })
});