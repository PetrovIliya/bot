<?php
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const TELEGRAM_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  const KEYBOARD_COMMANDS_TEXT = 'Команды';
  const KEYBOARD_HISTORY_TEXT = 'История';

  function telegramInit(): array
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
       $keyboard = [[KEYBOARD_COMMANDS_TEXT],[KEYBOARD_HISTORY_TEXT]];
       $replyMarkup = replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                           'resize_keyboard' => true,
                                           'one_time_keyboard' => false]);
      sendRequest('sendMessage', ['chat_id' => $chatId, 
                                 'reply_markup' => $replyMarkup]); 
  }  

  function showInlineKeyBoard($chatId)
  {
      $keyboard = [[KEYBOARD_COMMANDS_TEXT],[KEYBOARD_HISTORY_TEXT]];
      $replyMarkup = replyKeyboardMarkup([ 'inline_keyboard' => $keyboard]);
      sendRequest('sendMessage', ['chat_id' => $chatId, 
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
