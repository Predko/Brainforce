<?php
session_start();

$GLOBALS['config'] = [
    'mysql' => [
      'host' => 'localhost',
      'dbname' => 'Prices',
      'username' => 'brainforce',
      'password' => '123456'
    ],
    'priceFileName' => 'xls/pricelist.xls', 
];




?>