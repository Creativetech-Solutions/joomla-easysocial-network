<?php
/**
* @package   JE Tour component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined ('_JEXEC') or die ('Restricted access');

	defined ('_JEXEC') or die ('Restricted access');

	//error_reporting(0); 
    $controller = JRequest::getVar('view','skills','request','string' );
  	$task = JRequest::getVar('task','','request','string' );


	if($controller=="about")
	{
		JToolBarHelper::title(   JText::_( 'About us' ) ); 
		require_once (JPATH_COMPONENT.'/'.'readme.html');
		
	}
	else 
	{
		require_once (JPATH_COMPONENT.'/'.'controller.php');
		//require_once (JPATH_COMPONENT.'/'.'helper/thumbnail.php');
		
    	require_once (JPATH_COMPONENT.'/'.'controllers'.'/'.$controller.'.php');
		$classname  = $controller.'controller';
    	$controller = new $classname( array('default_task' => 'display') );
    	$controller->execute( JRequest::getVar('task','','request','string'));
    	$controller->redirect();
    }
    
?>
