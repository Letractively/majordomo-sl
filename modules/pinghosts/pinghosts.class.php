<?
/**
* Pinghosts 
*
* Pinghosts
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 0.2 (wizard, 00:01:48 [Jan 06, 2011])
*/
Define('DEF_TYPE_OPTIONS', '0=PING (HOST)|1=WEB PAGE (URL)'); // options for 'HOST TYPE'
//
//
class pinghosts extends module {
/**
* pinghosts
*
* Module class constructor
*
* @access private
*/
function pinghosts() {
  $this->name="pinghosts";
  $this->title="Pinghosts";
  $this->module_category="CMS";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();

  if ($this->mobile) {
   $out['MOBILE']=1;
  }

  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  if ($this->single_rec) {
   $out['SINGLE_REC']=1;
  }
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='pinghosts' || $this->data_source=='') {
  if ($this->view_mode=='' || $this->view_mode=='search_pinghosts') {
   $this->search_pinghosts($out);
  }
  if ($this->view_mode=='edit_pinghosts') {
   $this->edit_pinghosts($out, $this->id);
  }
  if ($this->view_mode=='delete_pinghosts') {
   $this->delete_pinghosts($this->id);
   $this->redirect("?");
  }
 }
}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
/**
* pinghosts search
*
* @access public
*/
 function search_pinghosts(&$out) {
  require(DIR_MODULES.$this->name.'/pinghosts_search.inc.php');
 }
/**
* pinghosts edit/add
*
* @access public
*/
 function edit_pinghosts(&$out, $id) {
  require(DIR_MODULES.$this->name.'/pinghosts_edit.inc.php');
 }
/**
* pinghosts delete record
*
* @access public
*/
 function delete_pinghosts($id) {
  $rec=SQLSelectOne("SELECT * FROM pinghosts WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM pinghosts WHERE ID='".$rec['ID']."'");
 }

/**
* Title
*
* Description
*
* @access public
*/
 function checkAllHosts() {

  // ping hosts
  $pings=SQLSelect("SELECT * FROM pinghosts WHERE CHECK_NEXT<=NOW()");
  $total=count($pings);
  for($i=0;$i<$total;$i++) {
   $host=$pings[$i];
   echo "Checking ".$host['HOSTNAME']."\n";
   $online_interval=$host['ONLINE_INTERVAL'];
   if (!$online_interval) {
    $online_interval=60;
   }
   $offline_interval=$host['OFFLINE_INTERVAL'];
   if (!$offline_interval) {
    $offline_interval=$online_interval;
   }

   if ($host['STATUS']=='1') {
    $host['CHECK_NEXT']=date('Y-m-d H:i:s', time()+$online_interval);
   } else {
    $host['CHECK_NEXT']=date('Y-m-d H:i:s', time()+$offline_interval);
   }
   SQLUpdate('pinghosts', $host);

   $online=0;
   // checking
   if (!$host['TYPE']) {
    //ping host
    $online=ping($host['HOSTNAME']);
   } else {
    //web host
    $online=file_get_contents($host['HOSTNAME']);
    SaveFile("./cached/host_".$host['ID'].'.html', $online);
    if ($host['SEARCH_WORD']!='' && !is_integer(strpos($online, $host['SEARCH_WORD']))) {
     $online=0;
    }
    if ($online) {
     $online=1;
    }
   }

   $old_status=$host['STATUS'];
   if ($online) {
    $new_status=1;
   } else {
    $new_status=2;
   }

   $host['CHECK_LATEST']=date('Y-m-d H:i:s');
   $host['STATUS']=$new_status;

   if ($host['STATUS']=='1') {
    $host['CHECK_NEXT']=date('Y-m-d H:i:s', time()+$online_interval);
   } else {
    $host['CHECK_NEXT']=date('Y-m-d H:i:s', time()+$offline_interval);
   }

   if ($old_status!=$new_status) {
    if ($new_status==2) {
     $host['LOG']=date('Y-m-d H:i:s').' Host is offline'."\n".$host['LOG'];
    } elseif ($new_status==1) {
     $host['LOG']=date('Y-m-d H:i:s').' Host is online'."\n".$host['LOG'];
    }
   }

   SQLUpdate('pinghosts', $host);

   if ($old_status!=$new_status && $old_status!=0) {
    // do some status change actions
    $run_script_id=0;
    $run_code='';
    if ($old_status==2 && $new_status==1) {
     // got online
     if ($host['SCRIPT_ID_ONLINE']) {
      $run_script_id=$host['SCRIPT_ID_ONLINE'];
     } elseif ($host['CODE_ONLINE']) {
      $run_code=$host['CODE_ONLINE'];
     }
    } elseif ($old_status==1 && $new_status==2) {
     // got offline
     if ($host['SCRIPT_ID_OFFLINE']) {
      $run_script_id=$host['SCRIPT_ID_OFFLINE'];
     } elseif ($host['CODE_OFFLINE']) {
      $run_code=$host['CODE_OFFLINE'];
     }
    }

    if ($run_script_id) {
     //run script
     runScript($run_script_id);
    } elseif ($run_code) {
     //run code
     eval($run_code);
    }

   }
   

  } 


 }

/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
  SQLExec('DROP TABLE IF EXISTS pinghosts');
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall() {
/*
pinghosts - Pinghosts
*/
  $data = <<<EOD
 pinghosts: ID int(10) unsigned NOT NULL auto_increment
 pinghosts: TITLE varchar(255) NOT NULL DEFAULT ''
 pinghosts: HOSTNAME varchar(255) NOT NULL DEFAULT ''
 pinghosts: TYPE int(30) NOT NULL DEFAULT '0'
 pinghosts: STATUS int(3) NOT NULL DEFAULT '0'
 pinghosts: SEARCH_WORD varchar(255) NOT NULL DEFAULT ''
 pinghosts: CHECK_LATEST datetime
 pinghosts: CHECK_NEXT datetime
 pinghosts: SCRIPT_ID_ONLINE int(10) NOT NULL DEFAULT '0'
 pinghosts: CODE_ONLINE text
 pinghosts: SCRIPT_ID_OFFLINE int(10) NOT NULL DEFAULT '0'
 pinghosts: CODE_OFFLINE text
 pinghosts: OFFLINE_INTERVAL int(10) NOT NULL DEFAULT '0'
 pinghosts: ONLINE_INTERVAL int(10) NOT NULL DEFAULT '0'
 pinghosts: LOG text
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgSmFuIDA2LCAyMDExIHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
?>