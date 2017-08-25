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
	

	var xmlHttp
//******************************** Check Browser Compability******************
function GetXmlHttpObject()
{
	
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}

function select_state(did)
{
	xmlHttp = GetXmlHttpObject();
	xmlHttp.onreadystatechange =function() {		
		if (xmlHttp.readyState == 4) {			

			document.getElementById("state_div").innerHTML=xmlHttp.responseText;
		}
	}	
	var url = "index2.php?option=com_jepropertyfinder&view=section_detail&task=getState&did=" + did;
	xmlHttp.open("GET", url, true)
	xmlHttp.send(null);			
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
					<?php echo JText::_( 'STATE_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="state_name" id="state_name" size="32" maxlength="250" value="<?php echo $this->detail->state_name;?>" />
				
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'COUNTRY' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['country'];  ?>
				 
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'STATE_3_CODE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="state_3_code" id="state_3_code" size="32" maxlength="250" value="<?php echo $this->detail->state_3_code;?>" />
				 	</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'STATE_3_CODE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="state_2_code" id="state_2_code" size="32" maxlength="250" value="<?php echo $this->detail->state_2_code;?>" />
				
			</td>
		</tr>
		
	
				
	</table>
	</fieldset>
</div>


 
<div class="clr"></div>

<input type="hidden" name="cid[]" value="<?php echo $this->detail->state_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="state_detail" />
</form>