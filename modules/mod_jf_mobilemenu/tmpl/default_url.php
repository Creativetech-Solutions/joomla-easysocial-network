<?php
/**
 * @package 	JF Mobile Menu
 * @author		JoomForest.com
 * @email		support@joomforest.com
 * @website		http://www.joomforest.com
 * @copyright	Copyright (C) 2011-2016 JoomForest.com, All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

defined('_JEXEC') or die('Restricted access');

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';
$rel   = $item->anchor_rel ? 'rel="' . $item->anchor_rel . '" ' : '';

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$linktype = $item->title;
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class; ?>href="<?php echo $flink; ?>" <?php echo $title . $rel; ?>><?php echo $linktype; ?></a><h2><i></i><?php echo $linktype; ?></h2><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $flink; ?>" target="_blank" <?php echo $title . $rel; ?>><?php echo $linktype; ?></a><h2><i></i><?php echo $linktype; ?></h2><?php
		break;
	case 2:
		
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
			?><a <?php echo $class; ?>href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a><h2><i></i><?php echo $linktype; ?></h2><?php
		break;
endswitch;
