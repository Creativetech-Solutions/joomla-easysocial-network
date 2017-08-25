<?php
/**
* @package  dowalo
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/ 
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$uri =JURI::getInstance();
$url= $uri->root();
$cid = JRequest::getVar ( 'cid', array (0 ), '', 'array' );
$editor = JFactory::getEditor();
$img_src = JRoute::_('components/com_jeshop/assets/images/');
$option=JRequest::getVar ( 'option','', 'request', 'string' );
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton =function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}else if(form.title.value==""){
			alert("<?php echo JText::_( 'PLEASE_ENTER_CATEGORY_NAME' ); ?>");
			return false;
		}
		 
			submitform( pressbutton );
			
	}
</script>

<form action="<?php echo JRoute::_('index.php?option='.$option); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<div class="col50">
	<fieldset class="adminForm">
		<table class="admintable" border="0">
		<tr>
			<td>
				<label for="category">
					<?php echo JText::_( 'CATEGORY_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="skills title" type="text" name="cname" id="cname" value="<?php echo $this->detail->cname;?>"/>
			</td>
		</tr>
	
		<tr>
			<td>
				<label for="category">
					<?php echo JText::_( 'PUBLISHED' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['published']; ?> 
			</td>
		</tr>
	
	
		
	</table>
	</fieldset>
</div>
<input type="hidden" name="id" value="<?php echo $this->detail->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="view" value="category_detail" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />

</form>




