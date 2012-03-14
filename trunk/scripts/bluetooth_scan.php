<?
/*
* @version 0.3 (06.09.2011 bug fixed)
*/

 include_once("./config.php");
 include_once("./lib/loader.php");

 set_time_limit(0);
 $connected=0;
 while(!$connected) {
  $connected=@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  sleep(5);
 }

 $db=new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); // connecting to database

 echo "CONNECTED TO DB\n";

 $bt_devices=array();
 $devices_file=DOC_ROOT."/../apps/bluetoothview/devices.txt";

 $first_run=1;
 while(1) {
  @unlink($devices_file);
  echo "Running bluetoothview\n";
  exec(DOC_ROOT.'/../apps/bluetoothview/bluetoothview.exe /stab '.$devices_file);
  sleep(30);
  $last_scan=time();
  if (file_exists($devices_file)) {
   $data=(LoadFile($devices_file));
   $data=str_replace(chr(0), '', $data);
   $data=str_replace("\r", '', $data);
   $lines=explode("\n", $data);
   $total=count($lines);
   for($i=0;$i<$total;$i++) {
    $fields=explode("\t", $lines[$i]);
    $title=trim($fields[1]);
    $mac=trim($fields[2]);
    $user=array();
    if ($mac!='') {
     if (!$bt_devices[$mac]) { // && !$first_run
      //new device found
      echo date('Y/m/d H:i:s')." Device found: $mac\n";
      $rec=SQLSelectOne("SELECT * FROM btdevices WHERE MAC LIKE '".$mac."'");
      $rec['LAST_FOUND']=date('Y/m/d H:i:s');
      $rec['LOG']='Device found '.date('Y/m/d H:i:s')."\n".$rec['LOG'];
     
      if (!$rec['ID']) {
       $rec['FIRST_FOUND']=$rec['LAST_FOUND'];
       $rec['MAC']=strtolower($mac);
       if ($title!='') {
        $rec['TITLE']='Устройство: '.$title;
       } else {
        $rec['TITLE']='Новое устройство';
       }
       $new=1;
       SQLInsert('btdevices', $rec);
      } else {
       $new=0;
       if ($rec['USER_ID']) {
        $user=SQLSelectOne("SELECT * FROM users WHERE ID='".$rec['USER_ID']."'");
       }
       SQLUpdate('btdevices', $rec);
      }
      getObject('BlueDev')->raiseEvent("Found", array('mac'=>$mac, 'user'=>$user['NAME'],'new'=>$new));
     } else {
      $rec=SQLSelectOne("SELECT * FROM btdevices WHERE MAC LIKE '".$mac."'");
      $rec['LAST_FOUND']=date('Y/m/d H:i:s');
      if ($title!='') { // && $rec['TITLE']=='Новое устройство'
        $rec['TITLE']='Устройство: '.$title;
      }
      if ($rec['ID']) {
       SQLUpdate('btdevices', $rec);
      }
     }
     $bt_devices[$mac]=$last_scan;
    }
   }

   foreach($bt_devices as $k=>$v) {
    if ($v!=$last_scan) {
     //device removed
     echo date('Y/m/d H:i:s')." Device gone: $k\n";
     $user=array();
     $rec=SQLSelectOne("SELECT * FROM btdevices WHERE MAC LIKE '".$k."'");
     if ($rec['ID']) {
      $rec['LOG']='Device lost '.date('Y/m/d H:i:s')."\n".$rec['LOG'];
      SQLUpdate('btdevices', $rec);
       if ($rec['USER_ID']) {
        $user=SQLSelectOne("SELECT * FROM users WHERE ID='".$rec['USER_ID']."'");
       }
     }
     getObject('BlueDev')->raiseEvent("Lost", array('mac'=>$k, 'user'=>$user['NAME']));

     unset($bt_devices[$k]);
    }
   }

  } else {
   sleep(30);
  }
  $first_run=0;
 }

 $db->Disconnect(); // closing database connection

?>