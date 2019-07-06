<?php 
    require_once(dirname(__FILE__).'/../index.php');
    
    function test($chatId, $db) 
    {
        $cols = Array ("userId");  
        $userId = $db->get ("history", null, $cols);
        foreach($userIds as $data)
        {
           sendMessage($data, $chatId);
        }
         
    }
