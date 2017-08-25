<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
//if ($this->my->id == 0 || $uid == 0) {
//	ES::requireLogin();
//}
//if($uid == 0 && $this->my->id != 0){
//	$uid = $this->my->id;
//}
//$mid = ES::user($uid)->id;
//$user = ES::user($mid);

$uid = $this->input->get('uid', null, 'int');
$type = $this->input->get('type', SOCIAL_TYPE_USER, 'cmd');

$user 	= FD::user();
// If this is a user type, we will want to get a list of albums the current logged in user created
if ($type == SOCIAL_TYPE_USER) {
	$user 	= FD::user($uid);
	$uid 	= $user->id;
}

$currentProfile = $user->getProfile()->get('title');
// Retrieve user's step
$usersModel = ES::model('Users');
// Get the active step
$activeStep = 0;
$privacy = $this->my->getPrivacy();

// Get the list of available steps on the user's profile
$steps = $usersModel->getAbout($user, $activeStep);
?>
<?php 
if($type == SOCIAL_TYPE_GROUP || $type == SOCIAL_TYPE_GROUPS)
    echo $adapter->getMiniHeader(); 
?>

<div data-es-videos class="es-container es-videos" data-videos-listing>
    <a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
        <i class="fa fa-grid-view  mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_SIDEBAR_TOGGLE' );?>
    </a>
    <?php
	if(JRequest::getVar('subview') != 'all' && $type == SOCIAL_TYPE_USER){
	?>
    <div class="es-profile userProfile" data-id="<?php echo $user->id;?>" data-profile>

	<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'aboveHeader' , array( $user ) ); ?>

	<?php echo $this->render( 'module' , 'es-profile-before-header' ); ?>

	<!-- Include cover section -->
	<?php echo $this->includeTemplate( 'site/videos/default.header', array('currentProfile' => $currentProfile,'user'=>$user,'cover' => $cover,'steps' => $steps,'privacy' => $privacy ) ); ?>

	<?php echo $this->render( 'module' , 'es-profile-after-header' ); ?>
    
	</div>
    <?php
	}
	?>
  	<?php /*?><div data-sidebar class="es-sidebar">
        <?php echo $this->render('module', 'es-videos-sidebar-top', 'site/dashboard/sidebar.module.wrapper'); ?>

        <div class="es-widget">
            <?php if ($allowCreation) { ?>
            <div class="es-widget-create mr-10">
                <a class="btn btn-es-primary btn-create btn-block" href="<?php echo $createLink;?>">
                	<?php echo JText::_('COM_EASYSOCIAL_VIDEOS_ADD_VIDEO');?>
                </a>
            </div>
            <hr class="es-hr mt-15 mb-10" />
            <?php } ?>

            <ul data-es-videos-filters="" class="es-widget-filter fd-reset-list">
                <li class="filter-item <?php echo $filter == '' ? 'active' : '';?>">
                    <a href="<?php echo $adapter->getAllVideosLink();?>"
                        data-videos-filter
                        data-type="all"
                        title="<?php echo $allVideosPageTitle;?>">
                        <span><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FILTERS_ALL_VIDEOS');?></span>
                        <b data-total><?php echo $total;?></b>
                    </a>
                </li>

                <li class="filter-item <?php echo $filter == 'featured' ? 'active' : '';?>">
                    <a href="<?php echo $adapter->getAllVideosLink('featured');?>"
                        data-videos-filter
                        data-type="featured"
                        title="<?php echo $featuredVideosPageTitle;?>">
                        <span><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FILTERS_FEATURED_VIDEOS');?></span>
                        <b data-total-featured><?php echo $totalFeatured;?></b>
                    </a>
                </li>

                <?php if ($showMyVideos) { ?>
                    <li class="filter-item <?php echo $filter == 'mine' ? 'active' : '';?>">
                        <a href="<?php echo FRoute::videos(array('filter' => 'mine'));?>"
                            data-videos-filter
                            data-type="mine"
                            title="<?php echo JText::_('COM_EASYSOCIAL_PAGE_TITLE_VIDEOS_FILTER_MINE');?>">
                            <span><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FILTERS_MY_VIDEOS');?></span>
                            <b data-total-created><?php echo $totalUserVideos;?></b>
                        </a>
                    </li>

                    <?php if ($totalPending > 0) { ?>
                        <li class="filter-item <?php echo $filter == 'pending' ? 'active' : '';?>">
                            <a href="<?php echo FRoute::videos(array('filter' => 'pending'));?>"
                                data-videos-filter
                                data-type="pending"
                                title="<?php echo JText::_('COM_EASYSOCIAL_PAGE_TITLE_VIDEOS_FILTER_PENDING');?>">
                                <span><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_FILTERS_PENDING_VIDEOS');?></span>
                                <b data-total-pending><?php echo $totalPending;?></b>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>

        <div class="es-widget">
            <div class="es-widget-head">
                <div class="pull-left widget-title"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING');?></div>
            </div>

            <div class="es-widget-body pr-10">
                <select class="form-control input-sm" data-videos-sorting>
                    <option value=""><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING_BY');?></option>
                    <option value="latest" <?php echo ($sort == 'latest') ? ' selected="true"' : ''; ?>><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING_RECENTLY_ADDED');?></option>
                    <option value="alphabetical" <?php echo ($sort == 'alphabetical') ? ' selected="true"' : ''; ?>><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING_ALPHABET');?></option>
                    <option value="popular" <?php echo ($sort == 'popular') ? ' selected="true"' : ''; ?>><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING_POPULAR');?></option>
                    <option value="commented" <?php echo ($sort == 'commented') ? ' selected="true"' : ''; ?>><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING_MOST_COMMENTED');?></option>
                    <option value="likes" <?php echo ($sort == 'likes') ? ' selected="true"' : ''; ?>><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_SORTING_MOST_LIKES');?></option>
                </select>
            </div>
        </div>

        <div class="es-widget">
            <div class="es-widget-head">
                <div class="pull-left widget-title"><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_CATEGORIES');?></div>
            </div>

            <?php if ($categories) { ?>
                <ul data-es-videos-categories class="es-widget-filter fd-reset-list">
                    <?php foreach ($categories as $category) { ?>
                    <li class="filter-item<?php echo $currentCategory == $category->id ? ' active' : '';?>">
                        <a href="<?php echo $category->getPermalink(true, $uid, $type);?>"
                            data-videos-filter
                            data-type="category"
                            data-id="<?php echo $category->id;?>"
                            title="<?php echo JText::_($category->pageTitle, true);?>">
                            <span><?php echo JText::_($category->title);?></span>
                            <b data-total-videos="<?php echo $category->getTotalVideos($cluster, $uid, $type);?>"><?php echo $category->getTotalVideos($cluster, $uid, $type);?></b>
                        </a>
                    </li>
                    <?php } ?>

                </ul>
            <?php } ?>
        </div>

        <?php echo $this->render('module', 'es-videos-sidebar-bottom', 'site/dashboard/sidebar.module.wrapper'); ?>
    </div><?php */?>

	<div class="es-container">
        <div class="es-content">
            
            <?php //secho $this->render('module' , 'es-videos-before-contents'); ?>
            
            <?php
			//get featured videos
			$model = ES::model('Videos');
			$options['featured'] = true;
			$options['limit'] = 1;
			$options['filter'] = SOCIAL_TYPE_USER;
			$options['userid'] = $user->id;
			$featuredVideos = array();
			$featuredVideos = $model->getVideos($options);

            //get all videos
            $optionsall['limit'] = 999;
            $optionsall['filter'] = SOCIAL_TYPE_USER;
            $optionsall['userid'] = $user->id;
            $allvideos = array();
            $allvideos = $model->getVideos($optionsall);
			
			if(JRequest::getVar('subview') == 'all'){
				echo $this->output('site/videos/default.all.items', array('videos' => $videos, 'pagination' => $pagination, 'filter' => $filter, 'returnUrl' => $returnUrl,'user'=>$user,'featuredVideos' => $featuredVideos, 'allowCreation'=>$allowCreation,'createLink'=>$createLink));
			}else{
			?>
    
            <div class="es-video-listing es-video-item-group" data-videos-result>
            	<div class="add-video-container">
                	
                    	<?php if ($allowCreation) { ?>
                        <div class="es-widget-create pull-right">
                            <a class="btn-add-video" href="<?php echo $createLink;?>">
                                <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_ADD_VIDEO');?>
                            </a>
                        </div>
                        <?php } ?>
                     
                </div>
                <div class="">
                        <?php 
						
						if ($featuredVideos) { ?>
                            <?php echo $this->output('site/videos/default.featured.items', array('featuredVideos' => $featuredVideos, 'returnUrl' => $returnUrl)); ?>
                        <?php } ?>                  
                </div>
                <div class="other-videos">
                	<?php echo $this->output('site/videos/default.items', array('videos' => $allvideos, 'pagination' => $pagination, 'filter' => $filter, 'returnUrl' => $returnUrl,'user'=>$user)); ?>
                </div>
                <?php /*?><div class="col-md-3">
					<?php echo $this->output('site/videos/default.others', array('featuredVideos' => $featuredVideos,'videos' => $videos, 'pagination' => $pagination, 'filter' => $filter, 'returnUrl' => $returnUrl)); ?>
                </div><?php */?>
            </div>
            
            <?php
			}
			?>
    
            <?php echo $this->render('module' , 'es-videos-after-contents'); ?>
        </div>
    </div>
</div>
