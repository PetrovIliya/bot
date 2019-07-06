<?php 
    require_once(dirname(__FILE__).'/../index.php');
    
    function test($chatId, $db) 
    {
        $userId = $db->get ("history", null, 'userId');
        foreach($userIds as $data)
        {
         //sendMessage($data, $chatId);
        }
         
    }
