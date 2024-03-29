<?php
  require_once('config.php');   
  
  function telegramInit(): ?array
  {
      $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY); 
      return $update;
  }

  function sendRequest($method, $params = []): ?array
  {
      if(!empty($params))
      {
          $url = TELEGRAM_URL . $method . '?' . http_build_query($params);
      } 
      else 
      {
          $url = TELEGRAM_URL . $method;
      }
      return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY); 
  }
  
  function replyKeyboardMarkup(array $params) 
  {
      return json_encode($params);
  }

  function sendMessage($text, $chatId)
  {
      sendRequest('sendMessage', ['chat_id' => $chatId, 
                                  'text' => $text]);
  }

  function showKeyboard($chatId)
  {
       $keyboard = [[KEYBOARD_HISTORY_TEXT, KEYBOARD_CATEGORIES_TEXT], [KEYBOARD_COMMANDS_TEXT, KEYBOARD_UNSCRIBE_TEXT]];
       $replyMarkup = replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                           'resize_keyboard' => true,
                                           'one_time_keyboard' => false]);
      sendRequest('sendMessage', ['chat_id' => $chatId,
                                  'text' => DUBLICATION, 
                                 'reply_markup' => $replyMarkup]); 
  }  

  function sendCommands($chatId)
  {
       foreach(COMMANDS as $comand)
       {
         sendMessage($comand, $chatId);
       }
  } 
  
  function sendVideos($data, $quantity, $chatId)
  {
      for($i=0; $i < $quantity; $i++)
      {
          $videoIds[$i] = $data -> items[$i] -> id['videoId']; 
          $message = YOUTUBE_URL . $videoIds[$i];
          sendMessage($message, $chatId); 
      }
  }

  function showUserHistory($db, $userId, $chatId, $quantinty)
  {
      $data = convertDataToArray($db, $userId, $quantinty);
      foreach($data as $item)
      {
          sendMessage($item, $chatId);
      }
  }
