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


/**
* Title
*
* Description
*
* @access public
*/
 function getGlobal($varname) {
  $tmp=explode('.', $varname);
  if ($tmp[1]) {
   $object_name=$tmp[0];
   $varname=$tmp[1];
  } else {
   $object_name='ThisComputer';
  }
  $obj=getObject($object_name);
  if ($obj) {
   return $obj->getProperty($varname);
  } else {
   return 0;
  }
 }

/**
* Title
*
* Description
*
* @access public
*/
 function setGlobal($varname, $value) {
  $tmp=explode('.', $varname);
  if ($tmp[1]) {
   $object_name=$tmp[0];
   $varname=$tmp[1];
  } else {
   $object_name='ThisComputer';
  }
  $obj=getObject($object_name);
  if ($obj) {
   return $obj->setProperty($varname, $value);
  } else {
   return 0;
  }
 }

/**
* Title
*
* Description
*
* @access public
*/
 function callMethod($method_name, $params=0) {
  $tmp=explode('.', $method_name);
  if ($tmp[1]) {
   $object_name=$tmp[0];
   $method_name=$tmp[1];
  } else {
   $object_name='ThisComputer';
  }
  $obj=getObject($object_name);
  if ($obj) {
   return $obj->callMethod($method_name, $params);
  } else {
   return 0;
  }
 
 }


// SHORT ALIAS *****************************

 function sg($varname, $value) {
  return setGlobal($varname, $value);
 }

 function gg($varname) {
  return getGlobal($varname, $value);
 }

 function cm($method_name, $params=0) {
  return callMethod($method_name, $params);
 }


?>