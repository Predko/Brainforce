<?php

use \Database\Database;
use \HTMLTable\HTMLTable;

require_once("startup.php");

include "classes/HTMLTable.php";
include "classes/Database.php";
include "DataTables/transformer.php";

$title = "Прайс трансформаторов";

$data = null;

$mysql = $GLOBALS["config"]["mysql"];

try
{
  $database = new Database($mysql["host"], $mysql["dbname"], $mysql["username"], $mysql["password"]);

  $data = $database->Select($transformerSelect);
}
catch (PDOException $e) {
  $content = "<h1>Ошибка открытия базы данных: " . $e->getMessage() .
             "<br>Попробуйте загрузить прайс</h1>";

  return include "templates/layout.php";
}
finally
{
  $database = null;
}

$inStock1 = 0;
$inStock2 = 0;
$sumRetail = 0;
$sumWholesale = 0;
$maxRetailPrice = 0;
$minWholesalePrice = $data[0][TRANSFORMER_WHOLESALE];

function MaxMinSumValues($values, $key)
{
  global $inStock1, $inStock2, $sumRetail, $sumWholesale, $maxRetailPrice, $minWholesalePrice;
  

  if ($values[TRANSFORMER_RETAIL] > $maxRetailPrice)
  {
    $maxRetailPrice = $values[TRANSFORMER_RETAIL];
  }
  
  if ($values[TRANSFORMER_WHOLESALE] < $minWholesalePrice)
  {
    $minWholesalePrice = $values[TRANSFORMER_WHOLESALE];
  }

  $inStock1 += $values[TRANSFORMER_INSTOCK1];
  $inStock2 += $values[TRANSFORMER_INSTOCK2];

  $inStock = $inStock1 + $inStock2;

  $sumRetail += $values[TRANSFORMER_RETAIL];
  $sumWholesale += $values[TRANSFORMER_WHOLESALE];
}

array_walk($data, "MaxMinSumValues");

$table = new HTMLTable();

$table->Create($title, ["price"], null);

// Массив заголовков таблицы.
$table->AddRowThead($columnsName);

$mediumRetail = round($sumRetail / count($data), 2);
$mediumWholesale = round($sumWholesale / count($data), 2);

// Информация о записях.
$footerColumns = [
  count($data) . " записей",
  "Средняя цена: $mediumRetail",
  "Средняя цена: $mediumWholesale",
  "Всего: $inStock1",
  "Всего: $inStock2",
  "",
  "",
];

// Футер таблицы.
$table->AddRowTfoot($footerColumns);

for ($i=0; $i < count($data); $i++)
{
  $table->AddRowTbody(prepareToPrint($data[$i], $maxRetailPrice, $minWholesalePrice));
}

// Фильтр.
include_once "Forms/filterform.php";

$filterForm = GetFilterForm(round($minWholesalePrice, 0), round($maxRetailPrice, 0));

// Формирование блока контента.
$content =  $filterForm .
            "<div id='table-container'>" .
               $table->GetMarkup() .
            "</div>";

include "templates/layout.php";
 ?>
