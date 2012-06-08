<?
/**
* Scripts 
*
* Scripts
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 0.3 (wizard, 18:09:04 [Sep 13, 2010])
*/
//
//
class scripts extends module {
/**
* scripts
*
* Module class constructor
*
* @access private
*/
function scripts() {
  $this->name="scripts";
  $this->title="Scripts";
  $this->module_category="Settings";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams() {
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
* Title
*
* Description
*
* @access public
*/
 function runScript($id, $params='') {
  $rec=SQLSelectOne("SELECT * FROM scripts WHERE ID='".(int)$id."' OR TITLE LIKE '".DBSafe($id)."'");
  if ($rec['ID']) {
   eval($rec['CODE']);
  }
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
 if ($this->data_source=='scripts' || $this->data_source=='') {
  if ($this->view_mode=='' || $this->view_mode=='search_scripts') {
   $this->search_scripts($out);
  }

  if ($this->view_mode=='run_script') {
   $this->runScript($this->id);
   exit;
   //$this->redirect("?");
  }

  if ($this->view_mode=='edit_scripts') {
   $this->edit_scripts($out, $this->id);
  }
  if ($this->view_mode=='delete_scripts') {
   $this->delete_scripts($this->id);
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
* scripts search
*
* @access public
*/
 function search_scripts(&$out) {
  require(DIR_MODULES.$this->name.'/scripts_search.inc.php');
 }
/**
* scripts edit/add
*
* @access public
*/
 function edit_scripts(&$out, $id) {
  require(DIR_MODULES.$this->name.'/scripts_edit.inc.php');
 }
/**
* scripts delete record
*
* @access public
*/
 function delete_scripts($id) {
  $rec=SQLSelectOne("SELECT * FROM scripts WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM scripts WHERE ID='".$rec['ID']."'");
 }
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install() {
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
  SQLExec('DROP TABLE IF EXISTS scripts');
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
scripts - Scripts
*/
  $data = <<<EOD
 scripts: ID int(10) unsigned NOT NULL auto_increment
 scripts: TITLE varchar(255) NOT NULL DEFAULT ''
 scripts: CODE text

 safe_execs: ID int(10) unsigned NOT NULL auto_increment
 safe_execs: COMMAND text NOT NULL DEFAULT ''
 safe_execs: EXCLUSIVE int(3) NOT NULL DEFAULT 0
 safe_execs: ADDED datetime



EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgU2VwIDEzLCAyMDEwIHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
?>