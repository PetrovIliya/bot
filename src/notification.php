<?php 
  require_once(dirname(__FILE__).'/../index.php');
  require_once('config.php');
  
  function test($db) 
  { 
      $categoriesId = getColumn($db, CATEGORIES_ID_COLUMN, CATEGORIES_TABLE);  
      var_dump($categoriesId);
      foreach($categoriesId as $id)
      {
          $chatIdsByCategory = $db->rawQuery('SELECT DISTINCT(chatId)
                                            FROM users
                                            WHERE userCategory = ' . $id);  
          $result[$id] = $chatIdsByCategory;
          return $result;
      }    
       
  }
