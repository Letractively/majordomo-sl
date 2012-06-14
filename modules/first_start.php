<?

 global $mode;

 $languages=array();

 $languages[]=array('TITLE'=>'en');
 $languages[]=array('TITLE'=>'ru');

 $out['LANGUAGES']=$languages;

 if ($mode=='update') {

  global $language;

  if (!$language) {
   $language='en';
  }

  $settings=array(
   array(
    'NAME'=>'SITE_LANGUAGE', 
    'TITLE'=>'Language', 
    'TYPE'=>'text',
    'DEFAULT'=>'en'
    )
   );


   foreach($settings as $k=>$v) {
    $rec=SQLSelectOne("SELECT ID FROM settings WHERE NAME='".$v['NAME']."'");
    if (!$rec['ID']) {
     $rec['NAME']=$v['NAME'];
     $rec['VALUE']=$language;
     $rec['DEFAULTVALUE']=$v['DEFAULT'];
     $rec['TITLE']=$v['TITLE'];
     $rec['TYPE']=$v['TYPE'];
     $rec['ID']=SQLInsert('settings', $rec);
     Define('SETTINGS_'.$rec['NAME'], $v['DEFAULT']);
    }
   }

   $this->redirect("/");
  
 }

?>