<?php
  require_once('config.php');     

  function youTubeInit() 
  {
      $video = new YouTubeVideo();
      return $video;
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
   
      public function buildUrlsForDb ($data, $quantity): string
      {
          for($i=0; $i < $quantity; $i++)
          {
              $videoIds[$i] = $data -> items[$i] -> id['videoId']; 
              $result[$i] = YOUTUBE_URL . $videoIds[$i] . ' '; 
          }
          return implode(' ', $result);
      }
       
      public function buildVideoName($data): ?string 
      {
          $length = count($data) - 1;
          for($i=1; $i<$length; $i++) 
          {
              $result .= $data[$i] . ' ';
          }
          return $result;
      }
      
      public function search(string $q, int $maxResults=12, string $lang='ru' )
      {
          $response = $this->youtube->search->listSearch('snippet', ['q' => $q,
                                                                    'maxResults' => $maxResults,
                                                                    'relevanceLanguage' => $lang,
                                                                   'type' => 'video']);
          return $response;
      } 
    
    public function getCategory($regionCode = 'RU')
    {
        $result = $this->youtube->videoCategories->listVideoCategories('snippet',
            array('hl' => 'ru', 'regionCode' => $regionCode));

        $category = [];
        $tempCategories = $result->getItems(); //масиив объектов Google_Service_YouTube_VideoCategory

        array_walk($tempCategories, function ($value) use (&$category){
            $category[$value['id']] =  $value['snippet']['title'];
        });

        return $category;
    }
   
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
