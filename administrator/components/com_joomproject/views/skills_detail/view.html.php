<?php
/**

* @package   JE shop

* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

**/ 

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.view' );

class skills_detailViewskills_detail extends JViewLegacy
{
	function display($tpl = null)
	{
		$document =  JFactory::getDocument();
		$document->setTitle( JText::_('SKILL_DETAIL') );
		$model = $this->getModel ( 'skills_detail' );
		
		
		
		$uri=JFactory::getURI();
		
		//$doc =& JFactory::getDocument();
		$option = JRequest::getVar('option','','request','string');
		
		$this->setLayout('default');

		$lists = array();

		$detail	=$this->get('data');
	/*	echo "<pre>";
		print_r($detail);
		exit;
	*/	
		$skilllist=$model->getskills($detail->id);
		
		$isNew		= ($detail->id < 1);

		$text = $isNew ? JText::_( 'SKILLS_NEW' ) : JText::_( 'SKILLS_EDIT' );
		JToolBarHelper::title(   JText::_( 'SKILL_DETAIL' ).': <small><small>[ ' . $text.' ]</small></small>' );
	
		//JToolBarHelper::apply(); 
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		 $options[] = JHtml::_('select.option', '0', JText::sprintf('No'));
		$options[] = JHtml::_('select.option', '1', JText::sprintf('Yes')); 

			$lists['published'] 	= JHTML::_('select.genericlist',$options,  'published', 'class="inputtext" ', 'value', 'text', $detail->published );
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('skilllist',		$skilllist);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri);

		parent::display($tpl);
	}
	
}
?>
