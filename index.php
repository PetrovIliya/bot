<?php
  include('vendor/autoload.php'); //Подключаем библиотеку
  use Telegram\Bot\Api; 
  $telegram = new Api('831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0');
  $result = $telegram -> getWebhookUpdates();
  echo $telegram;
?>
