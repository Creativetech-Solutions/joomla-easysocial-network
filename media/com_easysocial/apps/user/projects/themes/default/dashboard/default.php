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
<div class="app-projects" data-dashboard-app-projects data-app-id="<?php echo $app->id; ?>">

	<div class="es-filterbar row-table">
		<div class="col-cell filterbar-title"><?php echo JText::_( 'APP_USER_PROJECTS_MANAGE_PROJECTS' ); ?></div>

		<div class="col-cell cell-tight">
			<a class="btn btn-es-primary btn-sm pull-right" href="javascript:void(0);" data-app-projects-create><?php echo JText::_( 'APP_PROJECTS_NEW_PROJECT_BUTTON' ); ?></a>
		</div>
	</div>


	<div class="app-contents<?php echo !$projects ? ' is-empty' : '';?>" data-app-contents>
		<p class="app-info">
			<?php echo JText::_( 'APP_USER_PROJECTS_DASHBOARD_INFO' ); ?>
		</p>

		<div class="app-contents-data">
			<ul class="list-unstyled project-items" data-apps-projects>
				<?php if( $projects ){ ?>
					<?php foreach( $projects as $project ){ ?>
						<?php echo $this->loadTemplate( 'apps/user/projects/dashboard/item' , array( 'app' => $app , 'project' => $project , 'appId' => $app->id , 'user' => $user ) ); ?>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>

		<div class="empty" data-feeds-empty>
			<i class="fa fa-database"></i>
			<?php echo JText::_( 'APP_PROJECTS_EMPTY_PROJECTS' ); ?>
		</div>
	</div>

</div>
