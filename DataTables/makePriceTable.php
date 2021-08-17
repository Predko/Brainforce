<?php
use \HTMLTable\HTMLTable;

require_once "startup.php";

include_once "classes/HTMLTable.php";
include_once "Forms/filterform.php";
include_once "DataTables/transformer.php";
  
  
function MakePriceTable(array $data): string
{
    global $columnsName;
    
    $currentTableInfo = new CurrentTableInfo();
    $currentTableInfo->minWholesalePrice = $data[0][TRANSFORMER_WHOLESALE];

    array_walk($data, array($currentTableInfo, "MaxMinSumValues"));

    $table = new HTMLTable();

    $table->Create("price-table", "", ["price"], null);

    // Массив заголовков таблицы.
    $table->AddRowThead($columnsName);

    $mediumRetail = round($currentTableInfo->sumRetail / count($data), 2);
    $mediumWholesale = round($currentTableInfo->sumWholesale / count($data), 2);

    $filterForm = $_SESSION["filter-form"];

    $filterForm['minPrice'] = floor($currentTableInfo->minWholesalePrice);
    $filterForm['maxPrice'] = ceil($currentTableInfo->maxRetailPrice);

    $_SESSION['filter-form'] = $filterForm;

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

    return $table->GetMarkup();
}
?>