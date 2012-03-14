<?
/**
* Test script
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 1.3
*/

 include_once("./config.php");
 include_once("./lib/loader.php");

 $db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); // connecting to database

 header ('Content-Type: text/html; charset=utf-8');

 //echo GoogleTTS('Привет');

 //echo timeBetween('22:00', '13:00');
 //sendmail_html('info@atmatic.com', 'jey@tut.by', 'Hi there!', 'Hello world!!!');
 //echo date('Y-m-d H:i:s', time());
 //phpinfo();
 getObject('ThisComputer')->setProperty('minMsgLevel', 1);
 
 $db->Disconnect(); // closing database connection


?>