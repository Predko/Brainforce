<?php
namespace Database;

use \PDO;

class Database
{
  private $host;
  private $nameDatabase;
  private $connectionString;
  private $connection;
  private $user;
  private $password;

  private $error;
  private $errorMessage;

  public function __construct($host, $name, $user, $password)
  {
    $this->host = $host;
    $this->nameDatabase = $name;

    $this->user = $user;
    $this->password = $password;

    $this->error = false;
  }

  public function __destruct()
  {
    if ($this->connection != null)
    {
      $this->Close();
    }
  }

  // Добавляет данные в таблицу $tableName.
  // Если значение в колонке 'Name' уже есть в таблице, обновляет остальные поля
  // для этой записи.
  // $values - двумерный массив записей прайса для добавления(обновления).
  // $start - стартовая запись из этого массива.
  // $count - количество обновляемых записей(если 0 - все записи).
  public function Insert(string $sqlInsertQuery, $values, $isValid, $start = 0, $count = 0):int
  {
    $rowCount = 0;

    try 
    {
      $this->Open();

      $query = $sqlInsertQuery;

      $stmt = $this->connection->prepare($query);

      $end = ($count == 0) ? Count($values) : $start + $count;

      for ($i = $start; $i < $end; $i++) 
      { 
        $columnValues = array_values($values[$i]);

        if ($isValid($columnValues) == false)
        {
          // Данные не являются данными для записи в базу.
          continue;
        }

        $rowCount += $stmt->execute($columnValues);

        $stmt->closeCursor();
      }
    } 
    catch (PDOException $e)
    {
      $this->error = true;
      $this->errorMessage = $e->getMessage();
    }
    finally
    {
      $this->Close();
    }

    return $rowCount;
  }

  public function Select(string $sqlSelectQuery, $offset = 0, $rowCount = 0)
  {
    $this->Open();

    $rowCountString = ($rowCount == 0) ? ";" : " LIMIT  $offset, $rowCount;";

    $query = $sqlSelectQuery . $rowCountString;

    $stmt = $this->connection->query($query);

    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    
    $this->Close();
    
    return $result;
  }

  public function Create()
  {
    try
    {
      $this->connection = new PDO("mysql:host=localhost", $this->user, $this->password);

      $query = "CREATE DATABASE " . $this->nameDatabase;

      $this->connection->exec($query);
    }
    catch (PDOException $e)
    {
      $this->error = true;
      $this->errorMessage = $e->getMessage();
    }
    finally
    {
      $this->Close();
    }

    return !$this->error;
  }

  public function CreateTable($sqlCreateTableQuery)
  {
    try
    {
      $this->Open();

      $query = $sqlCreateTableQuery;

      $this->connection->exec($query);
    }
    catch (PDOException $e)
    {
      $this->error = true;
      $this->errorMessage = $e->getMessage();
    }
    finally
    {
      $this->Close();
    }

    return !$this->error;
  }

  public function IsDatabaseExist():bool
  {
    $sqlQuery = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" .
                                                      $this->nameDatabase . "';";

    $this->connection = new PDO("mysql:host=localhost", $this->user, $this->password);

    $result = $this->connection->query($sqlQuery)->fetchAll();

    $this->Close();

    return $result != false;
  }
  
  public function IsTableExist(string $name):bool
  {
    $query = "SELECT TABLE_NAME FROM information_schema.tables " . 
           "WHERE TABLE_SCHEMA = '$this->nameDatabase' " .
           "AND TABLE_NAME  = '$name'";

    $this->Open();

    $result = $this->Query($query);

    $this->Close();

    return $result != false;
  }

  private function Query($query)
  {
    $this->Open();

    $result = $this->connection->query($query)->fetchAll();

    $this->Close();

    return $result != false;
  }

  // Открываем подключение.
  private function Open()
  {
    $this->connection = new PDO("mysql:host=$this->host;dbname=$this->nameDatabase", $this->user, $this->password);
  }

  // Закрываем подключение.
  private function Close()
  {
    $this->connection = null;
  }

  public function IsError()
  {
    return $this->error;
  }

  public function GetErrorMessage()
  {
    return $this->errorMessage;
  }

  public function ErrorClear()
  {
    $this->error = false;
  }

}

?>
