<?php
/**
* @package   JE shop
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
			alert("<?php echo JText::_( 'PLEASE_ENTER_SKILL_TITLE' ); ?>");
			return false;
		}
		 
			submitform( pressbutton );
			
	}
</script>

<script type="text/javascript">
	var counter = 0;
    function addSkill()
    {

              var newdiv = document.createElement('div');
              var divIdName="skill"+counter;
			newdiv.setAttribute('id',divIdName);
              newdiv.innerHTML = '<div class="skill'+counter+'">'+
						'<div class="inputdiv">'+
							'<input type="text" name="skillitem[]" id="skillitem[]" >'+
							'<input type="hidden" name="skillitemid[]" id="skillitemid[]" value="0" >'+
						'</div>'+
						'<div class="inputdiv">'+
							'<input type="button" id="deleteskill"  value="<?php echo JText::_( "DELETE" ); ?>"  onclick="removeSkill(\'skill'+counter+'\');">'+
						'</div>'+
					'</div>';

              document.getElementById('skills').appendChild(newdiv);

              counter++;
    }
   
	function removeSkill(divNum) 
	{
	  var d = document.getElementById('skills');

	  var olddiv = document.getElementById(divNum);

	  d.removeChild(olddiv);

	}
</script>




<form action="<?php echo JRoute::_('index.php?option='.$option); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<div class="col50">
	<fieldset class="adminForm">
		<table class="admintable" border="0">
		<tr>
			<td>
				<label for="skill">
					<?php echo JText::_( 'SKILL_TITLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="skills title" type="text" name="skill_title" id="skill_title" value="<?php echo $this->detail->skill_title;?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<label for="skill">
					<?php echo JText::_( 'SKILLS_DESCRIPTION' ); ?>:
				</label>
			</td>
		<td>
				<?php echo $editor->display("skill_desc",$this->detail->skill_desc,'100px','20px','100','20','0');	?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="skill">
					<?php echo JText::_( 'SKILLS_PUBLISHED' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['published']; ?> 
			</td>
		</tr>
		<tr>
			<td >
				<input type="button" name="addskill" id="addskill" value="<?php echo JText::_( 'SKILLS_ADDSKILL' ); ?>" onClick="addSkill();">
			</td>
		</tr>
		<tr>
			<td>
				<label for="skill">
					<?php echo JText::_( 'SKILLS' ); ?>:
				</label>
			</td>
			<td >
				
				<div class="skillsdiv" id="skills">
				<?php 
					for($i=0;$i<count($this->skilllist);$i++)
					{
				?>
				
				<div id="skill<?php echo $i; ?>">	
					<div class="skill<?php echo $i; ?>">
						<div class="inputdiv">
							<input type="text" name="skillitem[]" id="skillitem[]" value="<?php echo $this->skilllist[$i]->skill_name; ?>" />
							<input type="hidden" name="skillitemid[]" id="skillitemid[]" value="<?php echo $this->skilllist[$i]->id; ?>" />
						</div>
						<div class="inputdiv">
							<input type="button" name="deleteskill" id="deleteskill" value="<?php echo JText::_( 'DELETE' ); ?>"  onclick="skilldelete('skill<?php echo $i; ?>',<?php echo $this->skilllist[$i]->id; ?>);"/>
						</div>
					</div>
				
				</div>
				<script type="text/javascript"> counter++; </script>
				<?php } ?>
				</div>
				
			</td>
		</tr>
		
	</table>
	</fieldset>
</div>
<input type="hidden" name="id" value="<?php echo $this->detail->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="view" value="skills_detail" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="item" value="1" />
</form>


<script type="text/javascript">


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

	
function skilldelete(divNum,skid)

{





	xmlHttp = GetXmlHttpObject();

	xmlHttp.onreadystatechange =function() {		

		if (xmlHttp.readyState == 4) {			

			removeSkill(divNum);
			
		}

	}	

	//var jelurl=document.getElementById("jelive_url").value;

	

	var url = "index.php?option=<?php echo $option;?>&view=skills_detail&task=deleteskill&skid="+skid;

	xmlHttp.open("GET", url, false)

	xmlHttp.send(null);			

}

</script>


