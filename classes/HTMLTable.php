<?php
namespace HTMLTable;


class HTMLTable
{
  // Массив заголовков колонок.
  private $thead;

  // Массив строк.
  private $tbody;

  private string $markup;

  private string $beginTableMarkup = "";
  private string $endTableMarkup = "</table>";

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

    $this->CreateTbody();
  }

  public function CreateThead()
  {
    $this->thead = "<thead>";
  }

  public function CreateTbody()
  {
    $this->tbody = "<tbody>";
  }

  public function AddRowThead($columns)
  {
    $this->thead .= "<tr><th>" .
                    implode("</th><th>", $columns) .
                    "Примечание" .
                    "</th></tr>";
  }

  public function AddRowTbody($columns)
  {
    $this->tbody .= "<tr><td>" .
                    implode("</td><td>", $columns) .
                    "</td><td>" .
                    "</td></tr>";
  }
  public function GetMarkup(): string
  {
    return $this->beginTableMarkup .
           $this->GetThead() .
           $this->GetTbody() .
           $this->endTableMarkup;
  }

  public function GetThead()
  {
    return $this->thead . "</thead>";
  }

  public function GetTbody()
  {
    return $this->tbody . "</tbody>";
  }

}
