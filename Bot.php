<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class Bot {
  private string $api;
  private        $http;
  public  string $text;
  public  int    $chatId;
  public  string $firstName;

  public function __construct(string $token){
    $this->api  = "https://api.telegram.org/bot$token/"; // $token got from credentials.php // FIXME: Replace with .env
    $this->http = new Client(['base_uri' => $this->api]);
  }

  public function handle(string $update){
    $update = json_decode($update);

    $this->text      = $update->message->text;
    $this->chatId    = $update->message->chat->id;
    $this->firstName = $update->message->chat->first_name;

    match($this->text){
      '/start' => $this->handleStartCommand(),
      '/list'  => $this->handleListCommand(),
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
      
      $this->http->post('sendMessage', [
        'form_params' => [
            'chat_id' => $this->chatId,
            'text'    => $text
        ]
      ]);
  }
}
