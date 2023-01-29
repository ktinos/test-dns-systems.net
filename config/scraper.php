<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Products Url
    |--------------------------------------------------------------------------
    |
    | This is the value for the url of the products page.
    |
    */

    'products_url' => env('DNS_PRODUCTS_URL', 'https://wltest.dns-systems.net/'),

    /*
    |--------------------------------------------------------------------------
    | Products Package Key
    |--------------------------------------------------------------------------
    |
    | This identifies the selector for the products package css container.
    |
    */

    'products_package_key' => env('DNS_PRODUCTS_PACKAGE_KEY', '.package'),

    /*
    |--------------------------------------------------------------------------
    | Products Title Key
    |--------------------------------------------------------------------------
    |
    | This identifies the selector for the products title css container.
    |
    */

    'products_title_key' => env('DNS_PRODUCTS_TITLE_KEY', '.header h3'),

    /*
    |--------------------------------------------------------------------------
    | Products Description Key
    |--------------------------------------------------------------------------
    |
    | This identifies the selector for the products description css container.
    |
    */

    'products_description_key' => env('DNS_PRODUCTS_DESCRIPTION_KEY', '.package-description'),

    /*
    |--------------------------------------------------------------------------
    | Products Price Key
    |--------------------------------------------------------------------------
    |
    | This identifies the selector for the products price css container.
    |
    */

    'products_price_key' => env('DNS_PRODUCTS_PRICE_KEY', '.package-price > span'),

    /*
    |--------------------------------------------------------------------------
    | Products Discount Key
    |--------------------------------------------------------------------------
    |
    | This identifies the selector for the products discount css container.
    |
    */

    'products_discount_key' => env('DNS_PRODUCTS_DISCOUNT_KEY', '.package-price > p'),

    /*
    |--------------------------------------------------------------------------
    | Products Sort By Key
    |--------------------------------------------------------------------------
    |
    | This identifies the key name used to sort the result array.
    |
    */

    'products_sort_by_key' => env('DNS_PRODUCTS_SORT_BY_KEY', 'price'),

    /*
    |--------------------------------------------------------------------------
    | Products Price Identifier
    |--------------------------------------------------------------------------
    |
    | This identifies the string character that determines if a word is a price.
    |
    */

    'products_price_identifier' => env('DNS_PRODUCTS_PRICE_IDENTIFIER', '£'),

];
