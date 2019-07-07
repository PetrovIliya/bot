<?php
  require_once('vendor/autoload.php'); 
  require_once('src/telegrammAPI.php');
  require_once('src/youtubeAPI.php');
  require_once ('src/dataBase.php');
  require_once ('src/botLogic.php');

  $video = youTubeInit();
  //$db = dataBaseInit();
  //$update = telegramInit();

  $some = $video -> getCategory();
  
  //$test = $video -> getPopularVideosByCategory($some[0]);
  var_dump($some);
 // startBot($update, $db, $video);
