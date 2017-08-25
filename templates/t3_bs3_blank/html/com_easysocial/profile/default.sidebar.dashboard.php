<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="account_overview_siderbar">
    <div class="dashboard_header">
        <div class="dashboard-box-title saccount-overview">
           <span>Account</span>
           <span>Overview</span>
         </div>
     </div>
     <div class="account_overview_details">
        <?php foreach( $steps as $step ){
                if($step->title == 'Skills & Specialties'){
                    //print_r($step->fields);
                ?>
                <?php if( !$step->hide ) { ?>
                    <?php 
                    if( $step->fields ){ 
                        $empty = true; ?>
                        <div class="mt-5 user-skills">
                            <span>Skills & Specialty</span>
                            <button data-toggle="collapse" data-target="#collapseSklls">
                                <i class="cskills fa fa-plus"></i>
                            </button>
                            <div class="collapse" id="collapseSklls">
                                <ul>
                        <?php
                        foreach( $step->fields as $field ){ 
                            
                            if($field->unique_key == 'creativefieldsone' && $field->data != ''){
                                echo '<li>';
                                //echo $field->data;
                                //$row[$field->data]['title']
                                $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                echo $sktitle[$field->data]['title'];
                                //print_r($sktitle);
                            }
                            if($field->unique_key == 'crfone_speciality'){
                                if($field->data != ''){
                                    echo '<span> / '.$field->data.'</span>';	
                                    echo '</li>';
                                }else{
                                    echo '</li>';	
                                }
                            }
                            
                            if($field->unique_key == 'creativefieldstwo' && $field->data != ''){
                                echo '<li>';
                                //echo $field->data;
                                $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                echo $sktitle[$field->data]['title'];
                            }
                            if($field->unique_key == 'crftwo_speciality'){
                                if($field->data != ''){
                                    echo '<span> / '.$field->data.'</span>';	
                                    echo '</li>';
                                }else{
                                    echo '</li>';	
                                }
                            }
                            
                            if($field->unique_key == 'creativefieldsthree' && $field->data != ''){
                                echo '<li>';
                                //echo $field->data;
                                $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                echo $sktitle[$field->data]['title'];
                            }
                            if($field->unique_key == 'crfthree_speciality'){
                                if($field->data != ''){
                                    echo '<span> / '.$field->data.'</span>';	
                                    echo '</li>';
                                }else{
                                    echo '</li>';	
                                }
                            }
                            
                        } 
                        ?>
                            </ul>
                           </div>
                        </div>
                        <?php
                    }
                    ?>
                <?php } ?>
            <?php }
            }
            foreach( $steps as $step ){ 
                ?>
                <?php if( !$step->hide ) { ?>
                    <?php 
                    if( $step->fields ){ 
                        $empty = true; ?>
                        <?php
                        foreach( $step->fields as $field ){
                            /*if($field->unique_key == 'creativefieldsone' && $field->data != ''){
                                echo '
                                <div class="mt-5 user-skills">
                                    <span>Skills & Speciality</span>
                                    <button data-toggle="collapse" data-target="#collapseSklls">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <div class="collapse" id="collapseSklls">
                                        <ul>
                                            <li>
                                                
                                            </li>
                                        </ul>
                                    </div>
                                </div>';	
                            }*/
                            if($field->unique_key == 'ADDRESS'){
                                if($field->data['address']){
                                    echo '<div class="mt-5 user-location"><span>'.$field->data['state'].','.$field->data['country'].' </span></div>';
                                }
                            } 
                            if($field->unique_key == 'WEBSITE' && $field->data !=''){
                                echo '<div class="mt-5 user-web"><span>'.$field->data.'</span></div>';	
                            }
                        } 
                        ?>
                        <?php
                    }
                    ?>
                <?php } ?>
            <?php } ?>
            <?php
            $usrRegdate = $user->getRegistrationDate();
            echo '<div class="mt-5 user-registered"><span>'.$usrRegdate->toLapsed().'</span></div>';
            
            
            foreach( $steps as $step ){
                if($step->title == 'Skills & Specialties'){
                    //print_r($step->fields);
                ?>
                <?php if( !$step->hide ) { ?>
                    <?php 
                    if( $step->fields ){ 
                        $empty = true; 
                        $teach_arr = array();
                        ?>
                        <div class="mt-5 user-hire">
                            <span>Available for Hire</span>
                            <button data-toggle="collapse" data-target="#collapseHire">
                                <i class="chire fa fa-plus"></i>
                            </button>
                            <div class="collapse" id="collapseHire">
                                <ul>
                        <?php
                        foreach( $step->fields as $field ){ 
                            
                            if($field->unique_key == 'creativefieldsone' && $field->data != ''){
                                //$cfone =  $field->data;
                                $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                $cfone = $sktitle[$field->data]['title'];
                            }
                            if($field->unique_key == 'crfone_teach_hire' && $field->data != ''){
                                if(strpos($field->data,'cfone-hire-available') !== false){
                                    echo '<li><span>';
                                    echo $cfone;
                                    echo '</span></li>';
                                }
                                if(strpos($field->data,'cfone-teach-lessons') !== false){
                                    $teach_arr['cfone'] = $cfone;
                                }
                            }
                            if($field->unique_key == 'creativefieldstwo' && $field->data != ''){
                                //$cftwo =  $field->data;
                                $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                $cftwo = $sktitle[$field->data]['title'];
                            }
                            if($field->unique_key == 'crftwo_teach_hire' && $field->data != ''){
                                if(strpos($field->data,'cftwo-hire-available') !== false){
                                    echo '<li><span>';
                                    echo $cftwo;
                                    echo '</span></li>';
                                }
                                if(strpos($field->data,'cftwo-teach-lessons') !== false){
                                    $teach_arr['cftwo'] = $cftwo;
                                }
                            }
                            if($field->unique_key == 'creativefieldsthree' && $field->data != ''){
                                //$cfthree =  $field->data;
                                $sktitle = EasySocialDashboardHelper::getSkillsTitle($field->id);
                                $cfthree = $sktitle[$field->data]['title'];
                            }
                            if($field->unique_key == 'crfthree_teach_hire' && $field->data != ''){
                                if(strpos($field->data,'cfthree-hire-available') !== false){
                                    echo '<li><span>';
                                    echo $cfthree;
                                    echo '</span></li>';
                                }
                                if(strpos($field->data,'cfthree-teach-lessons') !== false){
                                    $teach_arr['cfthree'] = $cfthree;
                                }
                            }
                        } 
                        ?>
                            </ul>
                           </div>
                        </div>
                        
                        <div class="mt-5 user-teach">
                            <span>I teach lessons.</span>
                            <button data-toggle="collapse" data-target="#collapseTeach">
                                <i class="cteach fa fa-plus"></i>
                            </button>
                            <div class="collapse" id="collapseTeach">
                                <ul>
                                <?php
                                if(count($teach_arr) > 0){
                                    //print_r($teach_arr);
                                    foreach($teach_arr as $teach){
                                        echo '<li><span>';
                                        echo $teach;
                                        echo '</span></li>';	
                                    }
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                <?php } ?>
            <?php }
            }
            ?>
     </div>
</div>
<div class="connections_sidebar">
    <div class="dashboard_header">
        <?php 
        // Get friends model
        $friendsmodel 		= FD::model( 'Friends' );
        $options	= array( 'limit' => 9 );
        // Get list of friends by the current user.
        $friends 	= $friendsmodel->getFriends( $user->id, $options );
        $friendstotal 		= $friendsmodel->getTotalFriends( $user->id ); 
        ?>
        <div class="dashboard-box-title connections">
            <span>Latest</span>
            <span>Connections</span>
            <span class="count"><?php echo $friendstotal; ?></span>
        </div>
    </div>
    <div class="connections_details">
        <?php 
        echo $this->includeTemplate('site/dashboard/sidebar.module.wrapper', array('displayOn' => 'friends', 'friendsData' => $friends,'friendsTotal' => $friendstotal)); ?>
    </div>
</div>
<div class="social_siderbar">
	<?php foreach( $steps as $step ){ 
        if($step->title == 'Social Links'){
        ?>
        <?php if( !$step->hide ) { ?>
        <div class="dashboard_header">
            <div class="dashboard-box-title social">
                <span>Social</span>
                <span>Networks</span>
            </div>
        </div>
            <?php 
            if( $step->fields ){ 
                $empty = true; ?>
                <ul>
                <?php
                foreach( $step->fields as $field ){ 
                    echo '<li class="social-icon-'.$field->unique_key.'">';
                        echo '<a href="'.$field->data.'" target="_blank">';
                        if($field->unique_key == 'gplus'){
                            echo '<i class="fa fa-google-plus fa-2"></i>';	
                        }
                        if($field->unique_key == 'facebook_url'){
                            echo '<i class="fa fa-facebook fa-2"></i>';	
                        }
                        if($field->unique_key == 'twitter_url'){
                            echo '<i class="fa fa-twitter fa-2"></i>';	
                        }
                        echo '</a>';
                        
                    echo '</li>';
                } 
                ?>
                </ul>
                <?php
            }
            ?>
        <?php } ?>
        <?php } ?>
    <?php } ?>
</div>
<div class="location_siderbar">
    <div class="dashboard_header">
        <div class="dashboard-box-title slocation">
           <span>Location</span>
           <span>Details</span>
         </div>
     </div>
     <div class="sidebar_location_details">
        <?php foreach( $steps as $step ){ 
                ?>
                <?php if( !$step->hide ) { ?>
                    <?php 
                    if( $step->fields ){ 
                        $empty = true; ?>
                        <?php
                        $business_hours = '';
                        foreach( $step->fields as $field ){ 
                            
                            if($field->unique_key == 'ADDRESS'){
                                if($currentProfile == 'Business'){
                                
                                    if($field->data['address']){
                                        echo '<script>
                                        jQuery(window).load(function(){
                                            var map;
                                            var myLatLng = {lat: '.$field->data['latitude'].', lng: '.$field->data['longitude'].'};
                                            map = new google.maps.Map(document.getElementById(\'mapsidebar\'), {
                                              center: myLatLng,
                                              zoom: 17
                                            });
                                            var marker = new google.maps.Marker({
                                              position: myLatLng,
                                              map: map
                                            });
                                        });
                                        </script>';
                                        echo '<div id="mapsidebar" style="width:220px;height:80px;margin-bottom: 20px;"></div>';
                                    }
                                }
                                echo '<div class="mt-5 es-teaser-about"><i class="fa fa-home fa-2"></i><span>'.$field->data['address'].'</span></div>';
                            }
                            if($field->unique_key == 'phone_number' && $field->data !=''){
                                echo '<div class="mt-5 es-teaser-about color-white"><i class="fa fa-phone  fa-2"></i><span>'.$field->data.'</span></div>';
                            }
                            if($field->unique_key == 'business_hours' && $field->data !=''){
                                //print_r($field);
                                $business_hours = '<div class="mt-5 es-teaser-about color-white"><i class="fa fa-clock-o  fa-2"></i><span>'.$field->data.'</span></div>';
                            }
                            
                        } 
                        echo $business_hours;
                        ?>
                        <?php
                    }
                    ?>
                <?php } ?>
            <?php } ?>
     </div>
</div>