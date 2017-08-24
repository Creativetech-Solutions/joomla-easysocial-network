<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet("modules/mod_crowdfundingcategories/css/style.css");
?>
<div class="cf-modcategories-list<?php echo $moduleclassSfx; ?> ">
    <?php foreach ($items as $item) {
        $item->title = htmlentities($item->title, ENT_QUOTES, "UTF-8")
        ?>
        <div class="cf-category">
            <?php if ($params->get("display_images", 1)) { ?>
            <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getCategoryRoute($item->slug)); ?>" class="cf-category-thumbnail">
                <?php if (!empty($item->image_link)) { ?>
                    <img src="<?php echo $item->image_link; ?>" alt="<?php echo $item->title; ?>" />
                <?php } else { ?>
                    <img src="<?php echo "media/com_crowdfunding/images/no_image.png"; ?>"
                         alt="<?php echo $item->title; ?>" width="200"
                         height="200" />
                <?php } ?>
            </a>
            <?php } ?>
            <div class="caption cf-category-content">
                <h3>
                    <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getCategoryRoute($item->slug)); ?>">
                        <?php echo $item->title; ?>
                    </a>
                    <?php
                    if ($params->get("display_projects_number", 0)) {
                        $number = (!isset($projectsNumber[$item->id])) ? 0 : $projectsNumber[$item->id]["number"];
                        echo "( ". $number . " )";
                    } ?>
                </h3>
                <?php if ($params->get("display_description", 1)) { ?>
                    <p><?php echo JHtmlString::truncate($item->description, $descriptionLength, true, false); ?></p>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php } ?>
</div>