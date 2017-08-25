<?php

/**

* @package   JE category component

* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.

* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php

* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com

**/



defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.calendar');

JHTML::_('behavior.tooltip');

JHTMLBehavior::modal();

$uri = JURI::getInstance();

$url= $uri->root();

$editor = JFactory::getEditor();

$option = JRequest::getVar('option','','','string');

$filter = JRequest::getVar('filter');

 

?>



<script language="javascript" type="text/javascript">

	Joomla.submitform =function submitbutton(pressbutton) {

		var form = document.adminForm;

		if (pressbutton == 'cancel') {

			submitform( pressbutton );

			return;

		}

		else if(form.cname.value=="")

		{

			alert("<?php echo JText::_( 'JE_PLEASE_ENTER_CATEGORY_NAME' ); ?>");

			return false;

			submitform( pressbutton );

		}

		 

		submitform( pressbutton );	

	}
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
	 if ((pressbutton=='publish')||(pressbutton=='unpublish')||(pressbutton=='saveorder'))
	 {		 
	  form.view.value="category_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();

}
</script>



<form action="<?php //echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" 

enctype="multipart/form-data">

<div class="col50">

<legend><?php echo JText::_( 'CATEGORY_DETAIL' ); ?></legend>

	<table class="admintable" border="0">

		<tr>

			<td width="100" class="key">

				<label for="name">

					<?php echo JText::_( 'CATEGORY_NAME' ); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="cname" id="cname" size="32" maxlength="250"

                value="<?php echo $this->detail->cname;?>"/>

			</td>

		</tr>

	

	 

		 

        

		 

		<tr>

			<td width="100"   class="key" valign="top">

				<label for="name">

					<?php echo JText::_( 'PUBLISHED' ); ?>:

				</label>

			</td>

			<td>

				<?php echo $this->lists['published']; ?> 

			</td>

		</tr>

		 

		

		

	</table>

</div>

<div class="clr"></div>



<input type="hidden" name="cid[]" value="<?php echo $this->detail->id; ?>" />

<input type="hidden" name="task" value="" />

<input type="hidden" name="view" value="category_detail" />

</form>