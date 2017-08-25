<?php
 /**
* @package   Dowalo
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
* Visit : http://www.joomlaextensions.co.in/
**/ 

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url= $uri->root();
//$editor =JFactory::getEditor();

?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton=function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
	
			submitform( pressbutton );
		
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'COUNTRY_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country_name" id="country_name" size="32" maxlength="250" value="<?php echo $this->detail->country_name;?>" />
				<?php //echo JHTML::tooltip( JText::_( 'TITTLE' ), JText::_( 'TITTLE' ), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'COUNTRY_3_CODE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country_3_code" id="country_3_code" size="32" maxlength="250" value="<?php echo $this->detail->country_3_code;?>" />
				 	</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'COUNTRY_2_CODE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country_2_code" id="country_2_code" size="32" maxlength="250" value="<?php echo $this->detail->country_2_code;?>" />
				
			</td>
		</tr>
	
		
	</table>
	</fieldset>
</div>

 		
 
<div class="clr"></div>

<input type="hidden" name="cid[]" value="<?php echo $this->detail->country_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="country_detail" />
</form>