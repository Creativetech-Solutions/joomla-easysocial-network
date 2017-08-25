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
$audioid = JRequest::getVar('audioid','0','','int');
$model = $this->getModel ('audios');


$db = JFactory::getDBO();
$query = "Select * from #__community_audios where video_id=".$audioid;
$db->setQuery( $query );
$audiodata  = $db->loadObject();

$document   = JFactory::getDocument();
$document->addStyleSheet('components/com_community/templates/jomsocial/assets/css/profile_edit.css');

$link = JRoute::_('index.php?option='.$option.'&view=profile&page=audio'); 
?>
 <link href="<?php echo $url;?>components/com_joomproject/assets/crop/css/main.css" rel="stylesheet">
 <link href="<?php echo $url;?>components/com_joomproject/assets/crop/css/croppic.css" rel="stylesheet">
 <script type="text/javascript" src="<?php echo $url.'components/com_community/assets/select/jquery.tokenize.js';?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $url.'components/com_community/assets/select/jquery.tokenize.css';?>" />
   <?php
  //$document->addScript($url."components/com_joomproject/assets/crop/js/jquery.js");
  $document->addScript($url."components/com_joomproject/assets/crop/js/jquery.mousewheel.min.js");
  $document->addScript($url."components/com_joomproject/assets/crop/js/main.js");
  $document->addScript($url."components/com_joomproject/assets/crop/js/croppic.js");
?>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    
  $(function(){ // DOM ready
    $("#tags input").on({
      focusout : function() {
        var txt = this.value.replace(/[^a-z0-9\+\-\.\#]/ig,''); // allowed characters
        //var txt = 'asdfd';
        if(txt) $("<span/>", {text:txt.toLowerCase(), insertBefore:this});
        //this.value = "";
        document.getElementById('audio_tags').value='';
       
        document.getElementById('audiotags').value=document.getElementById('audiotags').value+txt+",";
        document.getElementById('audiotags').value=document.getElementById('audiotags').value.replace(',,',',');
      },

      keyup : function(ev) {
        // if: comma|enter (delimit more keyCodes with | pipe)
        if(/(188|13)/.test(ev.which)) $(this).focusout(); 
      }
    });
    $('#tags').on('click', 'span', function() {
      
      if(confirm("Remove "+ $(this).text() +"?")) 
      {
        $(this).remove(); 
     //   removetag($(this).text(),"<?php echo $audiodata->id;?>");
      
      var tag=$(this).text();
        var tags=document.getElementById('audiotags').value;
        tags=tags.replace(tag+',','')
       document.getElementById('audiotags').value=tags;
       
      
     // removetag($(this).text()
      
      }
    });

  });
</script>   
 
<script>
var audioname=null;
var max=0;
function close_box()
{
  // window.parent.location.href='<?php echo $link; ?>';
   window.parent.SqueezeBox.close(); 
}
 
function maxsizesub() 
{
  
  var title= document.getElementById("audio_title").value;
  var gener= document.getElementById("audio_gener").value;
  var audio= document.getElementById("oldAudio").value;
  //  var imgcrop=document.getElementById("croppedImg");
  var imgcrop=document.getElementById("cropContainerHeaderButton").style.display;
  
  if((audioname=='' && audio=='') || (audioname==null && audio==''))
  {
    alert("<?php echo JText::_('COM_COMMUNITY_AUDIO__MUST_SELECT_AUDIO_FILE'); ?>");
    return false;
  }
  else if(title=='' || title==null)
  {
    alert("<?php echo JText::_('COM_COMMUNITY_AUDIO_AUDIO_TITLE_MUST_REQUIRED'); ?>");
    return false;
  
  }
  else if(gener=='' || gener==null)
  {
    alert("<?php echo JText::_('COM_COMMUNITY_AUDIO_AUDIO_GENER_MUST_REQUIRED'); ?>");
    return false;
  }
  else if(imgcrop=='none')
	{
		alert("<?php echo JText::_('COM_COMMUNITY_AUDIO_MUST_UPLOAD_AUDIO_IMAGE_AND_CROP_IT'); ?>");
		return false;
	}	
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
 
<form action="<?php //echo JRoute::_($this->request_url) ?>" method="post" name="audioForm" id="audioForm" enctype="multipart/form-data">

    <div class="col50 traininglisting">
        <h1 class="page_title"><?php echo JText::_( 'COM_COMMUNITY_NO_UPLOAD_AUDIO' ); ?></h1>

        <div class="audioportionleft" id="audioportionleft">
          <div class="col-lg-6 cropHeaderWrapper leftslectfield">
          <div id="savemsg"><i aria-hidden="true" class="fa fa-crop"></i><?php echo JText::_('COM_JOOMPROJECT_CLICK_CROP_BUTTON_TO_SAVE'); ?>.</div>
            <div id="croppic">
              <?php 
                if($audiodata->thumb !='')
                {
              ?>
               <img src="<?php echo $url.'images/audios/'.$audiodata->creator.'/'.$audiodata->thumb;?>" />
              <?php
                }
              ?>
            </div>
        <span class="btn" id="cropContainerHeaderButton"><?php echo JText::_('COM_JOOMPROJECT_UPLOAD_AN_IMAGE');?></span>
      </div>
        </div>
        
        <div class="rgihtdivfile">
          <ul>
        
            <li>
              <label><?php echo JText::_( 'COM_COMMUNITY_AUDIO_TITLE' ); ?></label>
              <input type="text" id="audio_title"  class="audio_title" name="audio_title" value="<?php echo $audiodata->title;?>"/>   
            </li>
            <li>
              <label><?php echo JText::_( 'COM_COMMUNITY_GENER' ); ?></label>

              <select id="audio_gener"  class="audio_gener" name="audio_gener">
                <option value=""><?php echo JText::_( 'COM_COMMUNITY_SELECT_GENER' ); ?> </option>
            <option style="color:#ff9100;" value="MUSIC" disabled><b>MUSIC</b></option>
            <option value="Alternative Rock" <?php if($audiodata->gener=='Alternative Rock'){?> selected="selected" <?php }?>>Alternative Rock</option>
            <option value="Ambient" <?php if($audiodata->gener=='Ambient'){?> selected="selected" <?php }?>>Ambient</option>
            <option value="Classical" <?php if($audiodata->gener=='Classical'){?> selected="selected" <?php }?>>Classical</option>
            <option value="Country" <?php if($audiodata->gener=='Country'){?> selected="selected" <?php }?>>Country</option>
            <option value="Dance & EDM" <?php if($audiodata->gener=='Dance & EDM'){?> selected="selected" <?php }?>>Dance & EDM</option>
            <option value="Dancehall" <?php if($audiodata->gener=='Dancehall'){?> selected="selected" <?php }?>>Dancehall</option>
            <option value="Disco" <?php if($audiodata->gener=='Disco'){?> selected="selected" <?php }?>>Disco</option>
            <option value="Drum & Bass" <?php if($audiodata->gener=='Drum & Bass'){?> selected="selected" <?php }?>>Drum & Bass</option>
            <option value="Dubstep" <?php if($audiodata->gener=='Dubstep'){?> selected="selected" <?php }?>>Dubstep</option>
            <option value="Electronic" <?php if($audiodata->gener=='Electronic'){?> selected="selected" <?php }?>>Electronic</option>
            <option value="Folk & Singer-Songwriter" <?php if($audiodata->gener=='Folk & Singer-Songwriter'){?> selected="selected" <?php }?>>Folk & Singer-Songwriter</option>
            <option value="Hip-hop & Rap" <?php if($audiodata->gener=='Hip-hop & Rap'){?> selected="selected" <?php }?>>Hip-hop & Rap</option>
            <option value="House" <?php if($audiodata->gener=='House'){?> selected="selected" <?php }?>>House</option>
            <option value="Indie" <?php if($audiodata->gener=='Indie'){?> selected="selected" <?php }?>>Indie</option>
            <option value="Jazz & Blues" <?php if($audiodata->gener=='Jazz & Blues'){?> selected="selected" <?php }?>>Jazz & Blues</option>
            <option value="Latin" <?php if($audiodata->gener=='Latin'){?> selected="selected" <?php }?>>Latin</option>
            <option value="Metal" <?php if($audiodata->gener=='Metal'){?> selected="selected" <?php }?>>Metal</option>
            <option value="Piano" <?php if($audiodata->gener=='Piano'){?> selected="selected" <?php }?>>Piano</option>
            <option value="Pop" <?php if($audiodata->gener=='Pop'){?> selected="selected" <?php }?>>Pop</option>
            <option value="R&B & Soul" <?php if($audiodata->gener=='R&B & Soul'){?> selected="selected" <?php }?>>R&B & Soul</option>
            <option value="Reggae" <?php if($audiodata->gener=='Reggae'){?> selected="selected" <?php }?>>Reggae</option>
            <option value="Reggaeton" <?php if($audiodata->gener=='Reggaeton'){?> selected="selected" <?php }?>>Reggaeton</option>
            <option value="Rock" <?php if($audiodata->gener=='Rock'){?> selected="selected" <?php }?>>Rock</option>
            <option value="Soundtrack" <?php if($audiodata->gener=='Soundtrack'){?> selected="selected" <?php }?>>Soundtrack</option>
            <option value="Techno" <?php if($audiodata->gener=='Techno'){?> selected="selected" <?php }?>>Techno</option>
            <option value="Trance" <?php if($audiodata->gener=='Trance'){?> selected="selected" <?php }?>>Trance</option>
            <option value="Trap" <?php if($audiodata->gener=='Trap'){?> selected="selected" <?php }?>>Trap</option>
            <option value="Triphop" <?php if($audiodata->gener=='Triphop'){?> selected="selected" <?php }?>>Triphop</option>
            <option value="World" <?php if($audiodata->gener=='World'){?> selected="selected" <?php }?>>World</option>

            <option style="color:#ff9100;" value="AUDIO" disabled><b>AUDIO</b></option>
            <option value="Audiobooks" <?php if($audiodata->gener=='Audiobooks'){?> selected="selected" <?php }?>>Audiobooks</option>
            <option value="Business" <?php if($audiodata->gener=='Business'){?> selected="selected" <?php }?>>Business</option>
            <option value="Comedy" <?php if($audiodata->gener=='Comedy'){?> selected="selected" <?php }?>>Comedy</option>
            <option value="Entertainment" <?php if($audiodata->gener=='Entertainment'){?> selected="selected" <?php }?>>Entertainment</option>
            <option value="Learning" <?php if($audiodata->gener=='Learning'){?> selected="selected" <?php }?>>Learning</option>
            <option value="News & Politics" <?php if($audiodata->gener=='News & Politics'){?> selected="selected" <?php }?>>News & Politics</option>
            <option value="Poetry" <?php if($audiodata->gener=='Poetry'){?> selected="selected" <?php }?>>Poetry</option>
            <option value="Religion & Spirituality" <?php if($audiodata->gener=='Religion & Spirituality'){?> selected="selected" <?php }?>>Religion & Spirituality</option>
            <option value="Science" <?php if($audiodata->gener=='Science'){?> selected="selected" <?php }?>>Science</option>
          </select>
        </li>
            <li>
              <label><?php echo JText::_( 'COM_COMMUNITY_TAGES' ); ?></label>
              <div id="tags">
                 <?php
                    $tags= explode(",",$audiodata->tags);
                    for($i=0;$i<count($tags);$i++)
                    {
                      if($tags[$i]!='')
                      {
                        echo '<span>'.$tags[$i].'</span>';
                      }
                    }
                 ?>
                <input type="text" id="audio_tags"  class="audio_tags" name="audio_tags" placeholder="Enter tags" />
                <input type="hidden" id="audiotags" name="audiotags" value="<?php echo $audiodata->tags.','; ?>">
                
              </div>
            </li>
            <li>
              <label><?php echo JText::_( 'COM_COMMUNITY_PRIVECY_SETTING' ); ?></label>
        
              <div class="bottmddivpchek"> 
                  <span><input type="radio" name="audio_privacy" class="audio_privacy" <?php if($audiodata->permissions=='10'){?> checked="checked" <?php } ?> value="10" /><b><?php echo JText::_( 'COM_COMMUNITY_PUBLIC' ); ?> </b><?php echo JText::_( 'COM_COMMUNITY_TRACHAPPEAR' ); ?></span>
                  <span><input type="radio" name="audio_privacy"  class="audio_privacy" <?php if($audiodata->permissions=='0'){?> checked="checked" <?php } ?> value="0"  /><b><?php echo JText::_( 'COM_COMMUNITY_PRIVATE' ); ?> </b><?php echo JText::_( 'COM_COMMUNITY_TRACH_VISIBLE' ); ?></span>
              </div>
            </li>
            </ul>
        </div>
        
    <div class="bottomlinsave">       
        <input type="button" value="CANCEL" name="cancel_btn" onclick="close_box();"/>
        <input type="submit" value="SUBMIT" name="sub" onclick="return maxsizesub();"/>   
    </div>   

    <div class="clr"></div>
  </div>
  <input type="hidden" id="oldImg" name="oldImg" value="<?php echo $audiodata->thumb; ?>">
  <input type="hidden" id="oldAudio" name="oldAudio" value="<?php echo $audiodata->audio_file; ?>">
  <input type="hidden" id="audioId" name="audioId" value="<?php echo $audiodata->id; ?>">
    <input type="hidden" name="task" value="audio_update" />
    <input type="hidden" name="view" value="audios" />
    <input type="hidden" name="live_url" id="live_url" value="<?php echo $url;?>" />
</form>


<script type="text/javascript">

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



