<?php

class DB {
  public PDO $pdo;

  public function __construct(
    public string $host,
    public string $database,
    public string $username,
    public string $password 
  ){
    try {
      $this->pdo = new PDO(
        "mysql:host=$this->host;dbname=$this->database",
        $this->username,
        $this->password);

      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
    }
  }
}

