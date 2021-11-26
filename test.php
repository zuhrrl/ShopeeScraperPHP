<?php
$url ="https://kaosqu.com/product/iphone-11-second-original-64-gb-128-gb-256-gb-resmi-no-rekondisi-";
if(str_contains(substr($url, -1), "-")) {
    $url = substr($url, 0, -1);
    $url = rtrim($url, "-");
    echo $url;
}
else {
    echo "not found";
}


?>