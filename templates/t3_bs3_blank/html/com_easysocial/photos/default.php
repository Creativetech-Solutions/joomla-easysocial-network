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
$script = '
jQuery(window).load(function() {
	var vheight = jQuery(".es-photo-content-body").height();
	jQuery("body").prepend("<div class=\"photo-bg\" style=\"background: #000;position: absolute;width: 100%;height:"+vheight+"px;z-index: -1;\"></div>");
});

jQuery(document).ready(function($) {	
	var com_form = $(".es-photo-comments .es-comments-form");
	var loadmore = $(".es-photo-comments .es-comments-control");
	
	$(".es-photo-comments .es-comments-form").remove();
	$(".es-photo-comments .es-comments-control").remove();
	
	$("ul.fd-reset-list.es-comments").parent().prepend(com_form);
	$("ul.fd-reset-list.es-comments").parent().append(loadmore);
	
});
';
JFactory::getDocument()->addScriptDeclaration($script);
?>
<?php echo $content; ?>
<?php //echo $this->includeTemplate('site/photos/item', array('photo' => $lib->data)); ?>