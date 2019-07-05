<?php
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const TELEGRAM_URL = 'https://api.telegram.org/bot' . TOKEN . '/';

  function telegramInit()
  {
      $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY); 
      return $update;
  }
  
  function replyKeyboardMarkup(array $params) 
  {
      return json_encode($params);
  }
  
  function sendRequest($method, $params = [])
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

  function sendVideos($data, $quantity, $chatId)
  {
      for($i=0; $i < $quantity; $i++)
      {
          $videoIds[$i] = $data -> items[$i] -> id['videoId']; 
          sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => YOUTUBE_URL . $videoIds[$i] ]); 
      }
  }

  function showUserHistory($db, $userId, $chatId, $quantinty)
  {
      $data = convertDataToArray($db, $userId, $quantinty);
      foreach($data as $item)
      {
          sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => $item]);
      }
  }
