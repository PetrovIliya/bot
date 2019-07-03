<?php
  require_once('vendor/autoload.php'); 
  require_once('src/api.php');
  require_once ('src/dataBase.php');
  use Telegram\Bot\Api;
  const MAX_VIDEOS = 10;
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ/0123456789';  
  const COMANDS = array('Данный бот находится на стадии разработки, некоторый функционал может быть не доступен',
                        'кавычки служат только для обозначения разделов команд, набирать их не стоит', 
                        'команды - список команд',
                        '"видео" "название видео" "количество" - поиск видео');
  const HOST = 'eu-cdbr-west-02.cleardb.net';
  const USER_NAME = 'b2f8e06330d503';
  const PASSWORD = 'fb10e00e0584280';
  const DATA_BASE_NAME =  'heroku_a3471d601ba1cc5';
  const KEYBOARD = [["команды"],["история"]];
 
  $video = new YouTubeVideo();
  $db = new MysqliDb (HOST, USER_NAME, PASSWORD, DATA_BASE_NAME);
 
 
  $requestWords = str_word_count($request, 1, EXCEPTIONS);
  $lastWord = end($requestWords);
  $db -> where("userId", $userId);
  $userData = $db->getOne("userHistory");

  switch ($requestWords[0]): 
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
