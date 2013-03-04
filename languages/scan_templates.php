<?
/*
* This script is just to make translation process easier :)
*/

 chdir('../');

 include_once("./config.php");
 include_once("./lib/loader.php");

 $lang_file=LoadFile('./languages/default.php');

 $lang_data=array();
 $replacements=array(
  'No records found.'=>'<#LANG_NO_RECORDS_FOUND#>',
  'No records found'=>'<#LANG_NO_RECORDS_FOUND#>',
  'Are you sure?'=>'<#LANG_ARE_YOU_SURE#>',
  '>Delete</a>'=>'><#LANG_DELETE#></a>',
  '<b>New Record</b>'=>'<b><#LANG_NEW_RECORD#></b>',
  '>New record<'=>'><#LANG_NEW_RECORD#><',
  'Please fill out all required fields'=>'<#LANG_FILLOUT_REQURED#>', 
  'Title:'=>'<#LANG_TITLE#>:',
  '>Cancel</a>'=>'><#LANG_CANCEL#></a>',
  '>Edit<'=>'><#LANG_EDIT#><',
  'Add new record'=>'><#LANG_ADD_NEW_RECORD#><',
  '>Search<'=>'><#LANG_SEARCH#><',
  'Data has been saved'=>'<#LANG_DATA_SAVED#>',
  '&lt; Back<'=>'&lt; <#LANG_BACK#><',

  'value="Add"'=>'value="<#LANG_ADD#>"',
  'value="Update"'=>'value="<#LANG_UPDATE#>"',
  'value="Submit"'=>'value="<#LANG_SUBMIT#>"',
  'value="Search"'=>'value="<#LANG_SEARCH#>"',
  '<b>Pages:</b>'=>'<b><#LANG_PAGES#>:</b>'
 );

 if (preg_match_all('/\'(\w+)\'=>\'(.+?)\',(.*?)/is', $lang_file, $m)) {
  $total=count($m[1]);
  for($i=0;$i<$total;$i++) {
   $lang_data[$m[1][$i]]=array('TEXT'=>$m[2][$i]);
  }
 }

 walk_dir('./templates', 'processTemplateFile');


 $res='<?'."\n\n".'$dictionary=array(';
 $last_section='';
 foreach($lang_data as $k=>$v) {
  $tmp=explode('_', $k);
  if ($tmp[1] && $tmp[0]!=$last_section) {
   $last_section=$tmp[0];
   $res.="\n\n";
  }
  $res.='\''.$k.'\'=>\''.$v['TEXT'].'\',';
  if ($v['SEEN_AT']) {
   $res.=" // ".$v['SEEN_AT'];
  }
  $res.="\n";
 }
 $res.="'TEST'=>'test'\n);\n\n?>";

// echo $res;
 SaveFile('./languages/_scanned.php', $res);


 /**
 * Title
 *
 * Description
 *
 * @access public
 */
  function processTemplateFile($filename) {
   global $lang_data;
   global $replacements;

   echo "Template file: ".$filename."\n";
   $content=LoadFile($filename);

   $replaced=0;
   foreach($replacements as $k=>$v) {
    if (is_integer(strpos($content, $k))) {
     $content=str_replace($k, $v, $content);
     $replaced++;
    }
   }
   if ($replaced) {
    SaveFile($filename, $content);
   }

   if (preg_match_all('/<#LANG\_(\w+?)#>/is', $content, $m)) {
    $total=count($m[1]);
    for($i=0;$i<$total;$i++) {
     $word=$m[1][$i];
     if (!$lang_data[$word]) {
      $lang_data[$word]['TEXT']=$word;
     }
     $lang_data[$word]['SEEN_AT']=$lang_data[$word]['SEEN_AT'].str_replace('./templates/', '', $filename).'; ';
    }
   }
  }

?>
