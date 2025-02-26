<?php

namespace MyApp;

class DB {
    private static ?DB $instance = null;
    public \PDO $pdo;

    public function __construct(
        private string $host,
        private string $database,
        private string $username,
        private string $password
    ) {
        try {
            $this->pdo = new \PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );
        } catch (\PDOException $e) {
            die("DB xatosi: " . $e->getMessage());
        }
    }

    public static function getInstance(string $host, string $database, string $username, string $password): DB {
        if (self::$instance === null) {
            self::$instance = new DB($host, $database, $username, $password);
        }
        return self::$instance;
    }

    public function addTask(int $user_Id, string $taskText): bool {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, task_text) VALUES (:user_id, :task_text)");
        return $stmt->execute(['user_id' => $user_Id, 'task_text' => $taskText]);
    }

    public function getTasks(int $user_Id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(["user_id" => $user_Id]);
        return $stmt->fetchAll();
    }

    public function completeTask(int $taskId): bool {
        $stmt = $this->pdo->prepare("UPDATE tasks SET status = 'done' WHERE id = :task_id");
        return $stmt->execute(['task_id' => $taskId]);
    }

    public function deleteTask(int $taskId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :task_id");
        return $stmt->execute(["task_id" => $taskId]);
    }
}
