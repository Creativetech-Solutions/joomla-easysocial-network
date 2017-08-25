<?php
/**
* @package   JE Tour component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
//JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$uri    = JURI::getInstance();
$url    = $uri->root();
$editor = JFactory::getEditor();
$option = JRequest::getVar('option','','','string');  
$Itemid = JRequest::getVar ('Itemid','','request','int');
//$model = $this->getModel ('project');

$document   = JFactory::getDocument();
//$document->addStyleSheet('components/com_community/templates/jomsocial/assets/css/profile_edit.css');

$link = JRoute::_('index.php?option='.$option.'&view=project'); 
?>
 <!--<link href="<?php echo $url;?>components/com_joomproject/assets/crop/css/main.css" rel="stylesheet">
 <link href="<?php echo $url;?>components/com_joomproject/assets/crop/css/croppic.css" rel="stylesheet">-->
 <script type="text/javascript" src="<?php echo $url.'components/com_community/assets/select/jquery.tokenize.js';?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $url.'components/com_community/assets/select/jquery.tokenize.css';?>" />
	 <?php
	//$document->addScript($url."components/com_joomproject/assets/crop/js/jquery.js");
	//$document->addScript($url."components/com_joomproject/assets/crop/js/jquery.mousewheel.min.js");
	//$document->addScript($url."components/com_joomproject/assets/crop/js/main.js");
	//$document->addScript($url."components/com_joomproject/assets/crop/js/croppic.js");
?>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script>

$(function(){ // DOM ready
  $("#tags input").on({
    focusout : function() {
      var txt = this.value.replace(/[^a-z0-9\+\-\.\#]/ig,''); // allowed characters
      if(txt) $("<span/>", {text:txt.toLowerCase(), insertBefore:this});
      //this.value = "";
      document.getElementById('project_tags').value='';
     
      document.getElementById('projecttags').value=document.getElementById('projecttags').value+txt+",";
       document.getElementById('projecttags').value=document.getElementById('projecttags').value.replace(',,',',');
    },
    keyup : function(ev) {
      // if: comma|enter (delimit more keyCodes with | pipe)
      if(/(188|13)/.test(ev.which)) $(this).focusout(); 
    }
  });
  $('#tags').on('click', 'span', function() {
    if(confirm("Remove "+ $(this).text() +"?")) { 
    	var g=$(this).text();
   	var k=document.getElementById('projecttags').value=document.getElementById('projecttags').value.replace(g+',','');
    	if(k==',')
    		document.getElementById('projecttags').value='';
    	$(this).remove();
    }
  });

});
</script>   
 
<script>
var projectname=null;
var max=0;
function close_box()
{
 	// window.parent.location.href='<?php echo $link; ?>';
	 window.parent.SqueezeBox.close(); 
}
 
function readURL(input) 
{
   if (input.files && input.files[0]) 
   {
     	 document.getElementById("projectname").innerHTML=input.files[0]['name'];
	document.getElementById("uploadtextid").innerHTML='';
	document.getElementById("up").className='uploadidvfile smallfont';
	projectname=input.files[0]['name'];
	
   }

}
function maxsize(input) 
{
   if (input.files && input.files[0]) 
   {
     	 if(input.files[0]['size']>30000000)
     	 {
		alert("<?php echo JText::_('COM_COMMUNITY_PROJECT_FILE_SIZE_MUST_LESS_THEN_30MB'); ?>");
		max=1;
	 	return false;
	 }
	 else
	 {
	 	max=0;
	 	return true;
	 }
   }

}
function maxsizesub() 
{
	
	var title= document.getElementById("project_title").value;
	var gener= document.getElementById("project_gener").value;
  //	var imgcrop=document.getElementById("croppedImg");
	//var imgcrop=document.getElementById("cropContainerHeaderButton").style.display;
	
	if(projectname=='' || projectname==null)
	{
		alert("<?php echo JText::_('COM_COMMUNITY_PROJECT__MUST_SELECT_PROJECT_FILE'); ?>");
		return false;
	}
	else if(max==1)
	{
		alert("<?php echo JText::_('COM_COMMUNITY_PROJECT_FILE_SIZE_MUST_LESS_THEN_30MB'); ?>");
		return false;
	}
	else if(title=='' || title==null)
	{
		alert("<?php echo JText::_('COM_COMMUNITY_PROJECT_PROJECT_TITLE_MUST_REQUIRED'); ?>");
		return false;
	
	}
	else if(gener=='' || gener==null)
	{
		alert("<?php echo JText::_('COM_COMMUNITY_PROJECT_PROJECT_GENER_MUST_REQUIRED'); ?>");
		return false;
	}
	/*else if(imgcrop=='none')
	{
		alert("<?php echo JText::_('COM_COMMUNITY_PROJECT_MUST_UPLOAD_PROJECT_IMAGE_AND_CROP_IT'); ?>");
		return false;
	}*/	
	return true;
}
</script>
	 
<style>
	.component-body
	{
	    padding: 0px !important;
	}
	#tags{
	  float:left;
	  border:1px solid #ccc;
	  padding:5px;
	  font-family:Arial;
	}
	#tags > span{
	  cursor:pointer;
	  display:block;
	  float:left;
	  color:#fff;
	  background:#789;
	  padding:5px;
	  padding-right:25px;
	  margin:4px;
	}
	#tags > span:hover{
	  opacity:0.7;
	}
	#tags > span:after{
	 position:absolute;
	 content:"×";
	 border:1px solid;
	 padding:2px 5px;
	 margin-left:3px;
	 font-size:11px;
	}
	#tags > input{
	  background:#eee;
	  border:0;
	  margin:4px;
	  padding:7px;
	  width:auto;
	}
</style>
 
<form action="<?php //echo JRoute::_($this->request_url) ?>" method="post" name="projectForm" id="projectForm" enctype="multipart/form-data">

    <div class="col50 traininglisting">
        <h1 class="page_title"><?php echo JText::_( 'COM_COMMUNITY_NO_UPLOAD_PROJECT' ); ?></h1>

        <?php /*?><div class="projectportionleft" id="projectportionleft">
        <div class="col-lg-6 cropHeaderWrapper leftslectfield">
        <div id="savemsg"><i aria-hidden="true" class="fa fa-crop"></i><?php echo JText::_('COM_JOOMPROJECT_CLICK_CROP_BUTTON_TO_SAVE'); ?>.</div>
        	<div id="croppic"></div>
			<span class="btn" id="cropContainerHeaderButton"><?php echo JText::_('COM_JOOMPROJECT_UPLOAD_AN_IMAGE');?></span>
		</div>
        
        
    <!--       <img class="u_p_img_tag" src="" />

<span>        <?php echo JText::_( 'COM_COMMUNITY_UPLOAD_AN_IMAGE' ); ?><br />

<?php echo JText::_( 'COM_COMMUNITY_FOR_YOUR_PROJECT_ART' ); ?></span> <input type="file" id="project_image"  class="project_image"  name="project_image" accept="image/*" onChange="return profile_photo1(this);"/>
       -->
        </div><?php */?>
        
        <div class="rgihtdivfile">
        <ul>
       
        <li class="autofile"> 
			<span class="uploadidvfile" id="up">
				<span id="projectname" class="dispprojectname"></span>

				<span id="uploadtextid">
					<?php echo JText::_( 'COM_COMMUNITY_CHOOSE_AN_PROJECT_FILE' ); ?> 
				</span>
			</span>        
			<input type="file" id="project_file"  class="project_file" name="project_file" accept="project/*" onchange="maxsize(this);readURL(this); "/>
		</li>
        
        <li><label><?php echo JText::_( 'COM_COMMUNITY_PROJECT_TITLE' ); ?></label>
        <input type="text" id="project_title"  class="project_title" name="title" />   
        </li>
        <li><label><?php echo JText::_( 'COM_COMMUNITY_GENER' ); ?></label>
         <!--<input type="text" id="project_gener"  class="project_gener" name="project_gener" />   </li> -->
        <select id="project_gener"  class="project_gener" name="gener">
        		<option value=""><?php echo JText::_( 'COM_COMMUNITY_SELECT_GENER' ); ?> </option>
			<option style="color:#ff9100;" value="MUSIC" disabled><b>MUSIC</b></option>
			<option value="Alternative Rock">Alternative Rock</option>
			<option value="Ambient">Ambient</option>
			<option value="Classical">Classical</option>
			<option value="Country">Country</option>
			<option value="Dance & EDM">Dance & EDM</option>
			<option value="Dancehall">Dancehall</option>
			<option value="Disco">Disco</option>
			<option value="Drum & Bass">Drum & Bass</option>
			<option value="Dubstep">Dubstep</option>
			<option value="Electronic">Electronic</option>
			<option value="Folk & Singer-Songwriter">Folk & Singer-Songwriter</option>
			<option value="Hip-hop & Rap">Hip-hop & Rap</option>
			<option value="House">House</option>
			<option value="Indie">Indie</option>
			<option value="Jazz & Blues">Jazz & Blues</option>
			<option value="Latin">Latin</option>
			<option value="Metal">Metal</option>
			<option value="Piano">Piano</option>
			<option value="Pop">Pop</option>
			<option value="R&B & Soul">R&B & Soul</option>
			<option value="Reggae">Reggae</option>
			<option value="Reggaeton">Reggaeton</option>
			<option value="Rock">Rock</option>
			<option value="Soundtrack">Soundtrack</option>
			<option value="Techno">Techno</option>
			<option value="Trance">Trance</option>
			<option value="Trap">Trap</option>
			<option value="Triphop">Triphop</option>
			<option value="World">World</option>

			<option style="color:#ff9100;" value="PROJECT" disabled><b>PROJECT</b></option>
			<option value="Projectbooks">Projectbooks</option>
			<option value="Business">Business</option>
			<option value="Comedy">Comedy</option>
			<option value="Entertainment">Entertainment</option>
			<option value="Learning">Learning</option>
			<option value="News & Politics">News & Politics</option>
			<option value="Poetry">Poetry</option>
			<option value="Religion & Spirituality">Religion & Spirituality</option>
			<option value="Science">Science</option>
	</select>
	</li>
        <?php /*?><li><label><?php echo JText::_( 'COM_COMMUNITY_TAGES' ); ?></label>
        <div id="tags">
        	<input type="text" id="project_tags"  class="project_tags" name="tags" placeholder="Enter tags" />
        	<input type="hidden" id="projecttags" name="projecttags" value="">
        </div>
        </li><?php */?>
        <?php /*?><li><label><?php echo JText::_( 'COM_COMMUNITY_PRIVECY_SETTING' ); ?></label>
        
        <div class="bottmddivpchek"> 
            <span><input type="radio" name="project_privacy" class="privacy" checked="checked" value="10" /><b><?php echo JText::_( 'COM_COMMUNITY_PUBLIC' ); ?> </b><?php echo JText::_( 'COM_COMMUNITY_TRACHAPPEAR' ); ?></span>
            <span><input type="radio" name="project_privacy"  class="project_privacy" value="0"  /><b><?php echo JText::_( 'COM_COMMUNITY_PRIVATE' ); ?> </b><?php echo JText::_( 'COM_COMMUNITY_TRACH_VISIBLE' ); ?></span>
        </div>
        </li><?php */?>
        
        
                </ul>
        
        
        </div>
        
        
        
        

<div class="bottomlinsave">       
    <input type="button" value="CANCEL" name="cancel_btn" onclick="close_box();"/>
    <input type="submit" value="SUBMIT" name="sub" onclick="return maxsizesub();"/>   
</div>   

<div class="clr"></div>

 </div>
  <input type="hidden" name="task" value="project_save" />
   <input type="hidden" name="view" value="project" />
   <input type="hidden" name="controller" value="project">
   <input type="hidden" name="option" value="com_easysocial">
   <input type="hidden" name="live_url" id="live_url" value="<?php echo $url;?>" />
   <?php echo JHtml::_('form.token'); ?>
</form>



<script type="text/javascript">


/*
function profile_photo1(aaa)
{
    var ext = jQuery('#image').val().split('.').pop().toLowerCase();
    if(jQuery.inArray(ext, ['gif','png','bmp','jpg','jpeg']) == -1) {
        jQuery('#project_image').val("");
        alert("<?php echo JText::_('INVALID_FILE_EXTENSION'); ?>");
    }
    else
    {
        jQuery('.u_p_img_tag')[0].src="#";
        jQuery('.u_p_img_tag')[0].src = window.URL.createObjectURL(aaa.files[0]);
        jQuery('.u_p_img_tag')[0].style.display='block';
        jQuery('#projectportionleft').addClass('myextraclass')
        //jQuery('#disp_image').show();
        //jQuery('#up_img_div').hide();
        //jQuery('#image_disp')[0].src="#";
        //jQuery('#image_disp')[0].src = window.URL.createObjectURL(aaa.files[0]);
    }
    return true; 
}
*/
function profile_photo1(aaa)
{
	var ext = jQuery('#image').val().split('.').pop().toLowerCase();
	if(jQuery.inArray(ext, ['gif','png','bmp','jpg','jpeg']) == -1) {
		jQuery('#image').val("");
		alert("<?php echo JText::_('COM_JOOMPROJECT_INVALID_FILE_EXTENSION'); ?>");
	}
	else
	{
		jQuery('.u_p_img_tag')[0].src="#";
		jQuery('.u_p_img_tag')[0].src = window.URL.createObjectURL(aaa.files[0]);
		jQuery('#u_p_img_tag')[0].style.display='block';
	}
	return true; 
}
</script>
<!--
<script>
		 
	   
		var url = document.getElementById('live_url').value;
		 
		var croppicHeaderOptions = {
				//uploadUrl:'img_save_to_file.php',
				cropData:{
					"dummyData":1,
					"dummyData2":"asdas"
				},
				cropUrl: url+'index.php?option=com_community&view=projects&task=img_crop_to_file',
				customUploadButtonId:'cropContainerHeaderButton',
				modal:false,
				processInline:true,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}	
		var croppic = new Croppic('croppic', croppicHeaderOptions);
		
		
		var croppicContainerModalOptions = {
				
				uploadUrl:url+'index.php?option=com_community&view=projects&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_community&view=projects&task=img_crop_to_file',
				modal:true,
				imgEyecandyOpacity:0.4,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		var cropContainerModal = new Croppic('cropContainerModal', croppicContainerModalOptions);
		
		
		var croppicContaineroutputOptions = {
		
				uploadUrl:url+'index.php?option=com_community&view=projects&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_community&view=projects&task=img_crop_to_file', 
				outputUrlId:'cropOutput',
				modal:false,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		
		var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
		
		var croppicContainerEyecandyOptions = {
			
				uploadUrl:url+'index.php?option=com_community&view=projects&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_community&view=projects&task=img_crop_to_file',
				imgEyecandy:false,				
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		
		var cropContainerEyecandy = new Croppic('cropContainerEyecandy', croppicContainerEyecandyOptions);
		
		var croppicContaineroutputMinimal = {

				uploadUrl:url+'index.php?option=com_community&view=projects&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_community&view=projects&task=img_crop_to_file', 
				modal:false,
				doubleZoomControls:false,
			    rotateControls: false,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		var cropContaineroutput = new Croppic('cropContainerMinimal', croppicContaineroutputMinimal);
		
		var croppicContainerPreloadOptions = {
				uploadUrl:url+'index.php?option=com_community&view=projects&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_community&view=projects&task=img_crop_to_file',
				loadPicture:url+'components/com_joomproject/assets/crop/img/night.jpg',
				enableMousescroll:true,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		var cropContainerPreload = new Croppic('cropContainerPreload', croppicContainerPreloadOptions);
		
		
	</script>
	-->
<script>
		 
	   
		var url = document.getElementById('live_url').value;
		 
		var croppicHeaderOptions = {
				//uploadUrl:'img_save_to_file.php',
				cropData:{
					"dummyData":1,
					"dummyData2":"asdas"
				},
				cropUrl: url+'index.php?option=com_joomproject&view=registration&task=img_crop_to_file',
				customUploadButtonId:'cropContainerHeaderButton',
				modal:false,
				processInline:true,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}	
		var croppic = new Croppic('croppic', croppicHeaderOptions);
		
		
		var croppicContainerModalOptions = {
				
				uploadUrl:url+'index.php?option=com_joomproject&view=registration&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_joomproject&view=registration&task=img_crop_to_file',
				modal:true,
				imgEyecandyOpacity:0.4,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		var cropContainerModal = new Croppic('cropContainerModal', croppicContainerModalOptions);
		
		
		var croppicContaineroutputOptions = {
		
				uploadUrl:url+'index.php?option=com_joomproject&view=registration&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_joomproject&view=registration&task=img_crop_to_file', 
				outputUrlId:'cropOutput',
				modal:false,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		
		var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
		
		var croppicContainerEyecandyOptions = {
			
				uploadUrl:url+'index.php?option=com_joomproject&view=registration&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_joomproject&view=registration&task=img_crop_to_file',
				imgEyecandy:false,				
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		
		var cropContainerEyecandy = new Croppic('cropContainerEyecandy', croppicContainerEyecandyOptions);
		
		var croppicContaineroutputMinimal = {

				uploadUrl:url+'index.php?option=com_joomproject&view=registration&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_joomproject&view=registration&task=img_crop_to_file', 
				modal:false,
				doubleZoomControls:false,
			    rotateControls: false,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		var cropContaineroutput = new Croppic('cropContainerMinimal', croppicContaineroutputMinimal);
		
		var croppicContainerPreloadOptions = {
				uploadUrl:url+'index.php?option=com_joomproject&view=registration&task=img_save_to_file',
				cropUrl:url+'index.php?option=com_joomproject&view=registration&task=img_crop_to_file',
				loadPicture:url+'components/com_joomproject/assets/crop/img/night.jpg',
				enableMousescroll:true,
				//loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onReset:function(){ console.log('onReset') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}
		var cropContainerPreload = new Croppic('cropContainerPreload', croppicContainerPreloadOptions);
		
		
	</script>



