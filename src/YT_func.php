<?php

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



   $video = new YouTubeVideo();
?>
