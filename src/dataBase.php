<?php
  const HOST = 'eu-cdbr-west-02.cleardb.net';
  const USER_NAME = 'b2f8e06330d503';
  const PASSWORD = 'fb10e00e0584280';
  const DATA_BASE_NAME =  'heroku_a3471d601ba1cc5'; 
 
  function dataBaseInit(): object
  {
      $db = new MysqliDb (HOST, USER_NAME, PASSWORD, DATA_BASE_NAME);
      return $db; 
  }
    
  function convertDataToArray($db, $userId, $quantinty) 
  {
      $userQueries = $db->rawQuery('SELECT DISTINCT(userQuery)
                                    FROM history
                                    WHERE userId = ' . $userId . 
                                   ' ORDER BY id DESC LIMIT ' . $quantinty);
      $data = [];
      foreach ($userQueries as $query): array
      {
          $tempData = str_word_count($query['userQuery'], 1, EXCEPTIONS);
          $data = array_merge($data, $tempData);
      }
    
      return array_reverse($data);
  }
 
  function insertToDataBase($db, $userId, $serchResult)
  {
      $data = [ 'userId' => $userId,
                'userQuery' => $serchResult];
      $db->insert('history', $data);
  }
