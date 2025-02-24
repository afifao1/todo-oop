<?php
require "./vendor/autoload.php";
require "./controllers/tasks_controlles.php";
require "./credentials.php";

use GuzzleHttp\Client;

$api = "https://api.telegram.org/bot$token/";

$task = new Client(['base_uri' => $api]);


$request = file_get_contents('php://input');
$message = json_decode($request);

$text = $message->message->text;
$chatId = $message->message->chat->id;
$firstName = $message->message->chat->first_name;

if ($text == "/start") {
    $text = "Assalomu alaykum $firstName";
    $text .= "\n\nBotimizga xush kelibsiz!";
    $text .= "\n\nBotdan foydalanish uchun quyidagi buyruqlardan birini tanlang:";
    $text .= "\n\n/list - Bor tasklar ro'yxati";
    $text .= "\n/add - Task qo'shish";
    $text .= "\n/delete - Taskni o'chirish";
    $text .= "\n/done - Taskni bajarilgan qilib belgilash";
    $text .= "\n/undone - Taskni bajarilmagan qilib belgilash";
    $task->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text' => $text
        ]
    ]);
}
if ($text == "/list") {
    $taskList = $fetchAllTasks();

    if (empty($taskList)) {
        $taskList = "📭 *Hozircha hech qanday tasklar mavjud emas.*";
    }
    $task->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text' => "$taskList",
            'parse_mode' => 'Markdown'
        ]
    ]);
}

if(strpos($text, "/add") === 0) {
    $taskName = trim(str_replace("/add", "", $text));
        $reply = "⚠️ Iltimos, task nomini yozing!\nMasalan: `/add Uy vazifasini qilish`";
    if(!empty($taskName)) {
        $reply = $addTask($taskName);
    }
    
    $task->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text' => "$reply",
            'parse_mode' => 'Markdown'
        ]
    ]);
}
if ($text == "/delete") {
    $task->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text' => "Taskni o'chirish"
        ]
    ]);
}
if ($text == "/done") {
    $task->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text' => "Taskni bajarilgan qilib belgilash"
        ]
    ]);
}
if ($text == "/undone") {
    $task->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text' => "Taskni bajarilmagan qilib belgilash"
        ]
    ]);
}
?>