<?php
  require_once('vendor/autoload.php'); 
  require_once('src/telegrammAPI.php');
  require_once('src/youtubeAPI.php');
  require_once ('src/dataBase.php');
  require_once ('src/botLogic.php');

  $video = youTubeInit();
  //$db = dataBaseInit();
  //$update = telegramInit();

  
  $test = $video -> getPopularVideosByCategory('1', 1);
  ?>
  <pre>
  <?php  
  var_dump($test); 
  ?>
  <pre> 
  <?php  
 // startBot($update, $db, $video);
