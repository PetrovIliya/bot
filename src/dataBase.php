<?php
  const QUERY_LENGTH = 5;
  const HOST = 'eu-cdbr-west-02.cleardb.net';
  const USER_NAME = 'b2f8e06330d503';
  const PASSWORD = 'fb10e00e0584280';
  const DATA_BASE_NAME =  'heroku_a3471d601ba1cc5'; 
 
  function dataBaseInit() {
    $db = new MysqliDb (HOST, USER_NAME, PASSWORD, DATA_BASE_NAME);
    return $db; 
  }
    
  function placeForInsert($userData) {
   $isEmpty = false;
   for($i = 1; $i <= QUERY_LENGTH; $i++) {
     if(empty($userData['userQuery' . $i])) {
       $isEmpty = true;
       break;
   }
     }
     if ($isEmpty) {
       return $i;
     } else {
       return ++$i;
     }
  }

  function insertUserHistory ($db, $userData, $serchResult, $userId) {
    if($userData) {
      $insertPlace = placeForInsert($userData);
      if($insertPlace <= QUERY_LENGTH) {
        $userQuery = 'userQuery' . $insertPlace;
        $data = [$userQuery => $serchResult];
        $db->where('userId', $userId);
        $db->update('userHistory', $data);
      } else {
        $data=['userQuery1' => $userData['userQuery2'],
               'userQuery2' => $userData['userQuery3'],
               'userQuery3' => $userData['userQuery4'],
               'userQuery4' => $userData['userQuery5'],
               'userQuery5' => $serchResult      ];
        $db->where('userId', $userId);
        $db->update('userHistory', $data);
      }
    } else {
      $data=['userId' => $userId,
          'userQuery1' => $serchResult];
      $db->insert('userHistory', $data);
    }
  }


  function sendUserHistory($userData, $chatId) {
     if($userData) {
       foreach($userData as $fieldName => $fieldValue) {
         if($fieldName !== 'userId' && !empty($fieldValue)) {
           sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => $fieldValue]);
         } elseif(empty($fieldValue)) {
           break;
         }
       } 
     } else {
       return false;
     } 
  } 
