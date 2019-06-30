<?php

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
    
    public function getDataVideo(array $videos){
    $dataset = [];
    array_walk($videos, function ($value) use (&$dataset){

        $dataset[] = [
            'id' => $value->toSimpleObject()->id,
            'title' => $value->toSimpleObject()->snippet['title'],
            'thumbnails' => [
                'default' =>  $value->toSimpleObject()->snippet['thumbnails']['default']['url'],
                'medium' =>  $value->toSimpleObject()->snippet['thumbnails']['medium']['url'],
            ],
            'viewCount' => $value->toSimpleObject()->statistics['viewCount'] ?? '-',
            'duration' => $this->timeFormatting($value->toSimpleObject()->contentDetails['duration'])
        ];
    });

    return $dataset;
}
    
}



   $video = new YouTubeVideo();
?>
