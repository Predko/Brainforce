<?php
// Содержит переменные для создания, чтения и записи данных
// в таблицу "Transformer" базы данных.
// Также определена функция валидации данных.

$nameTransormerTable = "Transformer";

$nameLength = 100;
$countryLength = 20;

const TRANSFORMER_ID = 0;
const TRANSFORMER_NAME = 1;
const TRANSFORMER_RETAIL = 2;
const TRANSFORMER_WHOLESALE = 3;
const TRANSFORMER_INSTOCK1 = 4;
const TRANSFORMER_INSTOCK2 = 5;
const TRANSFORMER_COUNTRY = 6;

$note = "Осталось мало!! Срочно докупите!!!";
$classNote = "class='table-note-cell'";

$columnsName = array(
    "Наименование товара",
    "Стоимость, руб",
    "Стоимость опт, руб",
    "Наличие на складе 1, шт",
    "Наличие на складе 2, шт",
    "Страна производства",
    "Примечание"
);

$transformerCreateTable = 
"CREATE TABLE " . $nameTransormerTable .
"(" .
    "Id INTEGER AUTO_INCREMENT PRIMARY KEY UNIQUE KEY NOT NULL," .
    "Name VARCHAR($nameLength) UNIQUE NOT NULL," .
    "Retail DECIMAL(15,2) DEFAULT 0," .
    "Wholesale DECIMAL(15,2) DEFAULT 0," .
    "InStock1 INTEGER DEFAULT 0," .
    "InStock2 INTEGER DEFAULT 0," .
    "Country VARCHAR($countryLength)" .
")";

 $transformerInsert =
    "INSERT INTO " .
    "$nameTransormerTable (Name, Retail, Wholesale, InStock1, InStock2, Country) " .
    "VALUES(?, ?, ?, ?, ?, ?)  AS new(n, r, w, i1, i2, c) " .
    "ON DUPLICATE KEY UPDATE " .
    "Retail = r, " .
    "Wholesale = w, " .
    "InStock1 = i1, " .
    "InStock2 = i2, " .
    "Country = c;";

$transformerSelect =
    "SELECT * FROM $nameTransormerTable";

$transformerSelectMinWholesaleMaxRetailPrice =
    "SELECT MIN(Wholesale), MAX(Retail) FROM $nameTransormerTable";

// Получает массив значений строки таблицы "Transformer"
// Возвращает true если в полях Retail и Wholesale находятся числа.
function transformerTableValidator($values):bool
{
    // Retail, Wholesale
    return is_numeric($values[1]) and is_numeric($values[2]);
}

// Получает массив значений из базы данных.
// Возвращает массив, содержащий выводимые значения, а также CSS класс для выводимой строки.
// array = [
//    "rowid" => "",
//    "rowclass" => "",
//    далее - массив полей из строки базы данных, кроме поля Id.
//    "values" => [
//      "data" => "поле из строки базы данных", 
//      "class" => "CSS класс" ]
// ]
function prepareToPrint($values, $maxRetail, $minWholesale): array
{
    $row = array();

    $row["rowid"] = $values[0];

    $row["rowclass"] = "";

    if ($values[TRANSFORMER_RETAIL] == $maxRetail)
    {
        $row["rowclass"] = "class='row-max-value'";
    }

    if ($values[TRANSFORMER_WHOLESALE] == $minWholesale)
    {
        $row["rowclass"] = "class='row-min-value'";
    }

    $sumQuontity = $values[TRANSFORMER_INSTOCK1] + $values[TRANSFORMER_INSTOCK2];

    global $note, $classNote;

    $row["values"] = array();

    for ($i = 1; $i < count($values); $i++) 
    { 
        $row["values"][] = [ "data" => $values[$i], "class" => "" ];
    }

    if ($sumQuontity < 20)
    {
        $row["values"][] = [ "data" => $note, "class" => $classNote ];
    }
    else
    {
        $row["values"][] = [ "data" => "", "class" => "" ];
    }

    return $row;
}

class CurrentTableInfo 
{
    public $inStock1 = 0;
    public $inStock2 = 0;
    public $sumRetail = 0;
    public $sumWholesale = 0;
    public $maxRetailPrice = 0;
    public $minWholesalePrice = 0;
    
    function MaxMinSumValues($values, $key)
    {
        if ($values[TRANSFORMER_RETAIL] > $this->maxRetailPrice)
        {
            $this->maxRetailPrice = $values[TRANSFORMER_RETAIL];
        }
        
        if ($values[TRANSFORMER_WHOLESALE] < $this->minWholesalePrice)
        {
            $this->minWholesalePrice = $values[TRANSFORMER_WHOLESALE];
        }
        
        $this->inStock1 += $values[TRANSFORMER_INSTOCK1];
        $this->inStock2 += $values[TRANSFORMER_INSTOCK2];
        
        $this->inStock = $this->inStock1 + $this->inStock2;
        
        $this->sumRetail += $values[TRANSFORMER_RETAIL];
        $this->sumWholesale += $values[TRANSFORMER_WHOLESALE];
    }
}

  