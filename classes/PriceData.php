<?php
namespace PriceData;

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class PriceData
{
  // Имя файла с данными.
  private $inputFileName;

  // Ридер для файла.
  private $reader;

  public function __construct($fileName)
  {
    $this->inputFileName = $fileName;

    $this->reader = IOFactory::createReaderForFile($this->inputFileName);
    $this->reader->setReadDataOnly(true);

    $worksheetNames = $this->reader->listWorksheetNames($this->inputFileName);
    $this->reader->setLoadSheetsOnly($worksheetNames[0]);
  }

  public function GetData()
  {
    $spreadsheet = $this->reader->load($this->inputFileName);

    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $spreadsheet->disconnectWorkSheets();

    unset($spreadsheet);

    return $sheetData;
  }
}
