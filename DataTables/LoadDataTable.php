<?php
use \Database\Database;

require_once "startup.php";

include_once "Forms/filterform.php";
include_once "DataTables/transformer.php";

// Загружает таблицу с прайсом в соответствии с фильтром.
function LoadDataTable():array
{
    global $transformerSelect, $transformerSelectMinWholesaleMaxRetailPrice;
    
    $filterForm = $_SESSION["filter-form"];

    $typePrice = $filterForm['typePrice'];
    $minPrice = $filterForm['minPrice'];
    $maxPrice = $filterForm['maxPrice'];
    $moreOrless = $filterForm['moreOrless'];
    $amount = $filterForm['amount'];

    $quantityCondition = "";

    $mysql = $_SESSION["config"]["mysql"];

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

    $transformerSelectQuery = $transformerSelect . $where . $priceCondition . $quantityCondition . ";";

    try
    {
        $database = new Database($mysql["host"], $mysql["dbname"], $mysql["username"], $mysql["password"]);

        $minMax = $database->Select($transformerSelectMinWholesaleMaxRetailPrice);
        
        $minWholesalePrice = floor($minMax[0][0]);
        $maxRetailPrice = ceil($minMax[0][1]);

        $filterForm['maxRetailPrice'] = $maxRetailPrice;
        $filterForm['minWholesalePrice'] = $minWholesalePrice;
        
        if($minPrice < $minWholesalePrice)
        {
            $filterForm['minPrice'] = $minPrice = $minWholesalePrice;
        }

        if ($maxPrice > $maxRetailPrice)
        {
            $filterForm['maxPrice'] = $maxPrice = $maxRetailPrice;
        }

        $_SESSION['filter-form'] = $filterForm;

        $data = $database->Select($transformerSelectQuery);
    }
    catch (PDOException $e) 
    {
        $content = "<h1>Ошибка открытия базы данных: " . $e->getMessage() .
                    "<br>Попробуйте загрузить прайс</h1>";
        
        session_destroy();
        
        return include "templates/layout.php";
    }
    finally
    {
        $database = null;
    }

    return $data;
}


?>