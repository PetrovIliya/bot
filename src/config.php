<?php
  const YOUTUBE_URL = 'https://www.youtube.com/watch?v=';
  const TOKEN = '831061547:AAFwm0s2dLQIWLhRHJljKVVRv4aTzwpbgI0';
  const TELEGRAM_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
  const KEYBOARD_COMMANDS_TEXT = 'Команды';
  const KEYBOARD_UNSCRIBE_TEXT = 'Отписаться';
  const KEYBOARD_HISTORY_TEXT = 'История';
  const KEYBOARD_CATEGORIES_TEXT = 'Категории';
  const DUBLICATION = 'Выполняю...';
  const HOST = 'eu-cdbr-west-02.cleardb.net';
  const USER_NAME = 'b2f8e06330d503';
  const PASSWORD = 'fb10e00e0584280';
  const DATA_BASE_NAME =  'heroku_a3471d601ba1cc5'; 
  const CHAT_ID_COLUMN = 'chatId';
  const USER_ID_COLUMN = 'userId';
  const USER_QUERY_COLUMN = 'userQuery';
  const CATEGORIES_COLUMN = 'categoriesName';
  const CATEGORIES_ID_COLUMN = 'categoriesId';
  const USER_CATEGORY_COLUMN = 'userCategory';
  const CATEGORIES_TABLE = 'categories';
  const HISTORY_TABLE = 'history';
  const USERS_TABLE = 'users';
  const DEFAULT_CATEGORY = 24;
  const UNSCRIBED = NULL;
  const SUBSCRIBE_ERROR_MESSAGE = 'Не верно указана категория, либо такой не существует';
  const PARAMETRS_ERROR_MESSAGE = 'не верно указаны параметры';
  const QUANTINTY_ERROR_MESSAGE = '<количество> - должно быть целым числом';
  const MAX_QUANTINTY_ERROR_MESSAGE = '<количество> - не может превышать 10';
  const FATAL_ERROR_MESSAGE = 'Произошла критическая ошибка, пожалуйста повторите запрос';
  const UNSCRIBE_MESSAGE = 'Вы успешно отписаны от уведомлений';
  const TOP_VIDEO_MESSAGE = 'Топовые видео на сегодня';
  const USER_ERROR_MESSAGE = 'Запрос не является командой, со списком доступных команд можно ознакомится с помощью запроса <команды>"';
  const SUBSCRIBE_MESSAGE = 'Теперь вы будете получать уведомления на тему ';
  const DEFAULT_TOP_VIDEO_QUANTINTY = 5;
  const DEFAULT_HISTORY_QUANTINTY = 5;
  const DEFALT_CATEGORY = 'развлечения';
  const MAX_VIDEOS = 10;
  const START_COMMAND = '/start';
  const VIDEO_COMMAND = 'видео';
  const ALL_COMMANDS_COMMAND = 'команды';
  const HISTORY_COMMAND = 'история';
  const CATEGORIES_COMAND = 'категории';
  const UNSCRIBE_COMMAND = 'отписаться';
  const SUBSCRIBE_COMAND = 'подписаться';
  const EXCEPTIONS = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ0123456789./?=-_:';  
  const COMMANDS = ['знаки "<" и ">" служат только для обозначения разделов команд.', 
                   '<подписаться> <название категории> - получать уведомления на выбранную категорию',
                    'уведомления приходят каждый день в 12:00 MSK',
                   '<видео> <название видео> <количество> - поиск видео',
                   '<история> <количество> - история запросов в количестве <количество>']; 
