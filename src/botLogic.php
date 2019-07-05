<?php
const DEFAULT_QUANTINTY = 5;
  const MAX_VIDEOS = 10;
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ/0123456789./?=-_:';  
  const COMMANDS = ['кавычки служат только для обозначения разделов команд, набирать их не стоит', 
                   'команды - список команд',
                   '"видео" "название видео" "количество" - поиск видео',
                   'история - история пяти последних запросов',
                   '"история" "количество" - история запросов в количестве "количество"'];

  $video = youTubeInit();
  $db = dataBaseInit();
  $update = telegramInit();
  $chatId = $update['message']['chat']['id'];
  $userId = $update['message']['from']['id'];
  $userFirstName = $update['message']['from']['first_name'];
  $userLastName = $update['message']['from']['last_name'];
  $request = $update['message']['text'];
  $keyboard = [["команды"],["история"]];
  $requestWords = str_word_count($request, 1, EXCEPTIONS);
  $lastWord = end($requestWords);
  $firstWord = $requestWords[0];
  switch ($firstWord): 
    case '/start': 
      $replyMarkup = replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                           'resize_keyboard' => true,
                                           'one_time_keyboard' => false]);
      sendRequest('sendMessage', ['chat_id' => $chatId, 
                                 'text' => 'Добро пожаловать ' . $userFirstName . ' ' . $userLastName . '!',
                                 'reply_markup' => $replyMarkup]); 
      break;
    case 'Команды':
    case 'команды':
      foreach(COMMANDS as $comand) {
        sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => $comand]);
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
          insertToDataBase($db, $userId, $serchResult);
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
      if($lastWord == 'история' || $lastWord == 'История') {
        $dataBaseData = convertDataToArray($db, $userId, DEFAULT_QUANTINTY);
        showUserHistory($dataBaseData, $userId, $chatId);
      } else {
        if(is_numeric($lastWord)) {
          $dataBaseData = convertDataToArray($db, $userId, $lastWord);
          showUserHistory($dataBaseData, $userId, $chatId);
        } else {
          sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => '"количество" - должно быть целым числом']);
        }  
      }
      break;
    default: 
      sendRequest('sendMessage', ['chat_id' => $chatId,
                                  'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью запроса "команды"']);
  endswitch;
