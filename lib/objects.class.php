<?
/*
* @version 0.1 (auto-set)
*/


/**
* Title
*
* Description
*
* @access public
*/
 function getObject($name) {
  $rec=SQLSelectOne("SELECT * FROM objects WHERE TITLE LIKE '".DBSafe($name)."'");
  if ($rec['ID']) {
   include_once(DIR_MODULES.'objects/objects.class.php');
   $obj=new objects();
   $obj->id=$rec['ID'];
   $obj->loadObject($rec['ID']);
   return $obj;
  }
  return 0;
 }

?>