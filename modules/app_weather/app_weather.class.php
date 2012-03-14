<?
/**
* Weather 
*
* Weather
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 0.2 (wizard, 17:03:20 [Mar 04, 2010])
*/
//
//
class app_weather extends module {
/**
* weather
*
* Module class constructor
*
* @access private
*/
function app_weather() {
  $this->name="app_weather";
  $this->title="Weather";
  $this->module_category="Applications";
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
 $this->getConfig();

 if ($this->mode=='update') {
  global $location;
  global $metric;
  $this->config['LOCATION']=$location;
  $this->config['METRIC']=$metric;
  $this->saveConfig();
  $this->redirect("?");
 }

 if ($this->mode=='refresh') {
  $this->updateFile();
 }

 $out['LOCATION']=$this->config['LOCATION'];
 $out['METRIC']=$this->config['METRIC'];


 $this->usual($out);

}

/**
* Title
*
* Description
*
* @access public
*/
 function updateFile() {
  $this->getConfig();
  $url = 'http://weather.msn.com/RSS.aspx?wealocations=wc:'.$this->config['LOCATION'].'&weadegreetype=C';
  $ch = curl_init();
  $timeout = 0;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $file_contents = curl_exec($ch);
  SaveFile(ROOT.'cms/weather/update.xml', $file_contents);
  curl_close($ch);
 }
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 if (!file_exists(ROOT.'cms/weather/update.xml')) {
  $this->updateFile();
 }

 $mtime=filemtime(ROOT.'cms/weather/update.xml');
 if ((time()-$mtime)>30*60) {
  $this->updateFile();
 }

 $data=LoadFile(ROOT.'cms/weather/update.xml');
 $out['RESULT']=htmlspecialchars($data);

 $xml=XMLTreeToArray(GetXMLTree($data));

 //print_r($xml);exit;
 
 $out['CURRENT']=$xml['rss']['channel']['item'][0]['description']['textvalue'];
 $out['CURRENT']=preg_replace('/all times.+/is','',$out['CURRENT']);
 $out['CURRENT']=preg_replace('/<strong.+<\/strong><br \/>/is','',$out['CURRENT']);
 $out['CURRENT']=str_replace('Winds:','Ветер:',$out['CURRENT']);
 $out['CURRENT']=str_replace('Humidity:','Влажность:',$out['CURRENT']);
 $out['CURRENT']=str_replace('Feels like','ощущается как',$out['CURRENT']);
 $out['CURRENT']=str_replace('<img ','<img style="float:left;padding-right:5px" ',$out['CURRENT']);
 
 
 $out['FORECAST']=$xml['rss']['channel']['item'][1]['description']['textvalue'];
 $out['FORECAST']=preg_replace('/chance of.+?%/is','',$out['FORECAST']);
 $out['FORECAST']=preg_replace('/<\/p><p.+/is','</p>',$out['FORECAST']);
 $out['FORECAST']=preg_replace('/<a href.+?>/','',$out['FORECAST']);
 $out['FORECAST']=preg_replace('/<\/a>/','',$out['FORECAST']);
 
 $out['FORECAST']=str_replace('Lo:','Мин:',$out['FORECAST']);
 $out['FORECAST']=str_replace('Hi:','Макс:',$out['FORECAST']);
 
 
 $out['FORECAST']=str_replace('Today','Сегодня',$out['FORECAST']);
 $out['FORECAST']=str_replace('Tomorrow','Завтра',$out['FORECAST']);
 $out['FORECAST']=str_replace('Monday','Понедельник',$out['FORECAST']);
 $out['FORECAST']=str_replace('Tuesday','Вторник',$out['FORECAST']);
 $out['FORECAST']=str_replace('Wednesday','Среда',$out['FORECAST']);
 $out['FORECAST']=str_replace('Thursday','Четверг',$out['FORECAST']);
 $out['FORECAST']=str_replace('Friday','Пятница',$out['FORECAST']);
 $out['FORECAST']=str_replace('Saturday','Суббота',$out['FORECAST']);
 $out['FORECAST']=str_replace('Sunday','Воскресенье',$out['FORECAST']);

 if (!$out['CURRENT']) {
  $out['ERROR']=1;
 }
 
 /*
 if ($xml['adc_database']) {

 $out['WEATHER_ICON']=$xml['adc_database']['currentconditions']['weathericon']['textvalue'];
 $out['TEMPERATURE']=$xml['adc_database']['currentconditions']['temperature']['textvalue'];

 $forecast=$xml['adc_database']['forecast']['day'];
 foreach($forecast as $day) {
  $rec=array();
  $rec['TITLE']=$day['daycode']['textvalue'];
  $rec['DAYTIME_WEATHER_ICON']=$day['daytime']['weathericon']['textvalue'];
  $rec['DAYTIME_TEMPERATURE']=$day['daytime']['hightemperature']['textvalue'];

  $rec['NIGHTTIME_WEATHER_ICON']=$day['nighttime']['weathericon']['textvalue'];
  $rec['NIGHTTIME_TEMPERATURE']=$day['nighttime']['lowtemperature']['textvalue'];


  $out['DAYS'][]=$rec;


 }

 } else {
  $out['ERROR']=1;
 }*/

 //

}
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install() {
  if (!Is_Dir(ROOT."./cms/weather")) {
   mkdir(ROOT."./cms/weather", 0777);
  }
  parent::install();
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgTWFyIDA0LCAyMDEwIHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
?>