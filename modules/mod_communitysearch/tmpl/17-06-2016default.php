<?php 
/**
* @package   JE communitysearch
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access'); 
$doc 	=  JFactory::getDocument();
$user 	=  JFactory::getUser();
$uri 	=  JFactory::getURI();
$url	= $uri->root();
$doc->addScript($url."modules/mod_communitysearch/js/ajax.js");
$doc->addScript($url."modules/mod_communitysearch/js/autosuggestions.js");
$no_text=JText::_( 'NO_RESULT_FOR_SEARCH');
$post = JRequest::get('post');
?>


<script src="http://maps.google.com/maps/api/js?v=3.13&sensor=false&libraries=places"></script>


	
	<h2><?php echo JText::_( 'FILTER_SERACH_RESULT' ); ?></h2>
	<div class="FilterSearch"> 
    
	   <form name="filtersearchForm" id="filtersearchForm" method="post" action="<?php echo JRoute::_('index.php?option=com_joomproject&view=search'); ?>" enctype="multipart/form-data">
       		<div class="searchButtonTop"><input type="submit" name="findresult" value="<?php echo JText::_( 'TEXT_SERACH_RESULT' ); ?>" /></div>
       		<div class="FirstDv" id="FirstDv">
            	<ul>
                	<li class="AllSearch FilterOptions <?php if(!JRequest::getVar('searchkeyword')){?>selected<?php } ?>" id="0"><i aria-hidden="true" class="fa fa-search"></i>&nbsp;<?php echo JText::_( 'ALL_SEARCH' ); ?></li>
                    <li class="PeopleSearch FilterOptions <?php if(JRequest::getVar('searchkeyword')==1){?>selected<?php } ?>" id="1"><i aria-hidden="true" class="fa fa-user"></i>&nbsp;<?php echo JText::_( 'PEOPLE_SEARCH' ); ?></li>
                    <li class="BusinessesSearch FilterOptions <?php if(JRequest::getVar('searchkeyword')==2){?>selected<?php } ?>" id="2"><i aria-hidden="true" class="fa fa-building"></i>&nbsp;<?php echo JText::_( 'BUSINESSES_SEARCH' ); ?></li>
                    <li class="JobsSearch FilterOptions <?php if(JRequest::getVar('searchkeyword')==3){?>selected<?php } ?>" id="3"><i aria-hidden="true" class="fa fa-briefcase"></i>&nbsp;<?php echo JText::_( 'JOBS_SEARCH' ); ?></li>
                    <li class="ProjectsSearch FilterOptions <?php if(JRequest::getVar('searchkeyword')==4){?>selected<?php } ?>" id="4"><i aria-hidden="true" class="fa fa-folder-open"></i>&nbsp;<?php echo JText::_( 'PROJECTS_SEARCH' ); ?></li>
                    <li class="EventsSearch FilterOptions <?php if(JRequest::getVar('searchkeyword')==5){?>selected<?php } ?>" id="5"><i aria-hidden="true" class="fa fa-calendar"></i>&nbsp;<?php echo JText::_( 'EVENTS_SEARCH' ); ?></li>
                </ul>
            </div>
            
            <div class="SecondDv" id="SecondDv">
            	<span><?php echo JText::_( 'TEXT_SEARCH' ); ?></span>
                <input type="text" name="searchkeyword" id="searchkeyword" value="<?php echo JRequest::getVar('searchkeyword',''); ?>" />
            </div>
            
            <div class="ThirdDv" id="ThirdDv">
            	<span><?php echo JText::_( 'TEXT_CATEGORY' ); ?></span>
                <?php echo $categorylist; ?>
            </div>
            
            <div class="FourthDv" id="FourthDv">
            	<span><?php echo JText::_( 'TEXT_LOCATION' ); ?></span>
                <?php echo $countrylist; ?>
                <input id="city" class="inputbox" type="text" value="<?php echo JRequest::getVar('city',''); ?>" size="30" name="city" placeholder="<?php echo JText::_( 'PLACEHOLDER_CITY' ); ?>" />
            </div>
            
            <div class="FifthDv" id="FifthDv">
            	<span><?php echo JText::_( 'TEXT_SKILLS' ); ?></span>
                <input id="skills" class="inputbox autosuggest" type="text" value="<?php echo JRequest::getVar('skills',''); ?>" size="30" name="skills" placeholder="<?php echo JText::_( 'PLACEHOLDER_SKILL' ); ?>" />
                <div id="dv_autosuggesion" class="dv_autosuggesion"></div>
            </div>
            
            <div class="SixthDv" id="SixthDv">
            	<span><?php echo JText::_( 'TEXT_JOB_TYPE' ); ?></span>
               <strong> <input type="checkbox" id="jobtype" name="jobtype[]" value="1" /><?php echo JText::_( 'TEXT_VOLUNTEER' ); ?></strong>
               <strong> <input type="checkbox" id="jobtype" name="jobtype[]" value="2" /><?php echo JText::_( 'TEXT_PAID' ); ?></strong>
            </div>
            <div class="searchButtonBottom"><input type="submit" name="findresultbottom" value="<?php echo JText::_( 'TEXT_SERACH_RESULT' ); ?>" /></div>
            <div class="SeventhDv">
            	MAP
            </div>
            

            <input type="hidden" id="view" name="view" value="search" />
            <input type="hidden" id="option" name="option" value="com_joomproject" />
            <input type="hidden" id="modulelive_url" name="modulelive_url" value="<?php echo $url;?>" />
            <input type="hidden" id="countryn" name="countryn" value="<?php echo JRequest::getVar('countryn','us'); ?>" />
            <input type="hidden" id="countrytext" name="countrytext" value="<?php echo JRequest::getVar('countrytext',''); ?>" />
            <input type="hidden" id="skillId" name="skillId" value="<?php echo JRequest::getVar('skillId','0'); ?>" />
            <input type="hidden" id="filteroption" name="filteroption" value="<?php echo JRequest::getVar('filteroption','0'); ?>" />
            <input type="hidden" id="no_text" name="no_text" value="<?php echo $no_text;?>" />
            
       </form>
	</div>



<script type="text/javascript">

jQuery(document).ready(function(){
                            
    jQuery(document).on('click', '.FilterOptions', function(e){     
                                                            
        jQuery(".FilterOptions.selected").removeClass("selected");
        jQuery(this).addClass("selected");
        jQuery("#filteroption").val(this.id);
        
        if(this.id==0 || this.id==1)
            jQuery("#FifthDv").show('slow');
        else
            jQuery("#FifthDv").hide('slow');
        if(this.id==0 || this.id==5)
            jQuery("#ThirdDv").show('slow');
        else
            jQuery("#ThirdDv").hide('slow');
        if(this.id==0 || this.id==3)
            jQuery("#SixthDv").show('slow');
        else
            jQuery("#SixthDv").hide('slow');
        
        
    });

});




var geocoder;
var map;
var cntr = "us";
mylat ='39.1185';
mylong ='117.1982';

function initialize() {
	geocoder = new google.maps.Geocoder();
	var mapOptions = {
		center: new google.maps.LatLng(mylat, mylong),
		zoom: 13
	};
  
  	var input =document.getElementById('city');
  
	options = {
	  types: ['(cities)'],
	  componentRestrictions: { country: cntr }
	}
  	var autocomplete = new google.maps.places.Autocomplete(input,options);
}

google.maps.event.addDomListener(window, 'load', initialize);


var no_tex = document.getElementById('no_text').value;
//alert('<?php echo $url?>modules/mod_communitysearch/ajaxsearch.php?json=true&no_text="+no_tex+"&limit=10&');
	var options = {
		script:"<?php echo $url?>modules/mod_communitysearch/ajaxsearch.php?json=true&no_text="+no_tex+"&limit=10&",
		varname:"input",
		json:true,
		shownoresults:true,
		maxresults:6,
		callback: function (obj) { 	 
		ajx_word=document.getElementById("skills").value;
		//following return can stop form to submit
		return;
		} 	 
	};
	
	var as_json = new bsn.AutoSuggest('skills', options);

</script>