<?php 
    require_once(dirname(__FILE__).'/../index.php');
    
    function test($chatId, $db) 
    {
        $userIds = $db->rawQuery('SELECT DISTINCT(userId)
                                    FROM history');
           sendMessage($userIds[0]['userId'], $chatId);
         
    }
