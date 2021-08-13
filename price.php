<?php

use \Database\Database;
use \HTMLTable\HTMLTable;

include "classes/HTMLTable.php";
include "classes/Database.php";
include "DataTables/transformer.php";

$title = "Прайс трансформаторов";

$data = null;

try
{
  $database = new Database("Prices", "brainforce", "123456");

  $data = $database->Select($transformerSelect);
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
finally
{
  $database = null;
}

$table = new HTMLTable();

$table->Create($title, null, null);

//Нумерация массива строк начинается с 1 - заголовок.
$table->AddRowThead($columnsName);

for ($i=0; $i < count($data); $i++)
{
  $values = array();
  for($j = 1; $j != Count($data[$j]); $j++)
  {
    $values[] = $data[$i][$j];
  }

  $table->AddRowTbody($values);
}

$content = "<section class=\"price-table\">" .
            $table->GetMarkup() .
            "</section>";

include "templates/layout.php";
 ?>
