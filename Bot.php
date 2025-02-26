<?php
declare(strict_types=1);

require 'vendor/autoload.php';
require_once 'DB.php';

use MyApp\DB;


use GuzzleHttp\Client;

class Bot {
    private string $api;
    private Client $http;
    private DB $db;
    public string $text;
    public int $chatId;
    public string $firstName;

    public function __construct(string $token, DB $db){
        $this->api = "https://api.telegram.org/bot$token/";
        $this->http = new Client(['base_uri' => $this->api]);
        $this->db = $db; 
    }

    public function handle(string $update){
        $update = json_decode($update);

        $this->text = $update->message->text;
        $this->chatId = $update->message->chat->id;
        $this->firstName = $update->message->chat->first_name;

        match($this->text){
            '/start' => $this->handleStartCommand(),
            '/list'  => $this->handleListCommand(),
            default  => $this->sendMessage("😕 Noma'lum buyruq!"),
        };
    }

    public function handleStartCommand(){
        $text = "Assalomu alaykum $this->firstName";
        $text .= "\n\nBotimizga xush kelibsiz!";
        $text .= "\n\nBotdan foydalanish uchun quyidagi buyruqlardan birini tanlang:";
        $text .= "\n\n/list - Bor tasklar ro'yxati";
        $text .= "\n/add - Task qo'shish";
        $text .= "\n/delete - Taskni o'chirish";
        $text .= "\n/done - Taskni bajarilgan qilib belgilash";
        $text .= "\n/undone - Taskni bajarilmagan qilib belgilash";

        $this->sendMessage($text);
    }

    public function handleListCommand(){
        $tasks = $this->db->getTasks($this->chatId); 

        if (empty($tasks)) {
            $text = "Sizda hali hech qanday task yo‘q! 😊";
        } else {
            $text = "📋 *Sizning tasklaringiz:* \n\n";
            foreach ($tasks as $index => $task) {
                $status = ($task['status'] === 'done') ? "✅" : "⏳";
                $text .= ($index + 1) . ". {$task['task_text']} $status\n";
            }
        }

        $this->sendMessage($text, 'Markdown');
    }

    private function sendMessage(string $text, string $parseMode = ''){
        $params = [
            'chat_id' => $this->chatId,
            'text'    => $text
        ];

        if ($parseMode) {
            $params['parse_mode'] = $parseMode;
        }

        $this->http->post('sendMessage', [
            'form_params' => $params
        ]);
    }
}
