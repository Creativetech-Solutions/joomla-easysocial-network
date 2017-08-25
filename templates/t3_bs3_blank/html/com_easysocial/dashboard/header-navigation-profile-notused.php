<?php // not used but if different than the dashboard navigation, use this one after editing it ofcourse ?>
<div class="es-profile-header-footer">
        <nav class="es-list-vertical-divider pull-left">
        
        	<span class="active">
                <a href="<?php echo FRoute::dashboard(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));?>">
                    <i class="fa fa-dashboard"></i>
                    <?php echo JText::_('COM_EASYSOCIAL_USER_DASHBOARD'); ?>
                </a>
            </span>

            <?php if ($this->config->get('photos.enabled')) { ?>
            <span>
                <a href="<?php echo FRoute::albums(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));?>">
                    <i class="fa fa-picture-o"></i>
                    <?php echo JText::sprintf(ES::string()->computeNoun('COM_EASYSOCIAL_USER_ALBUMS' , $user->getTotalAlbums()), $user->getTotalAlbums()); ?>
                </a>
            </span>
            <?php } ?>

            <?php if ($this->config->get('video.enabled', true) && $this->access->allowed('videos.create')) { ?>
            <span>
                <a href="<?php echo FRoute::videos(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));?>">
                    <i class="fa fa-film"></i>
                    <?php echo JText::sprintf(ES::string()->computeNoun('COM_EASYSOCIAL_GROUPS_VIDEOS' , $user->getTotalVideos()), $user->getTotalVideos()); ?>
                </a>
            </span>
            	<?php
                if($currentProfile == 'Business' || $currentProfile == 'Creative'){
                ?>
                <span>
                    <a href="<?php echo FRoute::audio(array('uid' => $user->getAlias(), 'type' => SOCIAL_TYPE_USER));
                    //echo JRoute::_('index.php?option=com_easysocial&view=audio');
                    ?>">
                        <i class="fa fa-music"></i>
                         <?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_PROFILE_AUDIO'); ?>
                    </a>
                </span>
                <?php } ?>
            <?php } ?>
			
            <span>
                <a href="<?php echo FRoute::events();?>">
                    <i class="fa fa-calendar"></i>
                    <?php echo JText::_('COM_EASYSOCIAL_TOOLBAR_PROFILE_EVENTS'); ?>
                </a>
            </span>
			
            <?php /*?><span>
                <a href="<?php echo FRoute::friends( array( 'userid' => $user->getAlias() ) );?>">
                    <i class="fa fa-users"></i>
                    <?php echo JText::sprintf( FD::string()->computeNoun( 'COM_EASYSOCIAL_GENERIC_FRIENDS' , $user->getTotalFriends() ) , $user->getTotalFriends() ); ?>
                </a>
            </span><?php */?>
            <?php if( $this->config->get( 'followers.enabled' ) ){ ?>
            <span>
                <a href="<?php echo FRoute::followers( array( 'userid' => $user->getAlias() ) );?>">
                    <i class="fa fa-share-alt"></i>
                    <?php echo $user->getTotalFollowers();?> <?php echo JText::_( FD::string()->computeNoun( 'COM_EASYSOCIAL_FOLLOWERS' , $user->getTotalFollowers() ) ); ?>
                </a>
            </span>
            <?php } ?>

            <?php /*?><?php if( $this->config->get('badges.enabled' ) && $user->badgesViewable( FD::user()->id ) ){ ?>
            <span>
                <a href="<?php echo FRoute::badges( array( 'layout' => 'achievements', 'userid' => $user->getAlias()));?>">
                    <i class="fa fa-trophy"></i>
                    <?php echo $user->getTotalBadges();?> <?php echo JText::_( FD::string()->computeNoun( 'COM_EASYSOCIAL_ACHIEVEMENTS' , $user->getTotalBadges() ) ); ?>
                </a>
            </span>
            <?php } ?><?php */?>
        </nav>

        <?php /*?><nav class="pull-right">
            <?php if( $this->template->get( 'profile_type' ) ){ ?>
            <span>
                <a href="<?php echo $user->getProfile()->getPermalink();?>" class="profile-type">
                    <i class="fa fa-list-alt"></i> <?php echo $user->getProfile()->get('title');?>
                </a>
            </span>
            <?php } ?>
        </nav><?php */?>
    </div>