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
	<h4 class="es-stream-content-title">
		<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=apps&layout=canvas&id=' . $appId . '&cid=' . $project->id . '&userid=' . $user->id );?>" class="project-title"><?php echo $project->title; ?></a>
	</h4>

	<div class="project-meta">
		<time datetime="<?php echo $this->html( 'string.date' , $project->created ); ?>" class="project-date">
			<span>
				<i class="fa fa-calendar "></i> <?php echo $this->html( 'string.date' , $project->created , JText::_( 'DATE_FORMAT_LC1' ) ); ?>
			</span>
		</time>
	</div>

	<div class="project-excerpt">
		<?php echo $project->getContent();?>
	</div>

	<div class="es-action-wrap">
		<ul class="list-unstyled es-action-feedback">
			<li>
				<a href="javascript:void(0);"><?php echo $project->likes->button();?></a>
			</li>
		</ul>

		<div data-stream-counter class="es-stream-counter<?php echo ( $project->likes->getCount() == 0 ) ? ' hide' : ''; ?>">
			<div class="es-stream-actions"><?php echo $project->likes->toHTML(); ?></div>
		</div>
		<div class="es-stream-actions">
			<?php echo $project->comments->getHTML( array( 'hideEmpty' => false ) );?>
		</div>
	</div>

</li>
