<?php 
    require_once(dirname(__FILE__).'/../index.php');
    
    function test($chatId, $db) 
    {
        $userIds = $db->get('userID');
        forech($userIds as $data)
        {
          sendMessage($data, $chatId);
        }
         
    }
