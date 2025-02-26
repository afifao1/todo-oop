<?php
require_once 'DB.php';

$db = MyApp\DB::getInstance('localhost', 'todo_app', 'root', 'root1223');

$text = isset($_POST['new_task']) ? $_POST['new_task'] : null;
if ($text != null) {
    $stmt = $db->pdo->prepare("INSERT INTO tasks (user_id, task_text) VALUES (:user_id, :task_text)");
    $stmt->execute(['user_id' => 1, 'task_text' => $text]); // user_id vaqtincha 1
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->pdo->prepare("UPDATE tasks SET status = IF(status = 'done', 'pending', 'done') WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: /");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: /");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>ToDo App</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h1>ToDo App</h1>
            <form action="index.php" method="post">
                <input type="text" name="new_task" placeholder="Task qo'shing">
                <button type="submit">Add task</button>
            </form>
            <ul class="list">
            <?php
                $stmt = $db->pdo->query("SELECT * FROM tasks");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($data) > 0) {
                    foreach($data as $item) {
                        $status = $item['status'] === 'done' ? '✅' : '🟢';
                        echo "
                        <li>
                        <a class='link' href='?id={$item['id']}'>$status {$item['task_text']}</a>
                        <a href='?delete={$item['id']}'>Delete</a>
                        </li>";
                    }
                } else {
                    echo "<h1>Please add a task.</h1>";
                }
            ?>
        </ul>
        </div>
    </div>
</body>
</html>
