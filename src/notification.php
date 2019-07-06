<?php 
    require_once(dirname(__FILE__).'/../index.php');
    
    function test($chatId, $db) 
    {
        $userIds = $db->get('userId');
        foreach($userIds as $data)
        {
         sendMessage($data, $chatId);
        }
         
    }
