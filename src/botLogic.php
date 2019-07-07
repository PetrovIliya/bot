<?php
  require_once('notification.php');
  require_once('config.php');  

  function buildUserQuery($data): ?string 	
  {	
      $length = count($data);	
      for($i = 1; $i <= $length; $i++) 	
      {	
          $result .= $data[$i] . ' ';	
      }	
      return $result;	
  }

  function buildCategoryId($db, $categoryName)
  {  
      $categoryName = mb_strtolower($categoryName);
      $categories = getColumn($db, CATEGORIES_COLUMN, CATEGORIES_TABLE);
      $categoriesId = getColumn($db, CATEGORIES_ID_COLUMN, CATEGORIES_TABLE);
      $length = count($categories);
      for($i = 0; $i <= $length; $i++)
      {
        if($categories[$i] == $categoryName)
        {
            return $categoriesId[$i];
        }  
      } 
      return false;
  }  

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
      $query = buildUserQuery($requestWords);
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
       $categories = getColumn($db, CATEGORIES_COLUMN, CATEGORIES_TABLE);
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
              changeSubscribe($db, $chatId, UNSCRIBED);
              sendMessage(UNSCRIBE_MESSAGE, $chatId);
              break;
          case SUBSCRIBE_COMAND:
              $categoryName = buildUserQuery($requestWords);
              $categoryId = buildCategoryId($db, $categoryName); 
              sendMessage($categoryId, $chatId);
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
