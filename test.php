<?php
require 'DB.php';
require 'credentials.php';

$db = DB::getInstance('localhost', $database, $dbUser, $dbPassword);
$userId = 1; // Test uchun 1-user

// 🟢 Task qo‘shish
$db->addTask($userId, 'PHP kodini yaxshilash');

// 🟡 Barcha tasklarni chiqarish
$tasks = $db->getTasks($userId);
echo "<pre>";
print_r($tasks);
echo "</pre>";

// 🔵 Taskni bajarilgan deb belgilash
if (!empty($tasks)) {
    $firstTaskId = $tasks[0]['id'];
    $db->completeTask($firstTaskId);
}

// 🔴 Taskni o‘chirish
if (!empty($tasks)) {
    $db->deleteTask($firstTaskId);
}
