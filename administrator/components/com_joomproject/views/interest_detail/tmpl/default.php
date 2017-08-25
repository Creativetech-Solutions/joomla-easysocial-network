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
    function addInt()
    {

              var newdiv = document.createElement('div');
              var divIdName="int"+counter;
			newdiv.setAttribute('id',divIdName);
              newdiv.innerHTML = '<div class="int'+counter+'">'+
						'<div class="inputdiv">'+
							'<input type="text" name="intitem[]" id="intitem[]" >'+
							'<input type="hidden" name="intitemid[]" id="intitemid[]" value="0" >'+
						'</div>'+
						'<div class="inputdiv">'+
							'<input type="button" id="deleteint"  value="<?php echo JText::_( "DELETE" ); ?>"  onclick="removeInt(\'int'+counter+'\');">'+
						'</div>'+
					'</div>';

              document.getElementById('interest').appendChild(newdiv);

              counter++;
    }
   
	function removeInt(divNum) 
	{
	  var d = document.getElementById('interest');

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
				<label for="int">
					<?php echo JText::_( 'INTEREST_TITLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="interest title" type="text" name="int_title" id="int_title" value="<?php echo $this->detail->int_title;?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<label for="int">
					<?php echo JText::_( 'INTEREST_DESCRIPTION' ); ?>:
				</label>
			</td>
		<td>
				<?php echo $editor->display("int_desc",$this->detail->int_desc,'100px','20px','100','20','0');	?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="int">
					<?php echo JText::_( 'PUBLISHED' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['published']; ?> 
			</td>
		</tr>
		<tr>
			<td >
				<input type="button" name="addint" id="addint" value="<?php echo JText::_( 'INTEREST_ADDINTEREST' ); ?>" onClick="addInt();">
			</td>
		</tr>
		<tr>
			<td>
				<label for="int">
					<?php echo JText::_( 'INTEREST' ); ?>:
				</label>
			</td>
			<td >
				
				<div class="interestdiv" id="interest">
				<?php 
					for($i=0;$i<count($this->intlist);$i++)
					{
				?>
				
				<div id="int<?php echo $i; ?>">	
					<div class="int<?php echo $i; ?>">
						<div class="inputdiv">
							<input type="text" name="intitem[]" id="intitem[]" value="<?php echo $this->intlist[$i]->int_name; ?>" />
							<input type="hidden" name="intitemid[]" id="intitemid[]" value="<?php echo $this->intlist[$i]->id; ?>" />
						</div>
						<div class="inputdiv">
							<input type="button" name="deleteint" id="deleteint" value="<?php echo JText::_( 'DELETE' ); ?>"  onclick="intdelete('int<?php echo $i; ?>',<?php echo $this->intlist[$i]->id; ?>);"/>
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
<input type="hidden" name="view" value="interest_detail" />
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

	
function intdelete(divNum,intid)

{





	xmlHttp = GetXmlHttpObject();

	xmlHttp.onreadystatechange =function() {		

		if (xmlHttp.readyState == 4) {			

			removeInt(divNum);
			
		}

	}	

	//var jelurl=document.getElementById("jelive_url").value;

	

	var url = "index.php?option=<?php echo $option;?>&view=interest_detail&task=deleteint&intid="+intid;

	xmlHttp.open("GET", url, false)

	xmlHttp.send(null);			

}

</script>


