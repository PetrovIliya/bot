<?php
  require_once('notification.php');
  const PARAMETRS_ERROR_MESSAGE = 'не верно указаны параметры';
  const QUANTINTY_ERROR_MESSAGE = '<количество> - должно быть целым числом';
  const MAX_QUANTINTY_ERROR_MESSAGE = '<количество> - не может превышать 10';
  const UNSCRIBE_MESSAGE = 'Вы успешно отписаны от уведомлений';
  const USER_ERROR_MESSAGE = 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью запроса <команды>"';
  const DEFAULT_HISTORY_QUANTINTY = 5;
  const DEFALT_CATEGORY = 'развлечения';
  const MAX_VIDEOS = 10;
  const START_COMMAND = '/start';
  const VIDEO_COMMAND = 'видео';
  const ALL_COMMANDS_COMMAND = 'команды';
  const HISTORY_COMMAND = 'история';
  const CATEGORIES_COMAND = 'категории';
  const UNSCRIBE_COMMAND = 'отписаться';
  const SUBSCRIBE_COMAND = 'подписаться';
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
          insertToDataBase($db, $userId, $serchResult, $chatId);
      } 
      elseif(!is_numeric($lastWord))
      {
          sendMessage(QUANTINTY_ERROR_MESSAGE, $chatId);
      } 
      else
      {
          sendMessage(MAX_QUANTINTY_ERROR_MESSAGE, $chatId);
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
          sendMessage(PARAMETRS_ERROR_MESSAGE, $chatId);
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
              sendMessage(QUANTINTY_ERROR_MESSAGE, $chatId);
          }  
      }
  }

  function categoriesLogicHandler($db, $chatId)
  {
       $categories = getColumn($db, 'categoriesName', 'categories');
       foreach($categories as $category)
       {
           sendMessage($category, $chatId);
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
              autoSubscribe($db, $chatId);
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
          case CATEGORIES_COMAND:
              categoriesLogicHandler($db, $chatId);
              break;
          case UNSCRIBE_COMMAND:
              unscribe($db, $chatId);
              sendMessage(UNSCRIBE_MESSAGE, $chatId);
              break;
          case SUBSCRIBE_COMAND:
              subscribe($db, $chatId);
              break;
          default: 
              sendMessage(USER_ERROR_MESSAGE, $chatId);
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
      if($isCorrect)
      {   
          botLogicHandler($db, $video, $request, $greatings, $chatId, $userId);
      }  
      else
      {
          sendMessage('Произошла ошибка по техническим причинам, пожалуйста повторите попытку', $chatId);
      }  
  }
