<?php 
  require_once(dirname(__FILE__).'/../index.php');
  require_once('config.php');
  
  function test($db) 
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
