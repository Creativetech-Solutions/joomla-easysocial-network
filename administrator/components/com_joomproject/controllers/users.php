<?php
/**
* @package   JE category component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined ('_JEXEC') or die ('Restricted access');
jimport( 'joomla.application.component.controller' );
 
class categoryController extends JControllerForm
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
	// ================================= Ordering Function =======================================================//

	public function saveOrderAjax()

	{

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));



		// Get the arrays from the Request

		$pks   = $this->input->post->get('cid', null, 'array');

		$order = $this->input->post->get('order', null, 'array');

		$originalOrder = explode(',', $this->input->getString('original_order_values'));



		// Make sure something has changed

		if (!($order === $originalOrder)) {

			// Get the model

			$model = $this->getModel();

			// Save the ordering

			$return = $model->saveorder($pks, $order);

			if ($return)

			{

				echo "1";

			}

		}

		// Close the application

		JFactory::getApplication()->close();



	}

	// ================================= End Ordering Function =======================================================//

	
 
}	

?>