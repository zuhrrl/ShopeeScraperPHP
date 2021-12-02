<?php
session_start();
// config connect database
include "config/config.php";
header('Content-type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';


$pages = $conn->query("SELECT product_link FROM products ORDER BY id ASC");
/*$article = $db->query('SELECT articleSlug FROM techno_blog ORDER BY articleId ASC');
$category = $db->query('SELECT categorySlug FROM techno_category ORDER BY categoryId ASC');
$tag= $db->query('SELECT articleTags FROM techno_blog ORDER BY articleId ASC');
*/

//define your base URLs
//Main URL
$base_url = $_SERVER['SERVER_NAME'];
$product_url = "https://".$base_url."/product/";

/*//Page base URL 
$page_base_url = "https://localhost/blog/page/";
//Category base URL
$category_base_url = "https://localhost/blog/category/";
//tag base URL
$tag_base_url = "https://localhost/blog/tag/";
*/

header("Content-Type: application/xml; charset=utf-8");

echo '<!--?xml version="1.0" encoding="UTF-8"?-->' . PHP_EOL;

echo '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:schemalocation="https://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' .
    PHP_EOL;
echo "<url>" . PHP_EOL;
echo "<loc>" . "https://".$base_url . "</loc>" . PHP_EOL;
echo "<changefreq>daily</changefreq>" . PHP_EOL;
echo "</url>" . PHP_EOL;

$sql =
    "SELECT product_link FROM products ORDER BY id DESC LIMIT 50";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($product = $result->fetch_assoc()) {
        $link =  $product['product_link'];
        echo '<url>' . PHP_EOL;
        echo '<loc>'.$product_url. $link .'</loc>' . PHP_EOL;
        echo '<changefreq>daily</changefreq>' . PHP_EOL;
        echo '</url>' . PHP_EOL;
    }
}

echo "</urlset>" . PHP_EOL;
?>
