/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

akeeba.jQuery(document).ready(function($){
    akeeba.jQuery('#addTickets').click(function(){
		if(document.adminForm.boxchecked.value == 0)
		{
            alert(Joomla.JText._('COM_ATS_BUCKETS_CHOOSE_ONE'));
			return false;
		}

		if(document.adminForm.boxchecked.value > 1)
		{
			alert(Joomla.JText._('COM_ATS_BUCKETS_CHOOSE_ONLY_ONE'));
			return false;
		}

		Joomla.submitbutton('addtickets');
	});
});