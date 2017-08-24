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

$title = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
$anchor_css = $item->anchor_css ? $item->anchor_css : '';

$linktype = $item->title;

if ($item->menu_image)
{
	$linktype = JHtml::_('image', $item->menu_image, $item->title);

	if ($item->params->get('menu_text', 1))
	{
		$linktype .= '<span class="image-title">' . $item->title . '</span>';
	}
}

?>
<a class="nav-header <?php echo $anchor_css; ?>"<?php echo $title; ?>><?php echo $linktype; ?></a>
<h2><i></i><?php echo $item->title; ?></h2>