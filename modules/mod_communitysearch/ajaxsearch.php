<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', "../../" );
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
$db = JFactory::getDbo();	

$aResults = array();

$count = 0;
$input = strtolower( $_GET['input'] );
$len = strlen($input);
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;
$no_text = $_GET['no_text'] ;

$display_json = new stdClass(); 
$json_arr = array();
$display_json->results = "";
$no_record=$no_text;
	
$query = "SELECT skill_name as value ,id from #__joomproject_skill_detail where skill_name like '%$input%' AND published=1 LIMIT 0,".$limit;
$db->setQuery($query);
$display_json->results =$db->loadObjectList();

if(count($display_json->results)){
	 print json_encode($display_json);exit;

}else{
	$json_arr["id"]="";
	$json_arr["value"]=$no_record;
	$display_json->results = $json_arr;
	$jsonWrite = json_encode($display_json); //encode that search data
	print $jsonWrite;	 exit;
}


	

?>