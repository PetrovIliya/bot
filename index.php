<?php
  require_once('vendor/autoload.php'); 
  require_once('src/telegramm.php');
  require_once('src/youtube.php');
  require_once ('src/dataBase.php');
 
  startBot($update, $db, $video);
