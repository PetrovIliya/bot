<?php
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
  
  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = BASE_URL . $method . '?' . http_build_query($params);
    } else {
      $url = BASE_URL . $method;
    }
    return  json_decode(file_get_contents('$url'), JSON_OBJECT_AS_ARRAY);
  }
  $chat_id = $updates['message']['chat']['id'];
  $sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'test']);
  var_dump($_POST);
 /*    
 include('vendor/autoload.php'); 
  use Telegram\Bot\Api; 
  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0'); 
  $result = $telegram -> getWebhookUpdates(); 
  $text = $result["message"]["text"];
  $chat_id = $result["message"]["chat"]["id"];  
  $name = $result["message"]["from"]["username"];
  if($text) {
    if ($text == "/start" and $name) {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать, ' . $name . '!' ]);
    } elseif($text == "/start" and !$name) {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' =>'Добро пожаловать!' . $result['message']['from']['first_name'] ]);
    } elseif ($text == "/sayHello") {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Hello World" ]);
    } else {
     	$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Запрос не является командой" ]);
    }
  } 
   */ 

?>
