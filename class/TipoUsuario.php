<?php

class TipoUsuario
{
  private $currentQuery = "";
  private $replaceValues = [];

  public function __construct()
  {
    $this->setQueryAll();
  }

  public function setQueryAll()
  {
    $this->currentQuery = "SELECT * FROM cattipousuario";
  }

  public function getQueryResult()
  {
    if ($this->currentQuery != "") {
      $connection = new ConectorBBDD();
      $result = $connection->consultarBD(
        $this->currentQuery,
        $this->replaceValues
      );
      return $result;
    }

    return [];
  }
}
