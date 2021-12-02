<?php
// required for database
session_start();
include("config/config.php");

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

//Main URL
$base_url = $_SERVER['SERVER_NAME'];
$product_url = "/product/";

$no_of_records_per_page = 8;
$offset = ($page-1) * $no_of_records_per_page;

$productlink = null;
$total_pages_sql = "SELECT COUNT(*) FROM products";
$result = mysqli_query($conn, $total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

// checking if product not found in url error 404
function is_404($url)
{
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    /* Get the HTML or whatever is linked in $url. */
    $response = curl_exec($handle);

    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    /* If the document has loaded successfully without any redirection or error */
    if ($httpCode >= 200 && $httpCode < 300) {
        return false;
    } else {
        return true;
    }
}


?>

<!-- Header -->

<?php include 'partials/header.php'; ?>

<body class="bg-gray-200">


    <div x-data="{ cartOpen: false , isOpen: false }" class="bg-white">
        <header>
            <div class="container mx-auto px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="hidden w-full text-gray-600 md:flex md:items-center">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="https://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16.2721 10.2721C16.2721 12.4813 14.4813 14.2721 12.2721 14.2721C10.063 14.2721 8.27214 12.4813 8.27214 10.2721C8.27214 8.06298 10.063 6.27212 12.2721 6.27212C14.4813 6.27212 16.2721 8.06298 16.2721 10.2721ZM14.2721 10.2721C14.2721 11.3767 13.3767 12.2721 12.2721 12.2721C11.1676 12.2721 10.2721 11.3767 10.2721 10.2721C10.2721 9.16755 11.1676 8.27212 12.2721 8.27212C13.3767 8.27212 14.2721 9.16755 14.2721 10.2721Z"
                                fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.79417 16.5183C2.19424 13.0909 2.05438 7.39409 5.48178 3.79417C8.90918 0.194243 14.6059 0.054383 18.2059 3.48178C21.8058 6.90918 21.9457 12.6059 18.5183 16.2059L12.3124 22.7241L5.79417 16.5183ZM17.0698 14.8268L12.243 19.8965L7.17324 15.0698C4.3733 12.404 4.26452 7.97318 6.93028 5.17324C9.59603 2.3733 14.0268 2.26452 16.8268 4.93028C19.6267 7.59603 19.7355 12.0268 17.0698 14.8268Z"
                                fill="currentColor" />
                        </svg>
                        <span class="mx-1 text-sm">ID</span>
                    </div>
                    <h1
                        class="md:text-center text-2xl font-semibold text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-indigo-500">
                        <?php echo $website_name;?>
                    </h1>
                    <div class="flex items-center justify-end w-full">


                        <div class="flex sm:hidden">
                            <button @click="isOpen = !isOpen" type="button"
                                class="text-gray-600 hover:text-gray-500 focus:outline-none focus:text-gray-500"
                                aria-label="toggle menu">
                                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current">
                                    <path fill-rule="evenodd"
                                        d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <?php include 'partials/navigation.php'; ?>


                <div class="relative mt-6 max-w-lg mx-auto">
                    <span id="search" class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>

                    <input id="search_keyword"
                        class="w-full border rounded-md pl-10 pr-4 py-2 focus:border-blue-500 focus:outline-none focus:shadow-outline"
                        type="text" placeholder="Search">
                </div>
            </div>
        </header>

        <main class="my-8">
            <section>

                <div class="container mx-auto px-6">
                    <h3 class="text-gray-700 text-2xl font-medium">Produk Terlaris</h3>
                    <span class="mt-3 text-sm text-gray-500"><?php echo $total_rows;?>+ Products</span>
                    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">


                        <?php
                        // get images path
                        function getProductImageUrl($image)
                        {
                            $imageurl = "https://cf.shopee.co.id/file/{$image}";
                            return $imageurl;
                        }

                        // format paragraph
                        function formatParagraph($text)
                        {
                            $text = str_replace("\r\n", "\n", $text);
                            $paragraphs = preg_split("/[\n]{2,}/", $text);
                            foreach ($paragraphs as $key => $p) {
                                $paragraphs[$key] =
                                    "<p class='text-gray-700  text-sm'>" .
                                    str_replace("\n", "<br />", $paragraphs[$key]) .
                                    "</p>";
                            }
            
                            $text = implode("", $paragraphs);
                            return $text;
                        }

                        // remove space
                        function removeSpace($desc)
                        {
                            $desc = str_replace(array("\r", "\n"), '', $desc);
                            return $desc;
                        }

                        /* change character set to utf8 */
                        if (!$conn->set_charset("utf8")) {
                        } else {
                            $conn->character_set_name();
                        }


                        if (isset($_GET['search'])) {
                            $sql = "SELECT product_name, product_description, product_images, product_price, product_link, product_itemid, product_shopid, product_thumbnail,product_stock, product_rating, product_category, product_review_count, product_brand FROM products WHERE product_name LIKE '{$searchQuery}%' ORDER BY id DESC LIMIT $offset, $no_of_records_per_page ";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row
                                while ($product = $result->fetch_assoc()) {
                                    $productImage = $product['product_thumbnail'];
                                    $productName = $product['product_name'];
                                    $productDescription = $product['product_description'];
                                    $productName =  preg_replace('/\s+/', ' ', $productName);
                                    if (strlen($productName) <=50) {
                                        $productName = "Sedang ada Promo ".$productName;
                                    }
                                    $productName = substr($productName, 0, 46);

                                    $productDescription =  preg_replace('/\s+/', ' ', $productDescription);
                                  
                                    $productDescription = substr($productDescription, 0, 85);
                                    
                                    
                                    
                                    $productlink = $product_url.$product['product_link'];
                                    $productPrice = $product['product_price'];
                                    $productPrice = number_format($productPrice);

                           

                                    echo "
                                    <article>
                                    <div class='w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden'>
                                    <div class='flex items-end justify-end h-56 w-full bg-cover'
                                        style='background-image: url({$productImage})'>
                                        <button
                                            class='p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500'>
                                            <span class='text-white mt-2'>Rp{$productPrice}</span>
                                        </button>
                                    </div>
                                    <div class='px-5 py-3'>
                                        <header>
                                        <h2 class='text-blue-700 mt-2'><a href='{$productlink}'>{$productName}...</a></h3>
                                        <p class='text-gray-600 mt-5'>{$productDescription}...
                                        </p>
                                        </header>
                                        <div class='flex items-center justify-center mt-6'>
                                            <a href='{$productlink}'
                                                class='px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500'>Lihat Detail</a>
                                            
                                        </div>
                                    </div>
                                </div></article>";
                                }
                            } else {
                                echo "
                            <p class='text-gray-600 mt-5'>Whoops Tidak ada Hasil
                            </p>
                            ";
                            }
                            $conn->close();
                        }

                        if (!isset($_GET['search'])) {
                            $sql = "SELECT product_name, product_description, product_images, product_price, product_link, product_itemid, product_shopid, product_thumbnail,product_stock, product_rating, product_category, product_review_count, product_brand FROM products ORDER BY id DESC LIMIT $offset, $no_of_records_per_page ";
                            $result = $conn->query($sql);
    
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while ($product = $result->fetch_assoc()) {
                                    $productImage = $product['product_thumbnail'];
                                    $productName = $product['product_name'];
                                    $productDescription = $product['product_description'];
                                    $productName =  preg_replace('/\s+/', ' ', $productName);
                                    if (strlen($productName) <=50) {
                                        $productName = "Sedang ada Promo ".$productName;
                                    }
                                    $productName = substr($productName, 0, 46);

                                    $productDescription =  preg_replace('/\s+/', ' ', $productDescription);
                                  
                                    $productDescription = substr($productDescription, 0, 70);
                                    
                                    
                                    $productlink = $product_url.$product['product_link'];
                                    $productPrice = $product['product_price'];
                                    $productPrice = number_format($productPrice);
                                    echo "
                                <article>
                                <div class='w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden'>
                                <div class='flex items-end justify-end h-56 w-full bg-cover'
                                    style='background-image: url({$productImage})'>
                                    <button
                                        class='p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500'>
                                        <span class='text-white mt-2'>Rp{$productPrice}</span>
                                    </button>
                                </div>
                                <div class='px-5 py-3'>
                                    <header>
                                    <h2 class='text-blue-700 mt-2'><a href='{$productlink}'>{$productName}...</a></h3>
                                    <p class='text-gray-600 mt-5'>{$productDescription}...
                                    </p>
                                    </header>
                                    <div class='flex items-center justify-center mt-6'>
                                        <a href='{$productlink}'
                                            class='px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500'>Lihat Detail</a>
                                        
                                    </div>
                                </div>
                            </div></article>";
                                }
                            } else {
                                echo "0 results";
                            }
                            $conn->close();
                        }


                        
                    ?>




                    </div>
                    <div class="flex justify-center">
                        <div class="flex rounded-md mt-8">


                            <a href="<?php if ($page <= 1) {
                        echo '#';
                    } else {
                        if (isset($_GET['search'])) {
                            echo "page=".($page - 1)."&search={$searchQuery}";
                        } else {
                            echo "page=".($page - 1);
                        }
                    } ?>" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 border-r-0 ml-0 rounded-l hover:bg-blue-500 hover:text-white"><span>Previous</a></a>
                            <a href="<?php if ($page >= $total_pages) {
                        echo '#';
                    } else {
                        if (isset($_GET['search'])) {
                            echo "page=".($page + 1)."&search={$searchQuery}";
                        } else {
                            echo "page=".($page + 1);
                        }
                    } ?>" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 rounded-r hover:bg-blue-500 hover:text-white"><span><?php
                            if ($page >= $total_pages) {
                                echo "Last";
                            } else {
                                echo "Next";
                            }
                            ?></span></a>
                        </div>
                    </div>

                </div>
            </section>

        </main>

        <!-- Footer -->

        <?php include 'partials/footer.php'; ?>

</body>


</html>