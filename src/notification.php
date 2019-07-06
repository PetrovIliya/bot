<?php 
    require_once(dirname(__FILE__).'/../index.php');
    
    function test($chatId) 
    {
        sendMessage('test', $chatId);
    }
