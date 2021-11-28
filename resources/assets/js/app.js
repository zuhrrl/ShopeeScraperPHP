// Jquery
var $ = require("jquery");
// Alpinejs
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// google analystic
const { gtag, install } = require("ga-gtag");

install('G-0J9479YGFT');
gtag('config', 'G-0J9479YGFT');

// handling event

$('#search_keyword').on("keypress", function (e) {

    if (e.keyCode == 13) {

        e.preventDefault();
        var keyword = $("#search_keyword").val()
        console.log(keyword)
        window.location.href = "?search=" + keyword
    }
});


// button search
$('#search').click(function (e) {

    e.preventDefault();
    var keyword = $("#search_keyword").val()
    console.log(keyword)
    window.location.href = "?search=" + keyword
});

$("#loading_screen").css({
    "visibility": "hidden"
})

$('#generate').click(function (e) {
    e.preventDefault()
    $("#loading_screen").css({
        "visibility": "visible"
    })
    var kw = $("#listkeywords").val().split('\n')
    kw = JSON.stringify(kw)

    $.ajax({
        url: "/generate/index.php",
        type: "post",
        data: {
            action: 'generate',
            keywords: kw
        },
        success: function (response) {
            if (response.includes("success")) {
                $("#loading_screen").css({
                    "visibility": "hidden"
                });
                $("#status_generate").text("Success")



            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            $("#loading_screen").css({
                "visibility": "hidden"
            });
            $("#status_generate").text("Failed")


        }
    });
});

$('#trending').click(function (e) {
    console.log('clicked')
    e.preventDefault()
    $("#loading_screen").css({
        "visibility": "visible"
    });

    $.ajax({
        url: "/generate/index.php",
        type: "post",
        data: {
            action: 'gettrending',
        },
        success: function (response) {
            if (response.includes("success")) {
                $("#loading_screen").css({
                    "visibility": "hidden"
                })
                $("#status_generate").text("Success")


            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            $("#loading_screen").css({
                "visibility": "hidden"
            });
            $("#status_generate").text("Failed")


        }
    });
});