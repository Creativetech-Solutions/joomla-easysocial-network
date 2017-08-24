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


<!-- <script src="http://maps.google.com/maps/api/js?v=3.13&sensor=false&libraries=places"></script> -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.13&sensor=false&libraries=places"></script>

	
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
            	<div id="map_module_canvas" style="width:400px;height:300px;border:1px solid #888888;"></div>
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

<?php 
    $my_address = array();
    $mytitle = array();
    $infowindow = array();

    for($i=0;$i<count($data);$i++)
    {
        if($data[$i]->resultType == 5)
        {
            $location =$data[$i]->location;
        
            $address1= str_replace(' ','+',$location);
            //$address1 ='S.G.+Road,+Bodakdev,Ahmedabad,Gujarat,IndiaDrive-In+Road,+Behind+Himalaya+Mall,Ahmedabad,+Gujarat+,Ahmedabad,Gujarat,India';
            $my_address[] = $location;

            $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address1.'&sensor=false');
            $output= json_decode($geocode);

            @$mylat[$i]->lat = $output->results[0]->geometry->location->lat;
            $mylat[$i]->long = $output->results[0]->geometry->location->lng;


            /*@$mylat[$i]->lat = @$hotel_list[$i]->latitude;

            $mylat[$i]->long = $hotel_list[$i]->longitude;*/

            $mytitle[] = $data[$i]->title;

            $infowindow[] = '<div style="" class="my_map_cot"><table><tr><td><h3>'.$data[$i]->title.'</h3></td></tr><tr><td>'.$data[$i]->location.'</td></tr></table></div>';
        }
    }
 
?>


 
<script type="text/javascript">

    /*jQuery(document).ready(function() {
            ViewCustInGoogleMap();
        });*/
    var map
    var barr = [ ] ;

    function ViewCustInGoogleMap()
    {
        var center = new google.maps.LatLng('<?php echo $mylat[0]->lat;?>','<?php echo $mylat[0]->long;?>');
        var settings = {
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          zoom: 3,
          center: center
        };
        map = new google.maps.Map(document.getElementById('map_module_canvas'), settings);

        <?php for ($i = 0; $i <count($infowindow); $i++) {
        
        $temp = $i+1;   ?>
             barr[<?php echo $i;?>] = new Object ;
            
             var image = new google.maps.MarkerImage('<?php echo $map_icon.$temp.'.png';?>' );
                    var marker = new google.maps.Marker({
                    position: new google.maps.LatLng('<?php echo $mylat[$i]->lat;?>','<?php echo $mylat[$i]->long;?>'),
                    title: '<?php echo $mytitle[$i] ?>',
                    dragable: true,
                    clickable: true,
                    map: map,
                    
                });
                
                barr[<?php echo $i;?>].marker = marker ;
                barr[<?php echo $i;?>].html = '<?php echo $infowindow[$i] ?>' ;

                // Create the infoWindow...
                
                barr[<?php echo $i;?>].infoWindow = new google.maps.InfoWindow({
                 content: barr[<?php echo $i;?>].html,
                 maxWidth: 600
                });
                barr[<?php echo $i;?>].listener = makeClosure(<?php echo $i;?>, barr[<?php echo $i;?>].marker) ;
              
                /*marker<?php echo $i;?>.setTitle('<?php echo $mytitle[$i] ?>'.toString());
                attachSecretMessage(marker<?php echo $i;?>, '<?php echo $infowindow[$i] ?>');*/
        <?php   
        }?>
    }
    
    function makeClosure( i, marker )
    {
       var listener = google.maps.event.addListener(marker, 'click', function() 
       {
            openInfoWindow(i) ;     // <-- this is the key to making it work
       });
       return listener ;
      // alert(listener);return false;
    }
    function openInfoWindow(i)
    {
       if ( typeof(lastI) == 'number' && typeof(barr[lastI].infoWindow) == 'object' )
       { 
            barr[lastI].infoWindow.close() ;
       }
       lastI = i ;    
       barr[i].infoWindow.open(map,barr[i].marker) ;    
    }
</script>


<script type="text/javascript">
    
    jQuery(document).ready(function()
    {
           ViewCustInGoogleMap();                      
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

    function initialize() 
    {
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