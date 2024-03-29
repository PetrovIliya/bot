<?php 
  require_once('config.php');
  require_once(dirname(__FILE__).'/../index.php');

  $video = youTubeInit();
  $db = dataBaseInit();

  function buildData($db)
  {
      $categoriesId = getColumn($db, CATEGORIES_ID_COLUMN, CATEGORIES_TABLE);  
      foreach($categoriesId as $id)
      {
          $temp = NULL;
          $chatIdsByCategory = $db->rawQuery('SELECT DISTINCT(chatId)
                                            FROM users
                                            WHERE userCategory = ' . $id);
          $length = count($chatIdsByCategory);
          for($i = 0; $i < $length; $i++)
          {
              $temp[$i] = $chatIdsByCategory[$i]['chatId'];
          }  
          $result[$id] = $temp;
      }    
      return $result; 
  }  

  function sendTopVideos($video, $categoryId, $chatId)
  {
       $videoInfo = $video -> getPopularVideosByCategory($categoryId, DEFAULT_TOP_VIDEO_QUANTINTY);
     /*  $categoryName = $db->rawQuery('SELECT DISTINCT(categoriesName)
                                      FROM categories
                                      WHERE categoriesId = ' . $categoryId);*/
       sendMessage(TOP_VIDEO_MESSAGE, $chatId);
       for($i=0; $i < DEFAULT_TOP_VIDEO_QUANTINTY; $i++)
       {  
           $videoId = $videoInfo["items"][$i]["id"];
           sendMessage(YOUTUBE_URL . $videoId, $chatId);
       }  
  }  

  function sendNotification($data, $video)
  {
      foreach($data as $key => $value)
      {
          if($value !== NULL)
          {
              foreach($value as $val)
              {
                  sendTopVideos($video, $key, $val);
              }  
          }  
      }  
  }  
  
  function startNotificationHandler($db, $video) 
  { 
      $data = buildData($db);
      sendNotification($data, $video);
  }

 startNotificationHandler($db, $video);
