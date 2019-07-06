<?php 
    require_once(realpath('../index.php'));
    
    function test($chatId) 
    {
        sendMessage('test', $chatId);
    }
