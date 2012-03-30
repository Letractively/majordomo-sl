<?


chdir('../');
include_once("./config.php");
include_once("./lib/loader.php");
$db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); // connecting to database

DebMes($_SERVER['REQUEST_URI']);

$command=stripslashes($_GET['command']);
$section=stripslashes($_GET['section']);
$param=stripslashes($_GET['param']);

$done=0;

if ($command!='' && file_exists('./rc/commands/'.$command.'.bat')) {
 if ($param!='') {
  safe_exec(dirname($_SERVER['SCRIPT_FILENAME']).'/commands/'.$_GET['command'].".bat \"".$param."\"");
  //$oExec = $WshShell->Run(dirname($_SERVER['SCRIPT_FILENAME']).'/commands/'.$_GET['command'].".bat \"".$param."\"", 1, false);
 } else {
  safe_exec(dirname($_SERVER['SCRIPT_FILENAME']).'/commands/'.$_GET['command'].".bat");
  //$oExec = $WshShell->Run(dirname($_SERVER['SCRIPT_FILENAME']).'/commands/'.$_GET['command'].".bat", 1, false);
 }
 $done=1;
} elseif ($command!='' && file_exists('./rc/commands/'.$command.'.sh')) {
 exec('./commands/'.$command.'.sh' . " > /dev/null &");
 $done=1;
} elseif ($command!='' && file_exists('./rc/scripts/'.$command.'.aut')) {
 if ($param!='') {
  safe_exec('start c:/homenetserver/apps/autoitv3/AutoIt3.exe '.dirname($_SERVER['SCRIPT_FILENAME']).'/scripts/'.$_GET['command'].'.aut '.$param);
 } else {
  safe_exec('start c:/homenetserver/apps/autoitv3/AutoIt3.exe '.dirname($_SERVER['SCRIPT_FILENAME']).'/scripts/'.$_GET['command'].'.aut');
 }
 $done=1;
} elseif ($command!='' && file_exists('./rc/scripts/'.$command.'.au3')) {
 if ($param!='') {
  safe_exec('start c:/homenetserver/apps/autoitv3/AutoIt3.exe '.dirname($_SERVER['SCRIPT_FILENAME']).'/scripts/'.$_GET['command'].'.au3 "'.$param.'"');
 } else {
  safe_exec('start c:/homenetserver/apps/autoitv3/AutoIt3.exe '.dirname($_SERVER['SCRIPT_FILENAME']).'/scripts/'.$_GET['command'].'.au3');
 }
 $done=1;

} elseif ($command!='') {
 echo "command not found";
}

$db->Disconnect(); // closing database connection


if ($done) {
 echo "OK";
 exit;
}

?>