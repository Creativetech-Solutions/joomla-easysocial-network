<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$doc->addStyleSheet("modules/mod_crowdfundinglatest/css/style.css");
?>
<div class="cf-modlatest<?php echo $moduleclassSfx; ?>">

    <?php if (!empty($projects)) { ?>

        <?php foreach ($projects as $project) {

            $title       = JHtmlString::truncate($project->title, $titleLength);
            $description = JHtmlString::truncate($project->short_desc, $descriptionLength);

            // Route project link
            $projectLink = JRoute::_(CrowdfundingHelperRoute::getDetailsRoute($project->slug, $project->catslug));

            $image = CrowdfundingLatestModuleHelper::getImage($params->get("image_type", "square"), $project, $componentParams, $imagesDirectory);

            if (!empty($image)) {
                $projectClass = "cf-mlv-project-80";
            } else {
                $projectClass = "cf-mlv-project-100";
            }

            // Prepare social profile.
            if (!empty($displayCreator)) {
                $socialProfile = (!$socialProfiles) ? null : $socialProfiles->getLink($project->user_id);
                $profileName   = JHtml::_("crowdfunding.socialProfileLink", $socialProfile, $project->user_name);
            }

            // Prapare information about project funding state.
            if ($displayInfo) {
                if (!empty($project->funding_days)) {
                    $fundingStartDate = new Crowdfunding\Date($project->funding_start);
                    $fundingEndDate = $fundingStartDate->calculateEndDate($project->funding_days);
                    $project->funding_end = $fundingEndDate->format("Y-m-d");
                }
                $startingDate = JHtml::_('crowdfunding.date', $project->funding_start, JText::_('DATE_FORMAT_LC3'));
                $endingDate   = JHtml::_('crowdfunding.date', $project->funding_end, JText::_('DATE_FORMAT_LC3'));
            }

            ?>
            <div>
                <?php if (!empty($image)) { ?>
                    <div class="cf-mlv-image">
                        <?php if ($params->get("image_link", 0)) { ?>
                        <a href="<?php echo $projectLink; ?>">
                            <?php } ?>
                            <img src="<?php echo $image["image"]; ?>"
                                 alt="<?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?>"
                                 width="<?php echo $image["width"]; ?>" height="<?php echo $image["height"]; ?>"/>
                            <?php if ($params->get("image_link", 0)) { ?>
                        </a>
                    <?php } ?>
                    </div>
                <?php } ?>

                <div class="<?php echo $projectClass; ?>">
                    <h5>
                        <a href="<?php echo $projectLink; ?>">
                            <?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?>
                        </a>
                    </h5>
                    <?php if ($displayCreator) { ?>
                        <span class="font-xxsmall">
                    <?php echo JText::sprintf("MOD_CROWDFUNDINGLATEST_BY_S", $profileName); ?>
               </span>
                    <?php } ?>

                    <?php if (!empty($displayDescription)) { ?>
                        <p class="font-xxsmall">
                            <?php echo htmlspecialchars($description, ENT_QUOTES, "UTF-8"); ?>
                        </p>
                    <?php } ?>

                    <?php if ($displayInfo) { ?>
                        <p class="font-xxsmall bolder"><?php echo JText::sprintf("MOD_CROWDFUNDINGLATEST_STARTED_ON", $startingDate, $endingDate); ?></p>
                        <p class="font-xxsmall bolder"><?php echo JText::sprintf("MOD_CROWDFUNDINGLATEST_GOAL", $amount->setValue($project->goal)->formatCurrency(), $amount->setValue($project->funded)->formatCurrency()); ?></p>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php } ?>

    <?php } ?>
</div>