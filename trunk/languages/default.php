<?php
/**
* Default language file
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 1.0
*/


$dictionary=array(

/* general */
'WIKI_URL'=>'http://smartliving.ru/',
'CONTROL_PANEL'=>'Control Panel',
'TERMINAL'=>'Terminal',
'USER'=>'User',
'SELECT'=>'select',
'CONTROL_MENU'=>'Control Menu',
'YOU_ARE_HERE'=>'You are here',
'FRONTEND'=>'Front End',
'MY_ACCOUNT'=>'My Account',
'LOGOFF'=>'Logoff',
'MODULE_DESCRIPTION'=>'Module Description on the Web',

'SECTION_OBJECTS'=>'Objects',
'SECTION_APPLICATIONS'=>'Applications',
'SECTION_SETTINGS'=>'Settings',
'SECTION_SYSTEM'=>'System',

/* end general */

/* module names */
'APP_GPSTRACK'=>'GPS Tracker',
'APP_PLAYER'=>'Player Control',
'APP_MEDIA_BROWSER'=>'Media Library',
'APP_PRODUCTS'=>'Products Inventory',
'APP_TDWIKI'=>'TdWiKi Notepad',
'APP_WEATHER'=>'Weather Informer',

'MODULE_OBJECTS_HISTORY'=>'Objects History',
'MODULE_BT_DEVICES'=>'Bluetooth Devices',
'MODULE_CONTROL_MENU'=>'Control Menu',
'MODULE_OBJECTS'=>'Objects',
'MODULE_PINGHOSTS'=>'Hosts Online',
'MODULE_SCRIPTS'=>'Scripts',
'MODULE_USB_DEVICES'=>'USB Devices',
'MODULE_WATCHFOLDERS'=>'Folders',
'MODULE_LAYOUTS'=>'Home Pages',
'MODULE_LOCATIONS'=>'Locations',
'MODULE_RSS_CHANNELS'=>'RSS Channels',
'MODULE_SETTINGS'=>'General Settings',
'MODULE_TERMINALS'=>'Terminals',
'MODULE_USERS'=>'Users',
'MODULE_EVENTS'=>'Events',
'MODULE_JOBS'=>'Scheduled Jobs',
'MODULE_MASTER_LOGIN'=>'Panel Login',
'MODULE_SAVERESTORE'=>'Backup/Upgrade',
'MODULE_WEBVARS'=>'Web Variables',

'MODULE_DASHBOARD'=>'Dashboard',
'MODULE_DATESELECTOR'=>'Dateselector',
'MODULE_METHODS'=>'Methods',
'MODULE_OBJECT_INSTANCES'=>'Object Instances',
'MODULE_PROPERTIES'=>'Object Properties',
'MODULE_PVALUES'=>'PValues',
'MODULE_SHOUTBOX'=>'Shoutbox',
'MODULE_SHOUTROOMS'=>'Shoutrooms',
'MODULE_SKINS'=>'Design Skins',
'MODULE_THUMB'=>'Thumbnailer',
'MODULE_USERLOG'=>'Users Log',
'MODULE_PATTERNS'=>'Patterns',

/* end module names */

/* languages constants */
'LANGUAGES_STRING_LANGUAGES'=>                               'Languages',
'LANGUAGES_STRING_LANGUAGE'=>                                'Language',
'LANGUAGES_STRING_ERROR_FILE_READONLY'=>             'Error writing file. File is read only.',
'LANGUAGES_STRING_ERROR_FILE_WRITE'=>                'Error writing file. Can not write in file.',
'LANGUAGES_STRING_ERROR_EMPTY_FILE'=>                'Error opening file. File is empty.',
'LANGUAGES_STRING_ERROR_NOT_FOUND'=>                 'Error opening file. File not found.',
'LANGUAGES_STRING_ERROR_ADD_NEW'=>                   'Error adding new language file.',
'LANGUAGES_STRING_ERROR_COPY_NEW'=>                  'Error copy data to new language file.',
'LANGUAGES_STRING_ERROR_SELECT_LANGUAGES'=>  'Select languages and if you want to add new then fill language title field.',
'LANGUAGES_STRING_SHOW_ALL_VALUES'=>                 'show all values',
'LANGUAGES_TRANSLATE_FROM'=>                                 'Translate from',
'LANGUAGES_TRANSLATE_TO'=>                                   'to',
'LANGUAGES_ADD_NEW_LANGUAGE'=>                               'add new language',
'LANGUAGES_LANGUAGE_TITLE'=>                                 'language title',
'LANGUAGES_APPLY'=>                                                  'Apply',
'LANGUAGES_SYNCHRONIZE_WITH'=>                               'Syncronize lang files with'
/* end languages constants */

);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}