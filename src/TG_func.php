<?php

  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = BASE_URL . $method . '?' . http_build_query($params);
    } else {
      $url = BASE_URL . $method;
    }
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
  }

  function sendVideos($data, $quantity, $chat_id) {
            for($i=0; $i < $quantity; $i++) {
             //  $video_titles[$i] = $data -> item[$i] ->snippet['title'];
               $video_ids[$i] = $data -> items[$i] -> id['videoId']; 
              // sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $video_titles[$i] ]); 
               sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => YT_URL . $video_ids[$i] ]); 
            }
  }

  function getQuery($data) {
    $length = count($data) - 1;
    for(i=1; i<$length; i++) {
      $result[$i] = $data[$i];
    }
    return $result;
  }
 
?>
