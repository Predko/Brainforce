<?php
use \Database\Database;
use \HTMLTable\HTMLTable;

require_once "startup.php";

include "classes/HTMLTable.php";
include "classes/Database.php";
include "DataTables/transformer.php";

$title = "Прайс трансформаторов";

$mysql = $_SESSION["config"]["mysql"];

// Фильтр.
include_once "Forms/filterform.php";

$isChanged = 0;

if (isset($_POST["type"]) && $filterForm['typePrice'] != $_POST["type"])
{
  $filterForm['typePrice'] = $_POST["type"];
  $isChanged++;
}

if (isset($_POST["min"]) && $filterForm['minPrice'] != $_POST["min"])
{
  $filterForm['minPrice'] = $_POST["min"];
  $isChanged++;
}

if (isset($_POST["max"]) && $filterForm['maxPrice'] != $_POST["max"])
{
  $filterForm['maxPrice'] = $_POST["max"];
  $isChanged++;
}

if (isset($_POST["more-or-less"]) && $filterForm['moreOrless'] != $_POST["more-or-less"])
{
  $filterForm['moreOrless'] = $_POST["more-or-less"];
  $isChanged++;
}

if (isset($_POST["amount"]) && $filterForm['amount'] != $_POST["amount"])
{
  $filterForm['amount'] = $_POST["amount"];
  $isChanged++;
}

if ($isChanged == 0 && isset($_POST["JSrequest"]))
{
  // Был отправлен запрос, но фильтр не изменился.
  echo "NoChanged";
  return;
}

$_SESSION['filter-form'] = $filterForm;

$typePrice = $filterForm['typePrice'];
$minPrice = $filterForm['minPrice'];
$maxPrice = $filterForm['maxPrice'];
$moreOrless = $filterForm['moreOrless'];
$amount = $filterForm['amount'];

$quantityCondition = "";

switch ($moreOrless) 
{
  case 'more':
    $quantityCondition = " AND InStock1 + InStock2 > $amount";
    break;
      
  case 'less':
    $quantityCondition = " AND InStock1 + InStock2 < $amount";
    break;
        
  case 'all':

  default:
    $quantityCondition = "";
    break;
}

$priceCondition = " $typePrice >= $minPrice AND $typePrice <= $maxPrice";

$where = ($quantityCondition == "" && $priceCondition == "") ? "" : " WHERE ";

$transformerSelect .= $where . $priceCondition . $quantityCondition . ";";

try
{
  $database = new Database($mysql["host"], $mysql["dbname"], $mysql["username"], $mysql["password"]);

  $minMax = $database->Select($transformerSelectMinWholesaleMaxRetailPrice);
  
  $minWholesalePrice = floor($minMax[0][0]);
  $maxRetailPrice = round($minMax[0][1]);

  $data = $database->Select($transformerSelect);
}
catch (PDOException $e) 
{
  $content = "<h1>Ошибка открытия базы данных: " . $e->getMessage() .
             "<br>Попробуйте загрузить прайс</h1>";

  return include "templates/layout.php";
}
finally
{
  $database = null;
}

// Проверяем, есть ли в массиве данные.
if (count($data) != 0)
{
  // Данные есть.
  $currentTableInfo = new CurrentTableInfo();
  $currentTableInfo->minWholesalePrice = $data[0][TRANSFORMER_WHOLESALE];

  array_walk($data, array($currentTableInfo, "MaxMinSumValues"));

  $table = new HTMLTable();

  $table->Create("price-table", "", ["price"], null);

  // Массив заголовков таблицы.
  $table->AddRowThead($columnsName);

  $mediumRetail = round($currentTableInfo->sumRetail / count($data), 2);
  $mediumWholesale = round($currentTableInfo->sumWholesale / count($data), 2);

  // Информация о записях.
  $footerColumns = [
    count($data) . " записей",
    "Средняя цена: $mediumRetail",
    "Средняя цена: $mediumWholesale",
    "Всего: $currentTableInfo->inStock1",
    "Всего: $currentTableInfo->inStock2",
    "",
    "",
  ];

  // Футер таблицы.
  $table->AddRowTfoot($footerColumns);

  for ($i=0; $i < count($data); $i++)
  {
    $table->AddRowTbody(prepareToPrint($data[$i], $currentTableInfo->maxRetailPrice, $currentTableInfo->minWholesalePrice));
  }

  $tableMarkup = $table->GetMarkup();
}
else
{
  // массив пуст - сообщаем об этом.
  $tableMarkup = "<h1>В таблице нет данных для данного фильтра</h1>";

  $filterFormMarkup = "";
}

if ($isChanged != 0)
{
    echo $tableMarkup;
    return;
}

$filterFormMarkup = GetFilterForm(0, $maxRetailPrice, $minWholesalePrice);

// Формирование блока контента.
$content =  $filterFormMarkup .
            "<div id='table-container'>" .
              $tableMarkup .
            "</div>";

include "templates/layout.php";
?>
