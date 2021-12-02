// Jquery
var $ = require("jquery");
// Alpinejs
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// google analystic
const {
    gtag,
    install
} = require("ga-gtag");

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

// loading screen
$("#loading_screen").css({
    "visibility": "hidden"
})

// generate button

$('#generate').click(function (e) {
    e.preventDefault()
    $("#loading_screen").css({
        "visibility": "visible"
    })
    getMode($("#mode").val(), $("#aff_link").val())

});

// function get error code

function getErrorCode(value) {
    var errorCode = null
    switch (value) {
        case 0:
            errorCode = "Please add Keyword"
            break;
        case 1:
            errorCode = "Image Creation Failed (Contact Admin)"
            break;
        case 2:
            errorCode = "Failed Connect API (Contact Admin)"
            break;
    }
    return errorCode

}

// function update affiliate link

function updateAffiliateLink(aff_link, id) {
    $.ajax({
        url: "/request/index.php",
        type: "post",
        data: {
            action: 'update_aff_link',
            new_aff_link: aff_link,
            product_id: id
        },
        success: function (response) {
            if (response.status) {
                $("#loading_screen").css({
                    "visibility": "hidden"
                });
                var elem = '#aff_link_' + id
                $(elem).text(response.update_link)
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
}


function inputAffValue(id) {
    var elem = '#aff_link_' + id
    return $(elem).val()
}

// button edit affiliate link
$('[name="edit_aff_link"]').bind("click", function (e) {
    e.preventDefault()
    $("#loading_screen").css({
        "visibility": "visible"
    })
    updateAffiliateLink(inputAffValue($(this)[0].id), $(this)[0].id)
});

// get current page
var current_page = window.location.href
if (current_page.includes("admin")) {
    $('#listkeywords').html($('#listkeywords').html().trim());

    // external js dashboard admin
    const sidebar = document.getElementById('sidebar');

    const toggleSidebarMobile = (sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose) => {
        sidebar.classList.toggle('hidden');
        sidebarBackdrop.classList.toggle('hidden');
        toggleSidebarMobileHamburger.classList.toggle('hidden');
        toggleSidebarMobileClose.classList.toggle('hidden');
    }

    const toggleSidebarMobileEl = document.getElementById('toggleSidebarMobile');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const toggleSidebarMobileHamburger = document.getElementById('toggleSidebarMobileHamburger');
    const toggleSidebarMobileClose = document.getElementById('toggleSidebarMobileClose');
    const toggleSidebarMobileSearch = document.getElementById('toggleSidebarMobileSearch');

    toggleSidebarMobileSearch.addEventListener('click', () => {
        toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

    toggleSidebarMobileEl.addEventListener('click', () => {
        toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

    sidebarBackdrop.addEventListener('click', () => {
        toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

}

// function get generate mode

function getMode(value, aff_link) {
    var mode;
    switch (value) {
        case 'keyword':
            var kw = $("#listkeywords").val().split('\n')
            kw = JSON.stringify(kw)

            $.ajax({
                url: "/request/index.php",
                type: "post",
                data: {
                    action: 'generate',
                    aff_link: aff_link,
                    keywords: kw
                },
                success: function (response) {

                    try {
                        if (response.status == "success") {
                            $("#loading_screen").css({
                                "visibility": "hidden"
                            });
                            $("#status_generate").text(response.success_code)
                        } else if (response.status == "failed") {
                            $("#loading_screen").css({
                                "visibility": "hidden"
                            });
                            $("#status_generate").text(response.success_code)
                        }
                    } catch (e) {
                        if (response.includes("imagecreatefromstring()")) {
                            $("#status_generate").text(getErrorCode(1))

                        } else {

                            $("#status_generate").text("Failed")
                        }
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#loading_screen").css({
                        "visibility": "hidden"
                    });

                    if (textStatus.includes("imagecreatefromstring()")) {
                        $("#status_generate").text(getErrorCode(1))

                    } else {

                        $("#status_generate").text("Failed")
                    }


                }
            });
            break;
        case 'trending':


            $.ajax({
                url: "/request/index.php",
                type: "post",
                data: {
                    action: 'gettrending',
                    aff_link: aff_link,
                    keywords: kw
                },
                success: function (response) {

                    try {
                        if (response.status == "success") {
                            $("#loading_screen").css({
                                "visibility": "hidden"
                            });
                            $("#status_generate").text(response.success_code)
                        } else if (response.status == "failed") {
                            $("#loading_screen").css({
                                "visibility": "hidden"
                            });
                            $("#status_generate").text(response.success_code)
                        }
                    } catch (e) {
                        if (response.includes("imagecreatefromstring()")) {
                            $("#status_generate").text(getErrorCode(1))

                        } else {

                            $("#status_generate").text("Failed")
                        }
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#loading_screen").css({
                        "visibility": "hidden"
                    });

                    if (textStatus.includes("imagecreatefromstring()")) {
                        $("#status_generate").text(getErrorCode(1))

                    } else {

                        $("#status_generate").text("Failed")
                    }


                }
            });
            break;
    }
}