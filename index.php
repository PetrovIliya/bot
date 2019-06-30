<?php
  require_once('vendor/autoload.php'); 
  require_once('src/info.php');
  require_once('src/YT_func.php');
  require_once('src/TG_func.php');
  
  $requestWords = str_word_count($request, 1, EXCEPTIONS);
  
  switch ($requestWords[0]): 
    case '/start': 
      $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                                     'resize_keyboard' => true,
                                                     'one_time_keyboard' => false]); 
    
     sendRequest('sendMessage', ['chat_id' => $chat_id, 
                                 'text' => 'Добро пожаловать ' . $user_first_name . ' ' . $user_last_name . '!',
                                 'reply_markup' => $reply_markup]); 
      break;
    case  'команды':
      foreach($comands as $comand) {
        sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $comand . ' ']);
      }
      break;
    case 'видео':
      sendRequest('sendMessage', ['chat_id' => $chat_id,
                                  'text' => 'видео']);
    default: 
      sendRequest('sendMessage', ['chat_id' => $chat_id,
                                  'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью /help']);
  endswitch;
  
 $dataBySearch = $video->search('космос', 2); 
  // $dataBySearch = $video->getDataVideo($dataBySearch->getItems());
 $video_ids = $dataBySearch -> items[0] -> id['videoId'];
 $video_title = $dataBySearch -> item[0] -> snippet['title'];
var_dump($video_title);


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
