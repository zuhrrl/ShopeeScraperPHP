<?php

    // Database Config 
    $servername = "localhost";
    $database = "ngesopi";
    $username = "root";
    $password = "";

    // Create connection
    //Don't Remove anything just change Database Config
    $conn = mysqli_connect($servername, $username, $password, $database);
    $conn->set_charset("utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Website Config atur website agan disini
    $website_name = "Example.com"; // Nama domain
    $page_subtitle = "Voucher Shopee Gratis Ongkir dan Diskon Terbaru"; // Subtitle contoh: Example | Subtitle
    $page_description ="Belanja Online dari Brand Ternama 100% Original & Gratis Ongkir"; // Description untuk meta
    $page_name = $website_name." | ".$page_subtitle;
    $title = $page_name;
    $og_image = "https://kaosqu.com/assets/images/ogimage.png";
?>