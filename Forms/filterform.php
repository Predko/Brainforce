<?php
include_once "filterformdata.php";

function GetFilterForm($min, $max):string
{
    $filterForm = $_SESSION['filter-form'];

    switch ($filterForm['typePrice']) 
    {
        case 'retail':
            $typeSelected = [" selected", ""];
            break;
        
        case 'wholesale':
        
        default:
            $typeSelected = ["", " selected"];
            break;
    }

    switch ($filterForm['moreOrless']) 
    {
        
        case 'more':
            $moreOrLessSelected = ["", " selected", ""];
            break;
        
        case 'less':
            $moreOrLessSelected = ["", "", " selected"];
            break;
        
        case 'all':
        
        default:
            $moreOrLessSelected = [" selected", "", ""];
            break;
    }

    return  <<<END
<div id='filter-form-container'>
    <form id='filter-form' enctype="multipart/form-data" method="post">
        Показать товары, у которых 
        <select name='type' title='Выберите тип цены'>
        <option value='retail' $typeSelected[0]>Розничная цена</option>
        <option value='wholesale' $typeSelected[1]>Оптовая цена</option>
        </select>
        от 
    
        <input name='min' type='number' min='$min' max='$max' value='{$filterForm['minPrice']}'>
        до 
        <input name='max' type='number' min='$min' max='$max' value='{$filterForm['maxPrice']}'>
        рублей и на складе 
        <select name='more-or-less'>
        <option value='all'$moreOrLessSelected[0]>неважно</option>
        <option value='more'$moreOrLessSelected[1]>более</option>
        <option value='less'$moreOrLessSelected[2]>менее</option>
        </select>
        <input name='amount' type='number' min='0' max='999' value='{$filterForm['amount']}'>
        штук.
        <input type='submit' value='Показать товары'>
    </form>
</div>
END;        
}

?>

