	<?php
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );
 
class stateController extends JControllerForm
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}	
	function cancel($key = NULL)
	{
		$this->setRedirect( 'index.php' );
	}
	function display($cachable = false, $urlparams = '') {
		
		parent::display();
	}
}	

