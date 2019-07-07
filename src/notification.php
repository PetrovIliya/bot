<?php 
  require_once(dirname(__FILE__).'/../index.php');
  require_once('config.php');

  function buildData($db)
  {
      $categoriesId = getColumn($db, CATEGORIES_ID_COLUMN, CATEGORIES_TABLE);  
      foreach($categoriesId as $id)
      {
          $temp = NULL;
          $chatIdsByCategory = $db->rawQuery('SELECT DISTINCT(chatId)
                                            FROM users
                                            WHERE userCategory = ' . $id);
          $length = count($chatIdsByCategory);
          for($i = 0; $i < $length; $i++)
          {
              $temp[$i] = $chatIdsByCategory[$i]['chatId'];
          }  
          $result[$id] = $temp;
      }    
      return $result; 
  }  

  function sendNotification($data)
  {
      foreach($data as $key => $value)
      {
          if($value !== NULL)
          {
              foreach($value as $val)
              {
                  echo $key . ' ' . $val;
                  
              }  
          }  
      }  
  }  
  
  function startNotificationHandler($db) 
  { 
      $data = buildData($db);
      sendNotification($data);
      return $data;
  }
