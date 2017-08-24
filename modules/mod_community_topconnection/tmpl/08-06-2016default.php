<?php
/**
* @copyright (C) 2015 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' ); 

$svgPath = CFactory::getPath('template://assets/icon/joms-icon.svg');
include_once $svgPath;
$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_community/templates/jomsocial/assets/css/profile_edit.css");
$my = CFactory::getUser();
$uri = JURI::getInstance();
$url = $uri->root();

$page = JRequest::getVar('page','','','string');
?>


<div class="joms-module--topmembers <?php if($page == "newsfeed"){ echo "hidemodclass"; }?>" >

    <?php if ( !empty($users) ) { ?>
    <ul class="joms-list">
    	
    			<span class="tithei"><?php echo $tot_user."&nbsp;".JText::sprintf('MOD_COMMUNITY_TOPCONNECTIONS_CONNECTIONS', CStringHelper::escape( $user->name ) ); ?></span>
    	
        <?php foreach ($users as $user) { ?>
            <li class="joms-stream__header no-gap" >
                <div class="joms-popover__avatar">
                    <div class="joms-avatar">
                    <a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&page=overview&userid='.$user->id.'');?>">
                    	<?php if(file_exists($user->thumb)) {?>
                        <img src="<?php echo $user->thumb; ?>"
                            title="<?php echo JText::sprintf('MOD_COMMUNITY_TOPCONNECTIONS_GO_TO_PROFILE', CStringHelper::escape( $user->name ) ); ?>"		<?php }else if($user->gender='Male'){
                          ?>
                          <img src="<?php echo $url;?>components/com_community/assets/default-male-avatar.png"
                            title="<?php echo JText::sprintf('MOD_COMMUNITY_TOPCONNECTIONS_GO_TO_PROFILE', CStringHelper::escape( $user->name ) ); ?>"          
                            
                          <?php  }
                                else if($user->gender='Female'){
                          ?>
                          <img src="<?php echo $url;?>components/com_community/assets/default-male-avatar.png"
                            title="<?php echo JText::sprintf('MOD_COMMUNITY_TOPCONNECTIONS_GO_TO_PROFILE', CStringHelper::escape( $user->name ) ); ?>"          
                            
                          <?php  }?>
                            alt="<?php echo CStringHelper::escape( $user->name ); ?>"
                            data-author="<?php echo $user->id; ?>">
                        </a>
                    </div>
                </div>
              <!--  <div class="joms-popover__content">
                    <h5><a href="<?php echo $user->link; ?>"><?php echo $user->name; ?></a></h5>
                    <ul class="joms-list joms-text--light joms-list--inline">
                        <li >
                            <small>
                            <svg class="joms-icon" viewBox="0 0 14 20">
                                <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-star"/>
                            </svg>
                            <!-- TOTAL OF USER POINT -->
                 <!--           <span><?php echo $user->userpoints; ?></span>
                            </small>
                        </li>
                        <li>
                            <small>
                            <svg class="joms-icon" viewBox="0 0 14 20">
                                <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-thumbs-up"/>
                            </svg>
                            <!-- TOTAL OF LIKE -->
                 <!--           <span><?php echo $user->likes; ?></span>
                            </small>
                        </li>
                        <li>
                            <small>
                            <svg class="joms-icon" viewBox="0 0 14 20">
                                <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-eye"/>
                            </svg>
                            <!-- TOTAL OF VIEW -->
                <!--            <span><?php echo $user->views; ?></span>
                            </small>
                        </li>
                    
                    </ul>
                </div> -->
            </li>
        <?php } ?>
        
        	       		<?php $link=JRoute::_('index.php?option=com_community&view=friends&page=friends&userid='.$my->id.'&Itemid=1395&lang=en',false);?>
        		<a class="alllink" href="<?php echo $link; ?>"><?php echo JText::_('MOD_COMMUNITY_TOP_CONNECTION_SEE_ALL'); ?></a>
        	
    </ul>
    		
    <?php } else { ?>
    <div class="joms-blankslate"><?php echo JText::_('MOD_COMMUNITY_TOPCONNECTIONS_NO_MEMBERS'); ?></div>
    <?php } ?>

</div>
