<?php
  require_once('config.php');   

  function dataBaseInit(): object
  {
      $db = new MysqliDb (HOST, USER_NAME, PASSWORD, DATA_BASE_NAME);
      return $db; 
  }
    
  function convertDataToArray($db, $userId, $quantinty): array 
  {
      $userQueries = $db->rawQuery('SELECT DISTINCT(userQuery)
                                    FROM history
                                    WHERE userId = ' . $userId . 
                                   ' ORDER BY id DESC LIMIT ' . $quantinty);
      $data = [];
      foreach ($userQueries as $query)
      {
          $tempData = str_word_count($query['userQuery'], 1, EXCEPTIONS);
          $data = array_merge($data, $tempData);
      }
    
      return array_reverse($data);
  }
 
  function insertToDataBase($db, $userId, $serchResult, $chatId)
  {
      $data = [ USER_ID_COLUMN => $userId,
                USER_QUERY_COLUMN => $serchResult,
                 CHAT_ID_COLUMN => $chatId];
      $db->insert(HISTORY_TABLE, $data);
  }
 
  function isPresent($data, $argument): bool
  {
      foreach($data as $value)
      {
          if($value == $argument)
          {
            return true;
          }  
      } 
      return false;
  }

  function buildCategoryId($db, $categoryName)
  {  
      $categoryName = mb_strtolower($categoryName);
      $categories = getColumn($db, CATEGORIES_COLUMN, CATEGORIES_TABLE);
      $categoriesId = getColumn($db, CATEGORIES_ID_COLUMN, CATEGORIES_TABLE);
      $length = count($categories);
      for($i = 0; $i < $length; $i++)
      {
        if($categories[$i] == $categoryName)
        {
            return $categoriesId[$i];
        }  
      } 
      return false;
  }  

  function changeSubscribe($db, $chatId, $category)
  {
      $db -> where(CHAT_ID_COLUMN, $chatId);
      $data = [USER_CATEGORY_COLUMN => $category];
      $db->update(USERS_TABLE, $data);
  }  
  
  function autoSubscribe($db, $chatId)
  {
      $chatIds = getColumn($db, CHAT_ID_COLUMN, USERS_TABLE);
      if(!isPresent($chatIds, $chatId))
      {
          $data = [CHAT_ID_COLUMN => $chatId,
                   USER_CATEGORY_COLUMN => DEFAULT_CATEGORY];
          $db->insert(USERS_TABLE, $data); 
      }  
  }

  function getColumn($db, $columnName, $tableName)
  {
      $categories = $db->rawQuery('SELECT DISTINCT(' . $columnName . ')
                                   FROM ' . $tableName);
      $length = count($categories);
      $data =[];
      for($i=0; $i < $length; $i++)
      {
          $data[$i] = $categories[$i][$columnName];
      }
    return $data;
  }
