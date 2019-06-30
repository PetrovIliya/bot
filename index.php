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
      sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $requestWords[0] . $requestWords[1] . $requestWords[2]]);
      if($requestWords[1] && $requestWords[2]) {
        sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'video']);
      }
      /*
      sendRequest('sendMessage', ['chat_id' => $chat_id,'text' => 'видео']);
      if($requestWords[1] && $requestWords[2]) {
        if(is_numeric($requestWords[2]) && $requestWords[2] <= MAX_VIDEOS){
           //$dataBySearch = $video->search($requestWords[1], $requestWords[2]); 
          echo 'hello';
        } elseif(!is_numeric($requestWords[2]) {
            sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' =>  '"количество" - должно быть целым числом']);
        } else {
          sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => '\"количество\" - не может превышать'. MAX_VIDEOS]);
        }     
      } else {
        sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'не верно указаны параметры']);
      }
      */
      echo 'video';
      break;
    default: 
      sendRequest('sendMessage', ['chat_id' => $chat_id,
                                  'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью /help']);
  endswitch;
  
 //$dataBySearch = $video->search('космос', 2); 
  // $dataBySearch = $video->getDataVideo($dataBySearch->getItems());
//$video_title = $dataBySearch -> item[0];
 //$video_ids = $dataBySearch -> items[0] -> id['videoId'];
 


?>
  <pre>
<?php
// var_dump($video_title);  
?>
</pre>
<?php
 
?>
