<?php
/**
* Russian language file
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 1.0
*/


$dictionary=array(

/* general */
'WIKI_URL'=>'http://smartliving.ru/',
'CONTROL_PANEL'=>'Панель управления',
'TERMINAL'=>'Терминал',
'USER'=>'Пользователь',
'SELECT'=>'выбрать',
'CONTROL_MENU'=>'Меню',
'YOU_ARE_HERE'=>'Вы здесь',
'FRONTEND'=>'Веб-сайт',
'MY_ACCOUNT'=>'Мой аккаунт',
'LOGOFF'=>'Выйти',
'MODULE_DESCRIPTION'=>'Описание модуля',

'SECTION_OBJECTS'=>'Объекты',
'SECTION_APPLICATIONS'=>'Приложения',
'SECTION_SETTINGS'=>'Настройки',
'SECTION_SYSTEM'=>'Система',

/* end general */

/* module names */
'APP_GPSTRACK'=>'GPS-трэкер',
'APP_PLAYER'=>'Плэер',
'APP_MEDIA_BROWSER'=>'Медиа',
'APP_PRODUCTS'=>'Продукты',
'APP_TDWIKI'=>'Блокнот',
'APP_WEATHER'=>'Погода',

'MODULE_OBJECTS_HISTORY'=>'История объектов',
'MODULE_BT_DEVICES'=>'Bluetooth-устройства',
'MODULE_CONTROL_MENU'=>'Меню управления',
'MODULE_OBJECTS'=>'Объекты',
'MODULE_PINGHOSTS'=>'Устройства Online',
'MODULE_SCRIPTS'=>'Сценарии',
'MODULE_USB_DEVICES'=>'USB-устройства',
'MODULE_WATCHFOLDERS'=>'Папки',
'MODULE_LAYOUTS'=>'Домашние страницы',
'MODULE_LOCATIONS'=>'Расположение',
'MODULE_RSS_CHANNELS'=>'Каналы RSS',
'MODULE_SETTINGS'=>'Общие настройки',
'MODULE_TERMINALS'=>'Терминалы',
'MODULE_USERS'=>'Пользователи',
'MODULE_EVENTS'=>'События',
'MODULE_JOBS'=>'Задания',
'MODULE_MASTER_LOGIN'=>'Пароль доступа',
'MODULE_SAVERESTORE'=>'Сохранение/обновление',
'MODULE_WEBVARS'=>'Веб-переменные',
'MODULE_PATTERNS'=>'Шаблоны поведения',
/* end module names */

);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}