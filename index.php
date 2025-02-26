<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';
require_once 'credentials.php';
require_once 'DB.php';
require_once 'Bot.php';

use MyApp\DB;


// Telegramdan kelayotgan update'ni olish
$update = file_get_contents('php://input');

// DEBUG: Update borligini tekshirish
file_put_contents("debug_update.txt", $update); // 📝 JSON ma'lumotni saqlaydi

if ($update) {
    $db = DB::getInstance('localhost', 'todo_app', 'root', 'root1223');
    $bot = new Bot($token, $db);
    $bot->handle($update);
} else {
    require 'Web.php'; // Web qismi ishlashi uchun
}
