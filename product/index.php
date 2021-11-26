<?php

// required for database
session_start();
include "../config/config.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = "";
}

$base_url = $_SERVER["SERVER_NAME"];
$product_url = "/product/";

$sql = "SELECT product_thumbnail, product_description, product_name FROM products WHERE product_link = '{$id}' LIMIT 1";
$result = $conn->query($sql);
$title;
$description = null;
$thumbnail;


if ($result->num_rows > 0) {
    // output data of each row
    while ($product = $result->fetch_assoc()) {
        $title = $product["product_name"];
        $description = $product["product_description"];
        $description = explode("\n", wordwrap(removeSpace($description), 80));
        $description = json_encode($description[0]);
        $thumbnail = $product["product_thumbnail"];
        $thumbnail = "https://".$base_url.$thumbnail;
    }

    if (str_contains($description, '"')) {
        $description = str_replace('"', "", $description);
    }
    if (str_contains($description, "\\")) {
        $description = str_replace("\\", "", $description);
    }
}
?>

<!-- Header -->

<?php 
$website_name = "Kaosqu.com";
if(isset($description) && isset($thumbnail)) {
    $page_description = $description;
    $page_name = "Jual ".$title." | ".$website_name;
    $title = $page_name;
    $og_image = $thumbnail;
}
else {
    $og_image = "";
}



include '../partials/header.php'; 
?>

<body class="bg-gray-200">

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

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
                    <div class="md:text-center text-2xl font-semibold text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-indigo-500">
                        Kaosqu.com
                    </div>
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
                            <?php include '../partials/navigation.php'; ?>

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
            <article>
            <div class="container mx-auto px-6">

            <?php
            // get images path
            function getProductImageUrl($image)
            {
                $imageurl = "https://cf.shopee.co.id/file/{$image}";
                return $imageurl;
            }

            // remove space
            function removeSpace($desc)
            {
                $desc = str_replace(["\r", "\n"], "", $desc);
                return $desc;
            }

            $sql = "SELECT product_name, product_description, product_images, product_price, product_link, product_itemid, product_shopid, product_thumbnail, product_stock, product_rating, product_category, product_review_count, product_brand FROM products WHERE product_link = '{$id}' LIMIT 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while ($product = $result->fetch_assoc()) {
                    $productImage = $product["product_thumbnail"];
                    $productName = $product["product_name"];
                    $productDescription = $product["product_description"];
                    $productlink = $product_url . $product["product_link"];
                    $productPrice = $product["product_price"];
                    $productPrice = number_format($productPrice);
                    $productStock = $product["product_stock"];
                    $productRating = $product["product_rating"];
                    $productRating = number_format($productRating, 1);
                    $productCategory = $product["product_category"];
                    $productReviewCount = $product["product_review_count"];
                    $productBrand = $product["product_brand"];
                    $priceSchema = $product["product_price"] . ".00";

                    echo "
                            <div itemscope itemtype='https://schema.org/Product'>
                            <div class='md:flex md:items-center'>
                            <div class='w-full h-64 md:w-1/2 lg:h-96'>
                                <img itemprop='image' class='h-full w-full rounded-md object-cover max-w-lg mx-auto'
                                    src='{$productImage}'
                                    alt='{$productName}'>
                            </div>
                            <span itemprop='name' content='{$productName}'></span>

                            <div itemscope itemprop='brand' itemtype='https://schema.org/Brand'>
                                            <span itemprop='name' content='{$productBrand}'></span>
                                            </div>

                            
                            <div class='w-full max-w-lg mx-auto mt-5 md:ml-8 md:mt-0 md:w-1/2'>
                            <div itemprop='offers' itemscope itemtype='https://schema.org/Offer'>
    <span itemprop='priceCurrency' content='IDR'></span>
    <span itemprop='price' content='{$priceSchema}'></span>
    <span itemprop='url' content='{$productlink}'></span>
    <link itemprop='availability' href='https://schema.org/InStock' />
                                
                                <header>
  
                                <a href='{$productlink}'><h3 class='text-blue-700 uppercase text-lg'>{$productName}</h3></a>
                                <span class='text-gray-500 mt-3'>Rp.{$productPrice}</span>
                                </div>
                                <hr class='my-3'>
                                <p itemprop='description' class='text-gray-700  text-sm'>

                                {$productDescription}

                                </p></header>";
                    // if review is 0
                    if ($productReviewCount < 1) {
                        echo "<div class='mb-1 tracking-wide px-4 py-4'>
                                    <div itemprop='aggregateRating' 
                                    itemscope itemtype='https://schema.org/AggregateRating'>
                                    
                                    <h2 class='text-gray-800 font-semibold mt-1'>Rating <span itemprop='ratingValue' content='5'>{$productRating}</span>/5 Dari {$productReviewCount} Ulasan</h2>
    
                                    </div>
                                        <div itemprop='review' itemscope itemtype='https://schema.org/Review'>
                                        <span itemprop='author'content='Kaosqu'></span>
                                            <div class='border-b -mx-8 px-8 pb-3'>
                                            <div class='flex items-center mt-1'>
                                                <div class=' w-1/5 text-indigo-500 tracking-tighter'>
                                                    <span>5</span> star
                                                </div>
                                                <div class='w-3/5'>
                                                    <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-1/12 bg-indigo-600 rounded-lg h-2'></div>
                                                    </div>
                                                </div>
                                                <div class='w-1/5 text-gray-700 pl-3'>
                                                    <span  class='text-sm'>0%</span>
                                                </div>
                                            </div><!-- first -->
                                            <div class='flex items-center mt-1'>
                                                <div class='w-1/5 text-indigo-500 tracking-tighter'>
                                                    <span>4 star</span>
                                                </div>
                                                <div class='w-3/5'>
                                                    <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class='w-1/12 bg-indigo-600 rounded-lg h-2'></div>
                                                    </div>
                                                </div>
                                                <div class='w-1/5 text-gray-700 pl-3'>
                                                    <span class='text-sm'>0%</span>
                                                </div>
                                            </div><!-- second -->
                                            <div class='flex items-center mt-1'>
                                                <div class='w-1/5 text-indigo-500 tracking-tighter'>
                                                    <span>3 star</span>
                                                </div>
                                                <div class='w-3/5'>
                                                    <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-1/12 bg-indigo-600 rounded-lg h-2'></div>
                                                    </div>
                                                </div>
                                                <div class='w-1/5 text-gray-700 pl-3'>
                                                    <span class='text-sm'>0%</span>
                                                </div>
                                            </div><!-- thierd -->
                                            <div class='flex items-center mt-1'>
                                                <div class=' w-1/5 text-indigo-500 tracking-tighter'>
                                                    <span>2 star</span>
                                                </div>
                                                <div class='w-3/5'>
                                                    <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-1/12 bg-indigo-600 rounded-lg h-2'></div>
                                                    </div>
                                                </div>
                                                <div class='w-1/5 text-gray-700 pl-3'>
                                                    <span class='text-sm'>0%</span>
                                                </div>
                                            </div><!-- 4th -->
                                            <div class='flex items-center mt-1'>
                                                <div class='w-1/5 text-indigo-500 tracking-tighter'>
                                                    <span>1</span> star
                                                </div>
                                                <div class='w-3/5'>
                                                    <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-1/12 bg-indigo-600 rounded-lg h-2'></div>
                                                    </div>
                                                </div>
                                                <div class='w-1/5 text-gray-700 pl-3'>
                                                    <span class='text-sm'>0%</span>
                                                </div>
                                            </div><!-- 5th -->
                                            </div>
                                        </div>
                                        
                                     
                                  <div class='flex items-center justify-center mt-2'>
                                  <button
                                      class='mb-5 px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500'>Ambil
                                      Promo</button>
                                 
                                            </div>
                                        </div>
                                    </div>
                                    </div></article>";
                    } else {
                        echo "<div class='mb-1 tracking-wide px-4 py-4'>
                                        <div itemprop='aggregateRating' 
                                        itemscope itemtype='https://schema.org/AggregateRating'>
                                        
                                        <h2 class='text-gray-800 font-semibold mt-1'>Rating {$productRating}/5 Dari {$productReviewCount} Ulasan</h2>
                         
                                        <span itemprop='ratingValue' content='{$productRating}'></span>
                                        <span itemprop='reviewCount' content='{$productReviewCount}'></span>
                                        <span itemprop='bestRating' content='5'></span>
                                        <span itemprop='worstRating' content='1'></span>

                                        </div>
                                            <div itemprop='review' itemscope itemtype='https://schema.org/Review'>
                                            <div itemscope itemprop='author' itemtype='https://schema.org/Person'>
                                            <span itemprop='name' content='Zuhrul Anam'></span>
                                            </div>

                                            <div class='border-b -mx-8 px-8 pb-3'>
                                               <div class='flex items-center mt-1'>
                                                  <div class=' w-1/5 text-indigo-500 tracking-tighter'>
                                                     <span>5</span> star
                                                  </div>
                                                  <div class='w-3/5'>
                                                     <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-7/12 bg-indigo-600 rounded-lg h-2'></div>
                                                     </div>
                                                  </div>
                                                  <div class='w-1/5 text-gray-700 pl-3'>
                                                     <span  class='text-sm'>51%</span>
                                                  </div>
                                               </div><!-- first -->
                                               <div class='flex items-center mt-1'>
                                                  <div class='w-1/5 text-indigo-500 tracking-tighter'>
                                                     <span>4 star</span>
                                                  </div>
                                                  <div class='w-3/5'>
                                                     <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class='w-1/5 bg-indigo-600 rounded-lg h-2'></div>
                                                     </div>
                                                  </div>
                                                  <div class='w-1/5 text-gray-700 pl-3'>
                                                     <span class='text-sm'>17%</span>
                                                  </div>
                                               </div><!-- second -->
                                               <div class='flex items-center mt-1'>
                                                  <div class='w-1/5 text-indigo-500 tracking-tighter'>
                                                     <span>3 star</span>
                                                  </div>
                                                  <div class='w-3/5'>
                                                     <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-3/12 bg-indigo-600 rounded-lg h-2'></div>
                                                     </div>
                                                  </div>
                                                  <div class='w-1/5 text-gray-700 pl-3'>
                                                     <span class='text-sm'>19%</span>
                                                  </div>
                                               </div><!-- thierd -->
                                               <div class='flex items-center mt-1'>
                                                  <div class=' w-1/5 text-indigo-500 tracking-tighter'>
                                                     <span>2 star</span>
                                                  </div>
                                                  <div class='w-3/5'>
                                                     <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-1/5 bg-indigo-600 rounded-lg h-2'></div>
                                                     </div>
                                                  </div>
                                                  <div class='w-1/5 text-gray-700 pl-3'>
                                                     <span class='text-sm'>8%</span>
                                                  </div>
                                               </div><!-- 4th -->
                                               <div class='flex items-center mt-1'>
                                                  <div class='w-1/5 text-indigo-500 tracking-tighter'>
                                                     <span>1</span> star
                                                  </div>
                                                  <div class='w-3/5'>
                                                     <div class='bg-gray-300 w-full rounded-lg h-2'>
                                                        <div class=' w-2/12 bg-indigo-600 rounded-lg h-2'></div>
                                                     </div>
                                                  </div>
                                                  <div class='w-1/5 text-gray-700 pl-3'>
                                                     <span class='text-sm'>0%</span>
                                                  </div>
                                               </div><!-- 5th -->
                                            </div>
                                         </div>
                                         
                                      </div>
                                      <div class='flex items-center justify-center mt-2'>
                                      <button
                                          class='mb-5 px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500'>Ambil
                                          Promo</button>
                                     
                                                </div>
                                            </div>
                                        </div>
                                        </div></article>";
                    }
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>


               
            </div>
        </section>
        </main>

        <!-- Footer -->
        <?php include '../partials/footer.php'; ?>

</body>

</html>