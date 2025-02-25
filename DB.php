<?php

class DB {
  public PDO $pdo;

  public function __construct(
    public string $host     = 'localhost',
    public string $database,
    public string $username = 'root',
    public string $password = 'root' 
  ){
    try {
      $this->pdo = new PDO(
        "mysql:host=$this->host;dbname=$this->database",
        $this->username,
        $this->password);

      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      file_put_contents('logs.txt', $e->getMessage(), FILE_APPEND);
    }
  }
}

