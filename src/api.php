<?php
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const TELEGRAM_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяВ/0123456789';
  const MAX_VIDEOS = 10;
 
  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = TELEGRAM_URL . $method . '?' . http_build_query($params);
    } else {
      $url = TELEGRAM_URL . $method;
    }
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
  }

  function sendVideos($data, $quantity, $chat_id) {
            for($i=0; $i < $quantity; $i++) {
               $video_ids[$i] = $data -> items[$i] -> id['videoId']; 
               sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => YT_URL . $video_ids[$i] ]); 
            }
  }

  function getQuery($data) {
    $length = count($data) - 1;
    for($i=1; $i<$length; $i++) {
      $result .= $data[$i];
    }
    return $result;
  }

 class YouTubeVideo
{
    public $id; 
    private $apiKey = 'AIzaSyAck9hXA-ov9ar1toE9u0MgmCiR-Xazqj8';
    private $youtube;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setDeveloperKey($this->apiKey);
        $this->youtube = new Google_Service_YouTube($client);
    }

    public function videosByIds( string $ids)
    {
        return $this->youtube->videos->listVideos('snippet, statistics, contentDetails', [
            'id' => $ids,
        ]);
    }

    public function videos(int $maxResults=10, string $region='RU')
    {
        return $this->youtube->videos->listVideos('snippet, statistics, contentDetails', [
            'chart' => 'mostPopular',
            'maxResults' => $maxResults,
            'regionCode' => $region,
        ]);
    }

    public function search(string $q, int $maxResults=12, string $lang='ru' ){
        $response = $this->youtube->search->listSearch('snippet',
            array(
                'q' => $q,
                'maxResults' => $maxResults,
                'relevanceLanguage' => $lang,
                'type' => 'video'
            ));
        return $response;
    } 
}
