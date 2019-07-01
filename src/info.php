<?php 
 
  use Telegram\Bot\Api;

  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  $update = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
  $chat_id = $update['message']['chat']['id'];
  $request = $update['message']['text'];
  $user_first_name = $update['message']['from']['first_name'];
  $user_last_name = $update['message']['from']['last_name'];
  $keyboard = [["команды"]];
  $comands = [ 'Данный бот находится на стадии разработки, некоторый функционал может быть не доступен',
               'кавычки служат только для обозначения разделов команд, набирать их не стоит', 
               'команды - список команд',
               '"видео" "название видео" "количество" - поиск видео',
               '"музыка" "название песни" "количество"- поиск музыки'
             ];
  $requestWords = str_word_count($request, 1, EXCEPTIONS);
  $lastWord = end($requestWords);
?>
