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


// Получает массив значений строки таблицы "Transformer"
// Возвращает true если в полях Retail и Wholesale находятся числа.
function transformerTableValidator($values):bool
{
    // Retail, Wholesale
    return is_numeric($values[1]) and is_numeric($values[2]);
}

// Получает массив значений из базы данных.
// Возвращает массив, содержащий выводимые значения, а также CSS класс для выводимой строки.
// 
function prepareToPrint($values, $maxRetail, $minWholesale): array
{
    $result = array();

    $result["class"] = "";

    if ($values[TRANSFORMER_RETAIL] == $maxRetail)
    {
        $result["class"] = "class='row-max-value'";
    }

    if ($values[TRANSFORMER_WHOLESALE] == $minWholesale)
    {
        $result["class"] = "class='row-min-value'";
    }

    $sumQuontity = $values[TRANSFORMER_INSTOCK1] + $values[TRANSFORMER_INSTOCK2];

    global $note;

    $result["note"] = "";

    if ($sumQuontity < 20)
    {
        $result["note"] = $note;
    }

    $result["values"] = $values;

    return $result;
}
  