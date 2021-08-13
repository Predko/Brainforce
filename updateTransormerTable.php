<?php

use \Database\Database;
use \PriceData\PriceData;

require_once("startup.php");
include "classes/PriceData.php";
include "classes/Database.php";
include "DataTables/transformer.php";

try
{
  $database = new Database("Prices", "brainforce", "123456");

  if ($database->IsDatabaseExist() == false)
  {
    $database->Create();
  }
  
  if ($database->IsTableExist($nameTransormerTable) == false)
  {
    $database->CreateTable($transformerCreateTable);
  }

  // Извлекаем данные из файла с прайсом.
  $priceData = new PriceData('xls/pricelist.xls');

  $data = $priceData->GetData();

  $database->Insert($transformerInsert, $data, 'transformerTableValidator', 2);

}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
finally
{
  $database = null;
}

?>
