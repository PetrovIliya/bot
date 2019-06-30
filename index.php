<?php
  require_once('vendor/autoload.php'); 
  require_once('src/info.php');
  require_once('src/YT_func.php');
  require_once('src/TG_func.php');
  
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

 $dataBySearch = $video->search('космос', 2); 
  // $dataBySearch = $video->getDataVideo($dataBySearch->getItems());
 $video_ids = $dataBySearch -> items[0] -> id['videoId'];


/*
?>
  <pre>
<?php
  var_dump($dataBySearch);  
?>
</pre>
<?php
  
  $videoId = $dataBySearch['id'];
  var_dump($videoId); */


?>
