<?php 
    require_once(dirname(__FILE__).'/../index.php');
    require_once('config.php');
    
    function test($chatId, $db) 
    {
        $userIds = $db->rawQuery('SELECT DISTINCT(userId)
                                    FROM history');
     
         
    }
