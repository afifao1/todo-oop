<?php
declare(strict_types=1);

require 'credentials.php';
require 'Bot.php';

$update = file_get_contents('php://input');

if($update){
  (new Bot($token))->handle($update);

  return;
}

require 'Web.php';
