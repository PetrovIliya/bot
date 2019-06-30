<?php 
  const YT_KEY = 'AIzaSyBW_jucSlgbrmgdDCV1m7Voy7aE6R1bil8';
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  const YT_URL = 'https://www.youtube.com/watch?v=';
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя/0123456789';
  const MAX_VIDEOS = 10;
  use Telegram\Bot\Api;

  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
  $chat_id = $update['message']['chat']['id'];
  $request = $update['message']['text'];
  $user_first_name = $update['message']['from']['first_name'];
  $user_last_name = $update['message']['from']['last_name'];
  $keyboard = [["команды"]];
  $comands = [ 'кавычки служат только для обозначения разделов команд, набирать их не стоит', 
               '/start - начало работы',
               'команды - список команд',
               '"видео" "название видео" "количество" - поиск видео',
               '"музыка" "название песни" "количество"- поиск музыки'
             ];
?>
