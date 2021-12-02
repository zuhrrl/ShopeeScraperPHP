<?php
// required for database
session_start();
include "../config/config.php";
$product_url = "/product/";

?>

<!-- Header -->

<?php
 $page_subtitle = "Admin Panel"; // Subtitle contoh: Example | Subtitle
 $page_description ="A Powerfull Shopee Auto Content Generator"; // Description untuk meta
 $page_name = $website_name." | ".$page_subtitle;
 $title = $page_name;

$total_pages_sql = "SELECT COUNT(*) FROM products";
$result = mysqli_query($conn, $total_pages_sql);
$total_products = mysqli_fetch_array($result)[0];



include '../partials/header.php'; ?>

<body class='bg-gray-200'>

  <!-- loading screen -->
<div id="loading_screen" class='w-full h-full fixed block top-0 left-0 bg-white opacity-75 z-50'>
    <span class='text-blue-500 opacity-75 top-1/2 my-0 mx-auto block relative w-0 h-0' style='
      top: 50%;'>
       <i class='fas fa-circle-notch fa-spin fa-5x'></i>
    </span>
 </div>

  <!-- component -->
  <div>
    <nav class='bg-white border-b border-gray-200 fixed z-30 w-full'>
      <div class='px-3 py-3 lg:px-5 lg:pl-3'>
        <div class='flex items-center justify-between'>
          <div class='flex items-center justify-start'>
            <button id='toggleSidebarMobile' aria-expanded='true' aria-controls='sidebar'
              class='lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded'>
              <svg id='toggleSidebarMobileHamburger' class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'
                xmlns='http://www.w3.org/2000/svg'>
                <path fill-rule='evenodd'
                  d='M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z'
                  clip-rule='evenodd'></path>
              </svg>
              <svg id='toggleSidebarMobileClose' class='w-6 h-6 hidden' fill='currentColor' viewBox='0 0 20 20'
                xmlns='http://www.w3.org/2000/svg'>
                <path fill-rule='evenodd'
                  d='M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z'
                  clip-rule='evenodd'></path>
              </svg>
            </button>
            <h1
              class='self-center white-space nowrap md:text-center text-2xl font-semibold text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-indigo-500'>
              Adminpanel </h1>
            </a>
            <div class='hidden lg:block lg:pl-32'>
              <label for='topbar-search' class='sr-only'>Search</label>
              <div class='mt-1 relative lg:w-64'>
                <div class='absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none'>
                  <svg class='w-5 h-5 text-gray-500' fill='currentColor' viewBox='0 0 20 20'
                    xmlns='http://www.w3.org/2000/svg'>
                    <path fill-rule='evenodd'
                      d='M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z'
                      clip-rule='evenodd'></path>
                  </svg>
                </div>
                <input type='text' name='email' id='topbar-search'
                  class='bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full pl-10 p-2.5'
                  placeholder='Search'>
              </div>
            </div>
          </div>
          <div class='flex items-center'>
            <button id='toggleSidebarMobileSearch' type='button'
              class='lg:hidden text-gray-500 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-lg'>
              <span class='sr-only'>Search</span>
              <svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                <path fill-rule='evenodd'
                  d='M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z'
                  clip-rule='evenodd'></path>
              </svg>
            </button>

            <a href='https://demo.themesberg.com/windster/pricing/'
              class='hidden sm:inline-flex ml-5 text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center items-center mr-3'>
              <svg class='svg-inline--fa fa-gem -ml-1 mr-2 h-4 w-4' aria-hidden='true' focusable='false'
                data-prefix='fas' data-icon='gem' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                <path fill='currentColor'
                  d='M378.7 32H133.3L256 182.7L378.7 32zM512 192l-107.4-141.3L289.6 192H512zM107.4 50.67L0 192h222.4L107.4 50.67zM244.3 474.9C247.3 478.2 251.6 480 256 480s8.653-1.828 11.67-5.062L510.6 224H1.365L244.3 474.9z'>
                </path>
              </svg>
              Upgrade to Pro
            </a>
          </div>
        </div>
      </div>
    </nav>
    <div class='flex overflow-hidden bg-white pt-16'>
      <aside id='sidebar'
        class='fixed hidden z-20 h-full top-0 left-0 pt-16 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75'
        aria-label='Sidebar'>
        <div class='relative flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white pt-0'>
          <div class='flex-1 flex flex-col pt-5 pb-4 overflow-y-auto'>
            <div class='flex-1 px-3 bg-white divide-y space-y-1'>
              <ul class='space-y-2 pb-2'>
                <li>
                  <form action='#' method='GET' class='lg:hidden'>
                    <label for='mobile-search' class='sr-only'>Search</label>
                    <div class='relative'>
                      <div class='absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none'>
                        <svg class='w-5 h-5 text-gray-500' fill='currentColor' viewBox='0 0 20 20'
                          xmlns='http://www.w3.org/2000/svg'>
                          <path
                            d='M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'>
                          </path>
                        </svg>
                      </div>
                      <input type='text' name='email' id='mobile-search'
                        class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:ring-cyan-600 block w-full pl-10 p-2.5'
                        placeholder='Search'>
                    </div>
                  </form>
                </li>
                <li>
                  <a href='/admin'
                    class='text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group'>
                    <svg class='w-6 h-6 text-gray-500 group-hover:text-gray-900 transition duration-75'
                      fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                      <path d='M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z'></path>
                      <path d='M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z'></path>
                    </svg>
                    <span class='ml-3'>Dashboard</span>
                  </a>
                </li>



                <li>
                  <a href='/admin/products'
                    class='text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group '>
                    <svg class='w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75'
                      fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                      <path fill-rule='evenodd'
                        d='M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z'
                        clip-rule='evenodd'></path>
                    </svg>
                    <span class='ml-3 flex-1 whitespace-nowrap'>Products</span>
                  </a>
                </li>
                
               
              </ul>
              <div class='space-y-2 pt-2'>

                <a href='/documentation' target='_blank'
                  class='text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 group transition duration-75 flex items-center p-2'>
                  <svg class='w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75'
                    fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                    <path d='M9 2a1 1 0 000 2h2a1 1 0 100-2H9z'></path>
                    <path fill-rule='evenodd'
                      d='M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z'
                      clip-rule='evenodd'></path>
                  </svg>
                  <span class='ml-3'>Documentation</span>
                </a>

                <a href='?logout=1' target='_blank'
                  class='text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 group transition duration-75 flex items-center p-2'>
                  <svg class='w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75'
                    fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                    <path fill-rule='evenodd'
                      d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z'
                      clip-rule='evenodd'></path>
                  </svg>
                  <span class='ml-3'>Logout</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </aside>
      <div class='bg-gray-900 opacity-50 hidden fixed inset-0 z-10' id='sidebarBackdrop'></div>
      <div id='main-content' class='h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64'>
        <main>
          <div class='pt-6 px-4'>

            <div class='w-full bg-white shadow rounded-lg'>
              <h3 class='p-5 text-2xl text-center'>Generate Produk</h3>
              <div class='ml-5 mr-5'>
                <label for='keyword' class='mb-2 block text-sm font-medium text-gray-700'>
                  Keywords: (Satu keyword tiap baris)</label>
                <textarea id='listkeywords' placeholder='Input keyword separated by breakline example: keyword [Enter] keyword [enter]'
                  rows='4'
                  class='placeholder-gray-500 w-full bg-white rounded-md border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 text-sm outline-none text-gray-900 py-1 px-3 leading-8 transition-colors duration-150 ease-in-out'> </textarea>

                <div class='col-span-6 sm:col-span-3'>
                  <label for='mode' class='block text-sm font-medium text-gray-700'>
                    Generate Mode</label>
                  <select id='mode'
                    class='mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'>
                    <option value='keyword'>By Keywords</option>
                    <option value='trending'>By Trending</option>
                  </select>
                </div>

                <div class='col-span-6 sm:col-span-3'>
                  <label for='item_aff_link' class='block text-sm font-medium text-gray-700'>
                    Affiliate Link (Untuk tiap Produk)</label>
                  <input id='aff_link'
                    class='mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'>

                  </input>
                </div>

                <label for='checked' class='mt-3 inline-flex items-center cursor-pointer'>
                  <span class='relative'>
                    <span class='block w-10 h-6 bg-gray-400 rounded-full shadow-inner'></span>
                    <span
                      class='absolute block w-4 h-4 mt-1 ml-1 rounded-full shadow inset-y-0 left-0 focus-within:shadow-outline transition-transform duration-300 ease-in-out bg-blue-600 transform translate-x-full'>
                      <input id='checked' type='checkbox' class='absolute opacity-0 w-0 h-0'>
                    </span>
                  </span>
                  <span class='ml-3 text-sm'>Auto Generate (Otomatis Tiap 60 Menit)</span>
                </label>

              </div>

              <p class='text-center ltexeading-relaxed m-2 text-gray-600'>
                Status: <span id='status_generate'></span>
              </p>

              <div class='mt-5 text-center'>
                <button id='generate'
                  class='w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 focus:outline-none focus:shadow-outline'
                  type='button'>
                  Generate
                </button>
              </div>
              <form class='px-8 pt-6 pb-8 mb-4 bg-white rounded'>






                <hr class='mb-6 border-t'>

              </form>
            </div>

            <div class='w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4'>
              <div class='bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 '>
                <div class='flex items-center'>
                  <div class='flex-shrink-0'>
                    <span class='text-2xl sm:text-3xl leading-none font-bold text-gray-900'><?php echo $total_products;?></span>
                    <h3 class='text-base font-normal text-gray-500'>Total Products</h3>
                  </div>
                  <div class='ml-5 w-0 flex items-center justify-end flex-1 text-green-500 text-base font-bold'>
                    -%
                    <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                      <path fill-rule='evenodd'
                        d='M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z'
                        clip-rule='evenodd'></path>
                    </svg>
                  </div>
                </div>
              </div>
              <div class='bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 '>
                <div class='flex items-center'>
                  <div class='flex-shrink-0'>
                    <span class='text-2xl sm:text-3xl leading-none font-bold text-gray-900'>-</span>
                    <h3 class='text-base font-normal text-gray-500'>Visitors: Under Construction</h3>
                  </div>
                  <div class='ml-5 w-0 flex items-center justify-end flex-1 text-green-500 text-base font-bold'>
                    0%
                    <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                      <path fill-rule='evenodd'
                        d='M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z'
                        clip-rule='evenodd'></path>
                    </svg>
                  </div>
                </div>
              </div>
              <div class='bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 '>
                <div class='flex items-center'>
                  <div class='flex-shrink-0'>
                    <span class='text-2xl sm:text-3xl leading-none font-bold text-gray-900'>-</span>
                    <h3 class='text-base font-normal text-gray-500'>Broken Links: Under Construction</h3>
                  </div>
                  <div class='ml-5 w-0 flex items-center justify-end flex-1 text-red-500 text-base font-bold'>
                    0%
                    <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                      <path fill-rule='evenodd'
                        d='M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z'
                        clip-rule='evenodd'></path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
            <div class='grid grid-cols-1 2xl:grid-cols-2 xl:gap-4 my-4'>
              <div class='bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full'>
                <div class='flex items-center justify-between mb-4'>
                  <h3 class='text-xl font-bold leading-none text-gray-900'>Latest Generated Products</h3>
                  <a href='#'
                    class='text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2'>
                    View all
                  </a>
                </div>
                <div class='flex flex-col'>
                  <div class='overflow-x-auto'>
                    <div class='align-middle inline-block min-w-full'>
                      <div class='shadow overflow-hidden'>
                        <table class='table-fixed min-w-full divide-y divide-gray-200'>
                          <thead class='bg-gray-100'>
                            <tr>
                              <th scope='col' class='p-4'>
                                <div class='flex items-center'>
                                  <input id='checkbox-all' aria-describedby='checkbox-1' type='checkbox'
                                    class='bg-gray-50 border-gray-300 focus:ring-3 focus:ring-cyan-200 h-4 w-4 rounded'>
                                  <label for='checkbox-all' class='sr-only'>checkbox</label>
                                </div>
                              </th>
                              <th scope='col' class='p-4 text-left text-xs font-medium text-gray-500 uppercase'>
                                Product Name
                              </th>
                              <th scope='col' class='p-4 text-left text-xs font-medium text-gray-500 uppercase'>
                                Category
                              </th>

                              <th scope='col' class='p-4 text-left text-xs font-medium text-gray-500 uppercase'>
                                Price
                              </th>
                              <th scope='col' class='p-4 text-left text-xs font-medium text-gray-500 uppercase'>
                                Affiliate Link
                              </th>

                              <th scope='col' class='p-4'>
                              </th>
                            </tr>
                          </thead>
                          <tbody class='bg-white divide-y divide-gray-200'>

                          <?php
                          

$sql = "SELECT id, product_name, product_description, product_images, product_price, product_link, product_itemid, product_shopid, product_thumbnail,product_stock, product_rating, product_category, product_review_count, product_brand, product_aff_link FROM products ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($product = $result->fetch_assoc()) {
        $productId = $product['id'];
        $productImage = $product['product_thumbnail'];
        $productName = $product['product_name'];
        $productDescription = $product['product_description'];
        $productName =  preg_replace('/\s+/', ' ', $productName);
       
        $productName = substr($productName, 0, 15);
        $productCategory = $product['product_category'];
        $productAffLink = $product['product_aff_link'];

        $productDescription =  preg_replace('/\s+/', ' ', $productDescription);
      
        $productDescription = substr($productDescription, 0, 85);
        $productlink = $product_url.$product['product_link'];
        $productPrice = $product['product_price'];
        $productPrice = number_format($productPrice);

        echo "<tr class='hover:bg-gray-100'>
        <td class='p-4 w-4'>
          <div class='flex items-center'>
            <input id='checkbox-194556' aria-describedby='checkbox-1' type='checkbox'
              class='bg-gray-50 border-gray-300 focus:ring-3 focus:ring-cyan-200 h-4 w-4 rounded'>
            <label for='checkbox-194556' class='sr-only'>checkbox</label>
          </div>
        </td>
        <td class='p-4 whitespace-nowrap text-sm font-normal text-gray-500'>
          <div class='text-base font-semibold text-gray-900'>{$productName}...
          </div>
          <div class='text-sm font-normal text-gray-500'>Rating 4.9/5 Dari 69 Ulasan</div>
        </td>
        <td class='p-4 whitespace-nowrap text-base font-medium text-gray-900'>
          {$productCategory}</td>
        <td class='p-4 whitespace-nowrap text-base font-medium text-gray-900'>
          Rp. {$productPrice}</td>
        <td class='p-4 whitespace-nowrap text-base font-medium text-gray-900'>
        <input name='input_aff' id='aff_link_{$productId}' value='{$productAffLink}' class='py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'>

        </td>

        <td class='p-4 whitespace-nowrap space-x-2'>
          <button type='button' id='{$productId}' name='edit_aff_link'
            class='text-white bg-blue-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center'>
            <svg class='mr-2 h-5 w-5' fill='currentColor' viewBox='0 0 20 20'
              xmlns='http://www.w3.org/2000/svg'>
              <path
                d='M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z'>
              </path>
              <path fill-rule='evenodd'
                d='M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z'
                clip-rule='evenodd'></path>
            </svg>
            Edit Link
          </button>
          <button type='button'
            class='text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center'>
            <svg class='mr-2 h-5 w-5' fill='currentColor' viewBox='0 0 20 20'
              xmlns='http://www.w3.org/2000/svg'>
              <path fill-rule='evenodd'
                d='M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z'
                clip-rule='evenodd'></path>
            </svg>
            Delete item
          </button>
        </td>
      </tr>";
    }
}

                          ?>







                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </main>

      </div>
    </div>
  </div>

  <?php include '../partials/footer.php'; ?>


</body>



</html>