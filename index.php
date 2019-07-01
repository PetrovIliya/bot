<?php
  require_once('vendor/autoload.php'); 
  require_once('src/api.php');
  use Telegram\Bot\Api;
  
  const COMANDS = array('Данный бот находится на стадии разработки, некоторый функционал может быть не доступен',
                        'кавычки служат только для обозначения разделов команд, набирать их не стоит', 
                        'команды - список команд',
                        '"видео" "название видео" "количество" - поиск видео',
                        '"музыка" "название песни" "количество"- поиск музыки');
  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  $video = new YouTubeVideo();
  $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
  $chat_id = $update['message']['chat']['id'];
  $request = $update['message']['text'];
  $user_first_name = $update['message']['from']['first_name'];
  $user_last_name = $update['message']['from']['last_name'];
  $keyboard = [["команды"]];
  $requestWords = str_word_count($request, 1, EXCEPTIONS);
  $lastWord = end($requestWords);
   
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
      foreach(COMANDS as $comand) {
        sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $comand . ' ']);
      }
      break;

     case ('видео' || 'Видео' ) :
      
      $query = getQuery($requestWords);
      if($query && $lastWord) {
        if(is_numeric($lastWord) && $lastWord <= MAX_VIDEOS){
          $dataBySearch = $video->search($query, $lastWord); 
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
                                  'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью запроса "команды"']);
  endswitch;
  
?>
  <pre>
<?php
// var_dump($video_titles);  
?>
</pre>
<?php
 
?>
