<?php
session_start();

if (isSet($_SESSION['config']) == false)
{
  $_SESSION['config'] = [
    'mysql' => [
      'host' => 'localhost',
      'dbname' => 'Prices',
      'username' => 'brainforce',
      'password' => '123456'
    ],
    'priceFileName' => 'xls/pricelist.xls', 
  ];
}
?>