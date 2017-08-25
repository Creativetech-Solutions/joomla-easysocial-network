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
<li class="project-item" data-apps-projects-item data-id="<?php echo $project->id;?>">

	<div class="clearfix">
		<h4 class="pull-left">
			<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'id' => $app->alias , 'cid' => $project->id , 'uid' => $user->getAlias() , 'type' => SOCIAL_TYPE_USER ) );?>" class="project-title"><?php echo $project->title; ?></a>
		</h4>
		<div class="pull-right btn-group">
			<a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropdown-toggle_ loginLink btn btn-dropdown">
				<i class="icon-es-dropdown"></i>
			</a>

			<ul class="dropdown-menu dropdown-menu-user messageDropDown">
				<li>
					<a href="javascript:void(0);" data-apps-projects-edit>
						<?php echo JText::_( 'APP_PROJECTS_EDIT_BUTTON' );?>
					</a>
				</li>
				<li data-friends-unfriend="">
					<a href="javascript:void(0);" data-apps-projects-delete data-id="<?php echo $project->id;?>">
						<?php echo JText::_( 'APP_PROJECTS_DELETE_BUTTON' );?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<hr />

	<div class="muted small project-date">
		<time datetime="<?php echo $this->html( 'string.date' , $project->created ); ?>" class="project-date">
			<i class="fa fa-calendar"></i>&nbsp; <?php echo $this->html( 'string.date' , $project->created , JText::_( 'DATE_FORMAT_LC1' ) ); ?>
		</time>
	</div>
</li>
