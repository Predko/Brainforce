<?php
namespace HTMLTable;


class HTMLTable
{
  // Массив заголовков колонок.
  private $thead;

  // Массив заголовков колонок.
  private $tfoot;

  // Массив строк.
  private $tbody;

  private string $markup;

  private string $beginTableMarkup = "";
  private string $endTableMarkup = "</table>";

  // Создаёт заготовку таблицы с указанными заголовком, классами и атрибутами.
  public function Create($title, $classes, $attributes)
  {
    $cl = "";
    if ($classes !== null and count($classes) != 0)
    {
      $cl = 'class="' . implode(" ", $classes) . '"';
    }

    $this->beginTableMarkup = "<table " .
                              'title="' . $title . '" ' .
                               $cl;

    if ($attributes != null and count($attributes) != 0)
    {
      foreach ($attributes as $key => $value)
      {
        $this->beginTableMarkup .= '{$key}="{value}"';
      }
    }

    $this->beginTableMarkup .= '>';

    $this->CreateThead();

    $this->CreateTfoot();

    $this->CreateTbody();
  }

  // Создаёт заготовку секции thead.
  public function CreateThead()
  {
    $this->thead = "<thead>";
  }

  // Создаёт заготовку секции tfoot.
  public function CreateTfoot()
  {
    $this->tfoot = "<tfoot>";
  }

  // Создаёт заготовку секции tbody.
  public function CreateTbody()
  {
    $this->tbody = "<tbody>";
  }

  // Добавляет строку в секцию thead.
  public function AddRowThead($columns)
  {
    $this->thead .= "<tr><th>" .
                    implode("</th><th>", $columns) .
                    "</th></tr>";
  }

  // Добавляет строку в секцию tfoot.
  public function AddRowTfoot($columns)
  {
    $this->tfoot .= "<tr><td>" .
                    implode("</td><td>", $columns) .
                    "</td></tr>";
  }

  // Добавляет строку в секцию tbody.
  // Принимает массив вида:
  // array = [
  //   "class" => "",
  //   "note" => "",
  //   "values" => [строка-массив из базы данных]
  // ]
  public function AddRowTbody($columns)
  {
    $values = $columns["values"];

    $this->tbody .= "<tr id='$values[0]' {$columns["class"]}><td>" .
                    implode("</td><td>", array_slice($values, 1)) . 
                    "</td>" .
                    "<td>{$columns["note"]}</td>" .
                    "</tr>";
  }
  
  // Возвращает готовую разметку таблицы.
  public function GetMarkup(): string
  {
    return $this->beginTableMarkup .
           $this->GetThead() .
           $this->GetTfoot() .
           $this->GetTbody() .
           $this->endTableMarkup;
  }

  // Возвращает готовую разметку секции thead.
  public function GetThead()
  {
    return $this->thead . "</thead>";
  }

  // Возвращает готовую разметку секции tfoot.
  public function GetTfoot()
  {
    return $this->tfoot . "</tfoot>";
  }

  // Возвращает готовую разметку секции tbody.
  public function GetTbody()
  {
    return $this->tbody . "</tbody>";
  }

}
