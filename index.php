<?php
  include('vendor/autoload.php'); 
  include('src/YT_func.php');
  include('src/info.php');
  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  use Telegram\Bot\Api; 

  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = BASE_URL . $method . '?' . http_build_query($params);
    } else {
      $url = BASE_URL . $method;
    }
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
  }

  if ($request == '/start') {
   $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                                   'resize_keyboard' => true,
                                                   'one_time_keyboard' => false 
                                                  ]); 
    
   sendRequest('sendMessage', ['chat_id' => $chat_id, 
                               'text' => 'Добро пожаловать ' . $user_first_name . ' ' . $user_last_name . '!',
                               'reply_markup' => $reply_markup 
                              ]);
    
  } elseif ($request == '/help') {
      foreach($comands as $comand) {
      sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $comand . ' ']);
    }
  } else {
    sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью /help']);
  }

  $dataById = $video->videosByIds('FBnAZnfNB6U');
  var_dump($dataById);

?>
