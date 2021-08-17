<?php
require_once "startup.php";

// Фильтр.
include_once "Forms/filterform.php";

const ACCEPTED = 1;
const HANDLED = 2;
const NOTACCEPTED = 3;

// Проверяет, был ли запрос на применение изменённого фильтра.
// Возвращает:
//  - true, если это запрос от формы фильтра,
//  - false, если это не запрос от формы фильтра.
//  - null, если запрос обработан. 
function AcceptRequest():int
{
    $filterForm = $_SESSION["filter-form"];

    $isChanged = 0;

    //При вводе неправильного значения в фильтр устанавливать минимальное или максимальное.
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
        // Запрос обработан.
        echo "NoChanged";
        return HANDLED;
    }

    if ($isChanged != 0)
    {
        $_SESSION['filter-form'] = $filterForm;

        return ACCEPTED;
    }

    return NOTACCEPTED;
}
?>
