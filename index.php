<?php
  require_once('vendor/autoload.php'); 

  const YT_KEY = 'AIzaSyBW_jucSlgbrmgdDCV1m7Voy7aE6R1bil8';
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  use Telegram\Bot\Api; 
  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
  $chat_id = $update['message']['chat']['id'];
  $request = $update['message']['text'];
  $user_first_name = $update['message']['from']['first_name'];
  $user_last_name = $update['message']['from']['last_name'];
  $keyboard = [["/музыка"],["/видео"]];
  $comands = [
               '/start - начало работы',
               '/help - список команд',
               '/видео название видео - поиск видео',
               '/музыка название песни - поиск музыки'
             ];






class YouTubeVideo
{
    public $id; //id видео
    private $apiKey = 'AIzaSyBW_jucSlgbrmgdDCV1m7Voy7aE6R1bil8';
    private $youtube;
    public function __construct()
    {
        $client = new Google_Client();
        $client->setDeveloperKey($this->apiKey);
        $this->youtube = new Google_Service_YouTube($client);
    }
    /*
    * Получение данных видео по их id
    */
    public function videosByIds( string $ids)
    {
        return $this->youtube->videos->listVideos('snippet, statistics, contentDetails', [
            'id' => $ids,
        ]);
    }
    /**
     * Получение списка популярных видео (данные - snippet и statistics)
     */
    public function videos(int $maxResults=10, string $region='RU')
    {
        return $this->youtube->videos->listVideos('snippet, statistics, contentDetails', [
            'chart' => 'mostPopular',
            'maxResults' => $maxResults,
            'regionCode' => $region,
        ]);
    }
    /**
     * Поиск видео по фразе
     */
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
    /** Получить список категорий видео с YouTube
     * https://developers.google.com/youtube/v3/docs/videoCategories
     * Возвращает массив с id категорий
     */
    public function getCategory($regionCode = 'RU'){
        $result = $this->youtube->videoCategories->listVideoCategories('snippet',
            array('hl' => 'ru', 'regionCode' => $regionCode));
        $category = [];
        $array = $result->getItems(); //масиив объектов Google_Service_YouTube_VideoCategory
        array_walk($array, function ($value) use (&$category){
            $category[$value['id']] =  $value['snippet']['title'];
        });
        return $category;
    }
    /**
     * Поиск самых популярных видео по указанной категории
     */
    public function getPopularVideosByCategory( string $videoCategoryId, int $maxResults=10, string $region='RU', $pageToken=null){
        try {
            $response = $this->youtube->videos->listVideos('snippet, statistics, contentDetails',
                array('videoCategoryId' => $videoCategoryId,
                    'maxResults' => $maxResults,
                    'regionCode' => $region,
                    'chart' => 'mostPopular',
                    'pageToken' => $pageToken,
                ));
        } catch (\Google_Service_Exception $e){
            return false;
        }
        return $response; //массив объектов Google_Service_YouTube_Video
    }


}




  function sendRequest($method, $params = []) {
    if(!empty($params)) {
      $url = BASE_URL . $method . '?' . http_build_query($params);
    } else {
      $url = BASE_URL . $method;
    }
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
  }



  if ($request == '/start') {
   $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard,
                                                   'resize_keyboard' => true,
                                                   'one_time_keyboard' => false 
                                                  ]); 
    
   sendRequest('sendMessage', ['chat_id' => $chat_id, 
                               'text' => 'Добро пожаловать ' . $user_first_name . ' ' . $user_last_name . '!',
                               'reply_markup' => $reply_markup 
                              ]);
    
  } elseif ($request == '/help') {
      foreach($comands as $comand) {
      sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => $comand . ' ']);
    }
  } else {
    sendRequest('sendMessage', ['chat_id' => $chat_id, 'text' => 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью /help']);
  }

  $dataById = $video->videosByIds('FBnAZnfNB6U');
  var_dump($dataById);

?>
