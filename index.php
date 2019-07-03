<?php
  require_once('vendor/autoload.php'); 
  require_once('src/api.php');
  require_once ('src/dataBase.php');
  const MAX_VIDEOS = 10;
  const COMANDS = array('Данный бот находится на стадии разработки, некоторый функционал может быть не доступен',
                        'кавычки служат только для обозначения разделов команд, набирать их не стоит', 
                        'команды - список команд',
                        '"видео" "название видео" "количество" - поиск видео');
  const KEYBOARD = [["команды"],["история"]];


  dataBaseInit($db);
  telegramInit($update);
  youTubeInit($video);
  buildUserRequest($requestWords, $firstWord, $lastWord);
  
  $chatId = $update['message']['chat']['id'];
  $request = $update['message']['text'];
  $userFirstName = $update['message']['from']['first_name'];
  $userLastName = $update['message']['from']['last_name'];
  $userId = $update['message']['from']['id'];

  //$db -> where("userId", $userId);
  //$userData = $db->getOne("userHistory");
  
  switch ($firstWord): 
    case '/start': 
      buildKeeboard(KEYBOARD);
      sendRequest('sendMessage', ['chat_id' => $chatId, 
                                 'text' => 'Добро пожаловать ' . $userFirstName . ' ' . $userLastName . '!',
                                 'reply_markup' => $replyMarkup]); 
      break;
    case 'команды':
      foreach(COMANDS as $comand) {
        sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => $comand . ' ']);
      }
      break;   
    case 'видео':
    case 'Видео':
      $query = getQueryForSearch($requestWords);
      if($query && $lastWord) {
        if(is_numeric($lastWord) && $lastWord <= MAX_VIDEOS){
          $dataBySearch = $video->search($query, $lastWord); 
          sendVideos($dataBySearch, $lastWord, $chatId);
          $serchResult = buildUrlsForDb($dataBySearch, $lastWord);
          insertUserHistory($db, $userData, $serchResult, $userId);
        } elseif(!is_numeric($lastWord)) {
            sendRequest('sendMessage', ['chat_id' => $chatId, 'text' =>  '"количество" - должно быть целым числом']);
        } else {
          sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => '"количество" - не может превышать ' . MAX_VIDEOS]);
        }     
      } else {
        sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => 'не верно указаны параметры']);
      }
      break;
    case 'история':
    case 'История':
     $isFound = true;
     $isFound = sendUserHistory($userData, $chatId);
    default: 
      sendRequest('sendMessage', ['chat_id' => $chatId,
                                 'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью запроса "команды"']);
  endswitch;
