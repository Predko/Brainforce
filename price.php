<?php
use \Database\Database;
use \HTMLTable\HTMLTable;

require_once "startup.php";

include "classes/HTMLTable.php";
include "classes/Database.php";
include "DataTables/transformer.php";

$title = "Прайс трансформаторов";

// Фильтр.
include_once "Forms/filterform.php";
include_once "RequestHandler\priceRequest.php";
include_once "DataTables\makePriceTable.php";
include_once "DataTables\LoadDataTable.php";

$requestAccepted = AcceptRequest();

if ($requestAccepted == HANDLED)
{
  return;
}

$data = LoadDataTable();

$filterForm = $_SESSION["filter-form"];

$maxRetailPrice = $filterForm['maxRetailPrice'];
$minWholesalePrice = $filterForm['minWholesalePrice'];

if (count($data) != 0)
{
  $tableMarkup = MakePriceTable($data);
}
else
{
  // массив пуст - сообщаем об этом.
  $tableMarkup = "<h1>В таблице нет данных для данного фильтра</h1>";

  $filterFormMarkup = "";
}

if ($requestAccepted == ACCEPTED)
{
    echo $tableMarkup;
    return;
}

$filterFormMarkup = GetFilterForm($minWholesalePrice, $maxRetailPrice);

// Формирование блока контента.
$content =  $filterFormMarkup .
            "<div id='table-container'>" .
              $tableMarkup .
            "</div>";

$scripts = "<script src='js\SubmitForm.js'></script>";

include "templates/layout.php";
?>
