// Скрипт обновления данных в базе данных .
// Если базы данных и таблицы $nameTransormerTable нет


<?php

use \Database\Database;
use \PriceData\PriceData;

require_once("startup.php");
include "classes/PriceData.php";
include "classes/Database.php";
include "DataTables/transformer.php";

try
{
  $mysql = $GLOBALS["config"]["mysql"];
  
  $database = new Database($mysql["host"], $mysql["dbname"], $mysql["username"], $mysql["password"]);

  if ($database->IsDatabaseExist() == false)
  {
    $database->Create();
  }
  
  if ($database->IsTableExist($nameTransormerTable) == false)
  {
    $database->CreateTable($transformerCreateTable);
  }

  // Извлекаем данные из файла с прайсом.
  $priceData = new PriceData($GLOBALS["priceFileName"]);

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
