<?php
  const HOST = 'eu-cdbr-west-02.cleardb.net';
  const USER_NAME = 'b2f8e06330d503';
  const PASSWORD = 'fb10e00e0584280';
  const DATA_BASE_NAME =  'heroku_a3471d601ba1cc5'; 
  const CHAT_ID_COLUMN = 'chatId';
  const USER_ID_COLUMN = 'userId';
  const USER_QUERY_COLUMN = 'userQuery';
  const CATEGORIES_COLUMN = 'categoriesName';
  const CATEGORIES_TABLE = 'categories';
  const HISTORY_TABLE = 'history';
  const USERS_TABLE = 'users';
  const DEFAULT_CATEGORY = 'Развлечения';
 
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

  function autoSubscribe($db, $chatId)
  {
      $chatIds = getColumn($db, CHAT_ID_COLUMN, USERS_TABLE);
      if(!isPresent($chatIds, $chatId))
      {
          $data = [CHAT_ID_COLUMN => $chatId,
                   CATEGORIES_COLUMN => DEFAULT_CATEGORY];
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
