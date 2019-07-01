<?php
  require_once('vendor/autoload.php'); 
  require_once('src/info.php');
  require_once('src/YT_func.php');
  require_once('src/TG_func.php');
  
  
  
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
      if($requestWords[1] && $lastWord) {
        if(is_numeric($lastWord) && $requestWords[2] <= MAX_VIDEOS){
          $dataBySearch = $video->search($requestWords[1], $lastWord); 
          sendVideos($dataBySearch, $lastWord, $chat_id);
        } elseif(!is_numeric($lastWord)) {
            sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' =>  '"количество" - должно быть целым числом']);
        } else {
          sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => '"количество" - не может превышать ' . MAX_VIDEOS]);
        }     
      } else {
        sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'не верно указаны параметры']);
      }
      break;
    default: 
      sendRequest('sendMessage', ['chat_id' => $chat_id,
                                  'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью /help']);
  endswitch;
  
?>
  <pre>
<?php
// var_dump($video_titles);  
?>
</pre>
<?php
 
?>
