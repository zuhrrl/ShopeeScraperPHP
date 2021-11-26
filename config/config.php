<?php
    $servername = "localhost";
    $database = "ngesopi";
    $username = "root";
    $password = "";
    $website_name = "Kaosqu.com";
    $page_subtitle = "Voucher Shopee Gratis Ongkir dan Diskon Terbaru";
    $page_description ="Belanja Online dari Brand Ternama 100% Original & Gratis Ongkir";
    $page_name = $website_name." | ".$page_subtitle;
    $title = $page_name;
    $og_image;
  
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    $conn->set_charset("utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

   
    
?>