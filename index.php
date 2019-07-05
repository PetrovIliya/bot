<?php
  require_once('vendor/autoload.php'); 
  require_once('src/telegramm.php');
  require_once('src/youtube.php');
  require_once ('src/dataBase.php');
  require_once ('src/botLogic.php');

  $video = youTubeInit();
  $db = dataBaseInit();
  $update = telegramInit();
  startBot($update, $db, $video);
