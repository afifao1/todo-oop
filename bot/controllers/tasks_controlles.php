<?php
require __DIR__."/../db.php";


$fetchAllTasks = function() use ($db) {
    $tasks = $db->query("SELECT * FROM todos")->fetchAll(PDO::FETCH_ASSOC);
    if (count($tasks) > 0) {
        $taskList = "📋 *Tasklar ro'yxati:*\n";
        foreach ($tasks as $task) {
            $status = $task['status'] ? '✅' : '🟢';
            $taskList .= "{$status} {$task['task']} (ID: {$task['id']})\n";
        }
    } else {
        return "";
    }
    return $taskList;
};

$addTask = function($taskName) use($db) {
    if(empty($taskName)) {
        return "Task nomini kiriting!";
    }
    $stmt = $db->prepare("INSERT INTO todos (task) VALUES (:task)");
    $stmt->execute(['task' => $taskName]);
    return "✅ *Yangi task qo'shildi:* $taskName";
};
?>