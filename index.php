<?php
  require_once('vendor/autoload.php'); 
  require_once('src/info.php');
  require_once('src/YT_func.php');
  require_once('src/TG_func.php');
 
  if($request) {
    checkRequest();
  }  

 $dataBySearch = $video->search('космос', 2); 
  // $dataBySearch = $video->getDataVideo($dataBySearch->getItems());
 $video_ids = $dataBySearch -> items[0] -> id['videoId'];


/*
?>
  <pre>
<?php
  var_dump($dataBySearch);  
?>
</pre>
<?php
  
  $videoId = $dataBySearch['id'];
  var_dump($videoId); */


?>
