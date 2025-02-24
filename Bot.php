<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class Bot {
  private string $api;
  private $http;

  public function __construct(string $token){
    $this->api  = "https://api.telegram.org/bot$token/"; // $token got from credentials.php // FIXME: Replace with .env
    $this->http = new Client(['base_uri' => $this->api]);
  }

  public function handle(string $update){
    $update = json_decode($update);

    $text      = $update->message->text;
    $chatId    = $update->message->chat->id;
    $firstName = $update->message->chat->first_name;

    match($text) {
      '/start' => $this->handleStartCommand($chatId, $text, $firstName),
    };

  }

  public function handleStartCommand(int $chatId, string $text, string $firstName){
      $text = "Assalomu alaykum $firstName";
      $text .= "\n\nBotimizga xush kelibsiz!";
      $text .= "\n\nBotdan foydalanish uchun quyidagi buyruqlardan birini tanlang:";
      $text .= "\n\n/list - Bor tasklar ro'yxati";
      $text .= "\n/add - Task qo'shish";
      $text .= "\n/delete - Taskni o'chirish";
      $text .= "\n/done - Taskni bajarilgan qilib belgilash";
      $text .= "\n/undone - Taskni bajarilmagan qilib belgilash";
      
      $this->http->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chatId,
            'text'    => $text
        ]
      ]);
  }
}
