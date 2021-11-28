<?php
// required for database
session_start();
include "../config/config.php";
include "../lib/imagewebp.php";


$api_url = "https://api.kaosqu.com/agcshopee/";
$productName;
$productDescription;
$productImages = [];
$productPrice;
$productLink;
$productItemId;
$productShopId;
$productThumbnail;
$productStock;
$productRating;
$productCategory;
$productReviewCount;
$productBrand;

// if post generate
if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case "generate":
            if ($_POST["keywords"] != "") {
                // ensure users is add keywords
                getbyKeywords($_POST["keywords"]);
            }
            break;
        case "gettrending":
            getTrending();
            break;
    }
}

if (isset($_GET["cron"])) {
    switch ($_GET["cron"]) {
        case "auto":
            getTrending();
            break;
    }
}

// get keywords from database
function getbyKeywords($keywords) {
    global $api_url;
    $keywords = json_decode($keywords);
    foreach ($keywords as $keyword) {
        $keyword = preg_replace("/\s+/", "", $keyword);
        $productgrabtype = $api_url."?action=get&keywords=".$keyword;
        $products = grabShopee($productgrabtype);
        // do curl
        connectShopee($products);
    }
}

// curl grab to shopee

function grabShopee($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    $resp = curl_exec($curl);
    return $resp;
}


// function get trending search
function getTrending() {
    global $api_url;
    $trends = grabShopee(
        $api_url."?action=gettrending"
    );
    $trends = json_decode($trends);
    $trends = $trends->data->querys;
    foreach ($trends as $trend) {
        $trend = $trend->text;
        $trend = preg_replace("/\s+/", "", $trend);
        $productgrabtype = $api_url."?action=get&keywords=".$trend;
        $products = grabShopee($productgrabtype);
        connectShopee($products);
    }
}
// get url path
function getProductUrl($productName, $itemid, $shopid)
{
    $url = preg_replace("/\s+/", "-", $productName);
    if (str_contains($url, "&")) {
        $url = str_replace("&", "-", $url);
    }
    if (str_contains($url, "/")) {
        $url = str_replace("/", "", $url);
    }
    if (str_contains($url, "!")) {
        $url = str_replace("!", "", $url);
    }
    if (str_contains($url, "+")) {
        $url = str_replace("+", "", $url);
    }
   
    if (str_contains($url, "---")) {
        $url = str_replace("---", "", $url);
    }
    if (str_contains($url, ".")) {
        $url = str_replace(".", "", $url);
    }
   
    if (str_contains($url, "|")) {
        $url = str_replace("|", "", $url);
    }
    if (str_contains($url, "#")) {
        $url = str_replace("#", "", $url);
    }
    if (str_contains($url, "]")) {
        $url = str_replace("]", "", $url);
    }
    if (str_contains($url, "[")) {
        $url = str_replace("[", "", $url);
    }
    if (str_contains($url, ",")) {
        $url = str_replace(",", "", $url);
    }
    if (str_contains($url, "%")) {
        $url = str_replace("%", "", $url);
    }
    if (str_contains($url, "--")) {
        $url = str_replace("--", "", $url);
    }
    if (str_contains($url, '"')) {
        $url = str_replace('"', "", $url);
    }
    if (str_contains($url, "”")) {
        $url = str_replace("”", "", $url);
    }
    if (str_contains($url, "?")) {
        $url = str_replace("?", "", $url);
    }
    // replace end url if contain symbol -
    if(str_contains(substr($url, -1), "-")) {
        $url = substr($url, 0, -1);
        $url = rtrim($url, "-");
    }
    
    $url = strtolower($url);
    return $url;
}

// get images path
function getProductImageUrl($image)
{
    $imageurl = "https://cf.shopee.co.id/file/{$image}";
    return $imageurl;
}

// get product price
function getProductPrice($price)
{
    $price = intval($price) / 100000; // divide by 1000 cause shopee returned billion price of real price
    // thousand separator
    return $price;
}

//grabbing
function connectShopee($products)
{
    global $conn;
    global $api_url;
    $products = json_decode($products)->items;
    foreach ($products as $product) {
        $productName = $product->item_basic->name;
        if (str_contains($productName, "?")) {
            $productName = str_replace("?", "", $productName);
        }
        $productItemId = $product->item_basic->itemid;
        $productShopId = $product->item_basic->shopid;
        $productImages = $product->item_basic->images;
        $productImages = json_encode($productImages);
        $productThumbnail = $product->item_basic->image;
        $productLink = getProductUrl(
            $productName,
            $productItemId,
            $productShopId
        );
        $productPrice = $product->item_basic->price;
        $productPrice = getProductPrice($productPrice);
        // next curl to get product description
        $grabProduct = grabShopee(
            $api_url."?action=getproductdata&product_item_id=".$productItemId."&product_shop_id=".$productShopId
        );
        $grabProduct = json_decode($grabProduct);
        $productDescription = $grabProduct->data->description;
        $productStock = $grabProduct->data->stock;
        $productRating = $grabProduct->data->item_rating->rating_star;
        $productCategory = $grabProduct->data->categories[0]->display_name;
        $productReviewCount = $grabProduct->data->item_rating->rating_count[0];
        $productBrand = $grabProduct->data->brand;
        $productBrand = $productBrand != null ? $productBrand : "Unknown";

        if ($productDescription != null) {
            $productDescription = $productDescription;
        } else {
            $productDescription = "";
        }
        /*
        echo $productName."<br><br>";
        echo getProductUrl($productName, $productItemId, $productShopId)."<br><br>";
        echo getProductImageUrl($productImages[0])."<br><br>";
        echo getProductImageUrl($productImages[0])."<br><br>";
        echo getProductPrice($productPrice)."<br><br>";
        echo $productDescription."<br><br>";
        */
        $productDescription = htmlentities(
            $productDescription,
            ENT_QUOTES,
            "UTF-8"
        );

        // submitting to sql server
        $user_check_query = "SELECT * FROM products WHERE product_itemid='$productItemId' OR product_shopid='$productShopId' LIMIT 1";
        $result = mysqli_query($conn, $user_check_query);
        $isProductExist = mysqli_fetch_assoc($result);

        if (
            !$isProductExist &&
            $productDescription != null &&
            strlen($productName) > 22 &&
            !str_contains($productDescription, "????????????????????????") &&
            $productReviewCount > 1
        ) {
            // if product not exist
            // filter if description null
            // filter if product name < 22 character

            // convert to webp before store it
            $thumbnail_url = getProductImageUrl($productThumbnail);
            $filename = $productThumbnail;
            convertImageToWebP(
                $thumbnail_url,
                $filename
            );
            $localThumbnail = "/assets/images/".$filename.".webp";

            $query = "INSERT INTO products (product_name, product_description, product_images, product_price, product_link, product_itemid, product_shopid, product_thumbnail, product_stock, product_rating, product_category, product_review_count, product_brand) 
                VALUES('$productName','$productDescription', '$productImages', '$productPrice', '$productLink', '$productItemId', '$productShopId', '$localThumbnail', '$productStock', '$productRating', '$productCategory', '$productReviewCount', '$productBrand')";
            /* change character set to utf8 */
            if (!$conn->set_charset("utf8")) {
            } else {
                $conn->character_set_name();
            }
            if ($conn->query($query) === true) {
                $data = [
                    "generate_status" => "success",
                ];
                echo json_encode($data);
            }
        } else {
            echo "skippping this products";
        }
    }
}
?>

<!-- Header -->

<?php 
 $page_subtitle = "Admin Panel"; // Subtitle contoh: Example | Subtitle
 $page_description ="A Powerfull Shopee Auto Content Generator"; // Description untuk meta
 $page_name = $website_name." | ".$page_subtitle;
 $title = $page_name;


include '../partials/header.php'; ?>

<body class="bg-gray-200">

    <div id="loading_screen" class="w-full h-full fixed block top-0 left-0 bg-white opacity-75 z-50">
        <span class="text-blue-500 opacity-75 top-1/2 my-0 mx-auto block relative w-0 h-0" style="
    top: 50%;">
            <i class="fas fa-circle-notch fa-spin fa-5x"></i>
        </span>
    </div>

    <section class="w-full text-gray-900 py-36 bg-center bg-cover bg-no-repeat"
        style="background:url('https://images.unsplash.com/photo-1623479322729-28b25c16b011?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=1280')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4 flex items-center justify-center">
            <div class="lg:w-3/6 lg:pr-0 pr-0">
                <h1 class="font-medium text-5xl text-white">Hello Brou. This is how you thread your Shopee content</h1>
                <p class="leading-relaxed mt-4 text-white">
                    Ga perlu ribet buat content yakan. Come on duds saatnya seting keywordnya dulu. Ingat!. Kerja
                    dudukan gajinya sarungan :v</p>
            </div>
            <div
                class="lg:w-3/6 xl:w-2/5 md:w-full bg-gray-50 p-8 flex flex-col lg:ml-auto w-full mt-10 lg:mt-0 rounded-md">

                <div class="relative mb-4">
                    <label for="keyword" class="leading-7 text-sm text-gray-600">Keywords:</label>
                    <textarea id="listkeywords" placeholder="Input keyword separated by | example: keyword|keyword|" id="message"
                        name="message" rows="4"
                        class="w-full bg-white rounded-md border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-sm outline-none text-gray-900 py-1 px-3 leading-8 transition-colors duration-150 ease-in-out"> </textarea>
                </div>

                <p class="text-center ltexeading-relaxed m-2 text-gray-600">
            Status: <span id="status_generate"></span>    
            </p>

                <button id="generate"
                    class="text-white bg-indigo-500 rounded-md border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 text-lg">Generate</button>
                <button id="trending"
                    class="mt-2 text-white bg-indigo-500 rounded-md border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 text-lg">Trending</button>
            </div>
        </div>
    </section>
</body>

 <!-- Footer -->

 <?php include "../partials/footer.php"; ?>

</html>