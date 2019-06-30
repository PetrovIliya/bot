<?php
   include('vendor/autoload.php'); 
   use Telegram\Bot\Api; 
   $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
  $chat_id = $update['message']['chat']['id'];
  $request = $update['message']['text'];
  $user_first_name = $update['message']['from']['first_name'];
  $user_last_name = $update['message']['from']['last_name'];
  $keyboard = [["/музыка"],["/видео"]];
  $comands = [
               '/start - начало работы',
               '/help - список команд',
               '/видео название видео - поиск видео',
               '/музыка название песни - поиск музыки'
             ];

  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = BASE_URL . $method . '?' . http_build_query($params);
    } else {
      $url = BASE_URL . $method;
    }
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
  }
  
  if ($request == '/start') {
    $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
    $response = $telegram->sendMessage([
	'chat_id' => $chat_id, 
	'reply_markup' => $reply_markup
]); 
    sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Добро пожаловать ' . $user_first_name . ' ' . $user_last_name . '!']);
  } elseif ($request == '/help') {
    foreach($comands as $comand) {
      sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $comand . ' ']);
    }
  } else {
    sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью /help']);
  }
  

?>
