function unSplash() {
    console.log("Unsplashing, welcome :D !");
    $(".page-wrapper").addClass("unsplash");
    $("#splash").fadeOut(1000);
}

function carousel() {
    let slide = ($(".carousel-home").attr("data-slide")) ? $(".carousel-home").attr("data-slide") : 0;
    if (slide === 0)
        $(".carousel-home").attr("data-slide", 0);
    else
        slide = slide % $(".carousel-home>.element").length;
    $(".element.show").fadeOut(1000).removeClass("show");
    $(".carousel-home>.element:nth-child(" + (slide + 1) + ")").fadeIn().addClass("show");
    $(".carousel-home").attr("data-slide", slide + 1);
}

imagecounter = 0;

$(document).ready(function () {
    $.ajax({
        type: "GET",
        url:  "api/statistic/view/" + encodeURI(window.location.pathname.replace("/", "|")),
        success: function (data) {
            console.log(data);
        },
        error: function () {
            console.log("Something went wrong, sorry");
        }
    });
    carousel();
    setInterval(carousel, 5000);
    $("#language>a").on("click", function (event) {
        event.preventDefault();
        $($(this).attr("href")).toggleClass("show");
    });
    $("body").on("click", "#choice>a", function (event) {
        event.preventDefault();
        let url = $(this).attr("href"),
            location = window.location.pathname.replace("/", "|");
        $.ajax({
            type: "GET",
            url:  url + encodeURI(location),
            success: function (data) {
                console.log(data);
            },
            error: function () {
              console.log("Something went wrong, sorry");
            }
        });
        $.get($(this).attr("href"), function (data) {
           console.log("Thanks for your vote");
        });
    })
});

if ($("img").length > 0) {
    $("img").on("load", function () {
        imagecounter++;
        console.log(imagecounter);
        if (imagecounter === $("img").length - 1)
            unSplash();
    });
} else {
    unSplash();
}
