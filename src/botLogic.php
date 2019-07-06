<?php
  include_once ('../notification.php');
  const DEFAULT_HISTORY_QUANTINTY = 5;
  const MAX_VIDEOS = 10;
  const START_COMMAND = '/start';
  const VIDEO_COMMAND = 'видео';
  const ALL_COMMANDS_COMMAND = 'команды';
  const HISTORY_COMMAND = 'история';
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ0123456789./?=-_:';  
  const COMMANDS = ['знаки "<" и ">" служат только для обозначения разделов команд, набирать их не стоит', 
                   'команды - список команд',
                   '<видео> <название видео> <количество> - поиск видео',
                   'история - история пяти последних запросов',
                   '<история> <количество> - история запросов в количестве <количество>']; 

  function checkData($dataForCheck): bool
  {
      foreach($dataForCheck as $data)
      {
          if(!isset($data))
          {
            return false;
          }    
      }
      return true;
  }
  
  function sendingVideoHandler($video, $db, $query, $lastWord, $chatId, $userId)
  {
      if(is_numeric($lastWord) && $lastWord <= MAX_VIDEOS)
      {
          $dataBySearch = $video -> search($query, $lastWord); 
          sendVideos($dataBySearch, $lastWord, $chatId);
          $serchResult = $video -> buildUrlsForDb($dataBySearch, $lastWord);
          insertToDataBase($db, $userId, $serchResult);
      } 
      elseif(!is_numeric($lastWord))
      {
          sendMessage('"количество" - должно быть целым числом', $chatId);
      } 
      else
      {
          sendMessage('"количество" - не может превышать ' . MAX_VIDEOS, $chatId);
      }     
  }  

  function videoLogicHandler($video, $db, $chatId, $userId, $requestWords)
  {
      $lastWord = end($requestWords);
      $query = $video -> buildVideoName($requestWords);
      if($query && $lastWord) 
      {
         sendingVideoHandler($video, $db, $query, $lastWord, $chatId, $userId);
      } 
      else 
      {
          sendMessage('не верно указаны параметры', $chatId);
      }
  }

  function historyLogicHandler($db, $userId, $chatId, $lastWord)
  {
      $lastWord = mb_strtolower($lastWord);
      if($lastWord == HISTORY_COMMAND) 
      {
          showUserHistory($db, $userId, $chatId, DEFAULT_HISTORY_QUANTINTY);
      }
      else
      {
          if(is_numeric($lastWord))
          {
              showUserHistory($db, $userId, $chatId, $lastWord);
          }
          else
          {
              sendMessage('"количество" - должно быть целым числом', $chatId);
          }  
      }
  }

  function botLogicHandler($db, $video, $request, $greatings, $chatId, $userId)
  {
      $requestWords = str_word_count($request, 1, EXCEPTIONS);
      $lastWord = end ($requestWords);
      $firstWord = mb_strtolower($requestWords[0]);
      switch ($firstWord): 
          case START_COMMAND: 
              sendMessage($greatings, $chatId); 
              break;
          case ALL_COMMANDS_COMMAND:
              sendCommands($chatId);
              break;     
          case VIDEO_COMMAND:
              videoLogicHandler($video, $db, $chatId, $userId, $requestWords);
              break;
          case HISTORY_COMMAND:
              historyLogicHandler($db, $userId, $chatId, $lastWord);
              break;
          default: 
             sendMessage('Запрос не является командой, со списком доступных команд можно ознакомится с помощью запроса "команды"',
                          $chatId);
          endswitch;
  }  

  function startBot($update, $db, $video)
  {
      $chatId = $update['message']['chat']['id'];
      $userId = $update['message']['from']['id'];
      $firstName = $update['message']['from']['first_name'];
      $lastName = $update['message']['from']['last_name'];
      $request = $update['message']['text'];
      $greatings = 'Добро пожаловать ' . $firstName . ' ' . $lastName . '!';
      $dataForCheck = [$chatId, $userId, $request];
      $isCorrect = checkData($dataForCheck);
      showKeyboard($chatId);
     // test($chatId);
      if($isCorrect)
      {   
          botLogicHandler($db, $video, $request, $greatings, $chatId, $userId);
      }  
      else
      {
          sendMessage('Произошла ошибка по техническим причинам, пожалуйста повторите попытку', $chatId);
      }  
  }
