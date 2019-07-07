<?php
  require_once('vendor/autoload.php'); 
  require_once('src/telegrammAPI.php');
  require_once('src/youtubeAPI.php');
  require_once ('src/dataBase.php');
  require_once ('src/botLogic.php');
  require_once ('src/notification.php');

  $video = youTubeInit();
  $db = dataBaseInit();
  $update = telegramInit();
  startBot($update, $db, $video);


startNotificationHandler($db, $video);
