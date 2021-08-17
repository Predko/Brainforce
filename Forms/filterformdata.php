<?php

include_once "startup.php";
require_once "classes/FilterForm.php";


if (!isSet($_SESSION['filter-form']))
{
    $filterForm = 
    [
        'maxRetailPrice' => PHP_INT_MAX,
        'minWholesalePrice' => 0,
        'typePrice' => 'retail',
        'minPrice' => 0,
        'maxPrice' => PHP_INT_MAX,
        'moreOrless' => 'all',
        'amount' => 20
    ];

    $_SESSION['filter-form'] = $filterForm;
}

?>