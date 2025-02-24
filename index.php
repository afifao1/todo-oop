<?php
$servername = "localhost";
$username = "root";
$password = "root5005";

try {
  $conn = new PDO("mysql:host=$servername;dbname=todo", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
}
$text = isset($_POST['new_task']) ? $_POST['new_task'] : null;
if($text != null) {
    $conn->query("INSERT INTO todos( task ) VALUES ('$text')");
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE todos SET status = IF(status = true , false , true) WHERE id = $id");
    $stmt->execute();
    header("Location: /");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = $id");
    $stmt->execute();
    header("Location: /");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style.css">
        <title>Document</title>
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
            <h1>ToDo App</h1>
            <form action="index.php" method="post">
                <input type="text" name="new_task" id="" placeholder="Task qo'shing">
                <button class="" type="submit">Add task</button>
            </form>
            <ul class="list">
            <?php
                $data = $conn->query("SELECT * FROM todos")->fetchAll(PDO::FETCH_ASSOC);
                if(count($data) > 0) {
                    foreach($data as $item) {
                        $status = $item['status'] ? '✅' :'🟢';
                        echo "
                        <li>
                        <a class='link' href='?id={$item['id']}'>$status {$item['task']}</a>
                        <a href='?delete={$item['id']}'>Delete</a>
                        </li>";
                    }
                } else {
                    echo "<h1>Please add a task.</h1>";
                }
            ?>
            </div>
        </ul>
    </div>
</body>
</html>