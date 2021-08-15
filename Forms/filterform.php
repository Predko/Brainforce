<?php


function GetFilterForm($min, $max):string
{
    return  
        "<div id='filter-form'>" .
        "<form>" .
        "Показать товары, у которых " .
        "<select name='type-price'>" .
        "<option value='retail' selected>Розничная цена</option>" .
        "<option value='wholesale'>Оптовая цена</option>" .
        "</select>" .
        " от " .
        "<input type='number' min='$min' max='$max' value='$min'>" .
        " до " .
        "<input type='number' min='$min' max='$max' value='$max'>" .
        " рублей и на складе " .
        "<select name='type-price'>" .
        "<option value='more' selected>более</option>" .
        "<option value='less'>менее</option>" .
        "</select>" .
        " штук." .
        "<input type='submit' value='Показать товары'>" .
        "</select>" .

        "</form>" .
        "</div>";
}

?>

