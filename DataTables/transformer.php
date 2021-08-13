<?php


$nameTransormerTable = "Transformer";

$nameLength = 100;
$countryLength = 20;

$columnsName = array(
    "Наименование товара",
    "Стоимость, руб",
    "Стоимость опт, руб",
    "Наличие на складе 1, шт",
    "Наличие на складе 2, шт",
    "Страна производства"
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
  