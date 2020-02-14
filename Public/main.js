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
    carousel();
    setInterval(carousel, 5000)
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
