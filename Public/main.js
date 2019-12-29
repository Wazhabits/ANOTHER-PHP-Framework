$(document).ready(function () {
    $("body").on("click", ".element", function () {
        $("#scene").toggleClass("show");
        $("#scene>.screen").html("<video><source src='" + $(this).attr("data-src") + "' type='video/mp4'></video>")

    }).on("click", ".exit", function (event) {
        event.preventDefault();
        $($(this).attr("href")).toggleClass("show");
    })
});