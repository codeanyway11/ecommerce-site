<?php

define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/shopping/');
define('CART_COOKIE', 'SB345678fghj');
define('CART_COOKIE_EXPIRE', time()+ (86400*30));
define('TAXRATE', 0.08);
define('CURRENCY', 'usd');
define('CHECKOUTMODE', 'TEST');

if('CHECKOUTMODE' == 'TEST'){
    define('STRIPE_PRIVATE', 'fas');
    define('STRIPE_PUBLIC', 'sads');
}

if('CHECKOUTMODE' == 'TEST'){
    define('STRIPE_PRIVATE', 'sk_test_uIGZfV43XXol1FOSzi7xrLlx');
    define('STRIPE_PUBLIC', 'pk_test_UWQMSvpYqzk5DtEOJEZh8WkA');
}
