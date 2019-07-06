<?php
  require_once('vendor/autoload.php'); 
 // require_once('src/telegrammAPI.php');
  require_once('src/youtubeAPI.php');
  require_once ('src/dataBase.php');
  //require_once ('src/botLogic.php');

  $video = youTubeInit();
  $db = dataBaseInit();
 // $update = telegramInit();
  $some = $video -> getCategory();
  foreach($some as $som)
  {
    $data = [ 'categoriesName' => $som];
    $db->insert('categories', $data);
  };
  //startBot($update, $db, $video);
