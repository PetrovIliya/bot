<?php
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const TELEGRAM_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  const YOUTUBE_URL = 'https://www.youtube.com/watch?v=';
  const API_KEY = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ/0123456789';
  use Telegram\Bot\Api; 
     
  function youTubeInit($video) {
    $video = new YouTubeVideo();
  }
   
  function telegramInit($chatId, $request, $userFirstName, $userLastName, $userID) {
    $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
    $chatId = $update['message']['chat']['id'];
    $request = $update['message']['text'];
    $userFirstName = $update['message']['from']['first_name'];
    $userLastName = $update['message']['from']['last_name'];
    $userId = $update['message']['from']['id'];
  }  

  function buildUserRequest($requestWords, $firstWord, $lastWord){
    $requestWords = str_word_count($request, 1, EXCEPTIONS);
    $firstWord = $requestWords[0];
    $lastWord = end($requestWords);
  }

  function buildKeyboard($Keyboard) {
    $telegram = new Api(API_KEY);
    $replyMarkup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                                    'resize_keyboard' => true,
                                                    'one_time_keyboard' => false]); 
  }
  
  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = TELEGRAM_URL . $method . '?' . http_build_query($params);
    } else {
      $url = TELEGRAM_URL . $method;
    }
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
  }

  function sendVideos($data, $quantity, $chatId) {
    for($i=0; $i < $quantity; $i++) {
      $videoIds[$i] = $data -> items[$i] -> id['videoId']; 
      sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => YOUTUBE_URL . $videoIds[$i] ]); 
    }
  }

  function getQueryForSearch($data): string {
    $length = count($data) - 1;
    for($i=1; $i<$length; $i++) {
      $result .= $data[$i] . ' ';
    }
    return $result;
  }

  function buildUrlsForDb ($data, $quantity): string {
    for($i=0; $i < $quantity; $i++) {
     $videoIds[$i] = $data -> items[$i] -> id['videoId']; 
     $result[$i] = YOUTUBE_URL . $videoIds[$i]; 
    }
    return implode(' ', $result);
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
