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

        <div class="row">

            <?php foreach ($projects as $project) {

                $title       = JHtmlString::truncate($project->title, $titleLength);
                $description = JHtmlString::truncate($project->short_desc, $descriptionLength);

                $image = CrowdfundingLatestModuleHelper::getImage($params->get("image_type", "square"), $project, $componentParams, $imagesDirectory);

                // Route project link
                $projectLink = JRoute::_(CrowdfundingHelperRoute::getDetailsRoute($project->slug, $project->catslug));

                // Calculate span.
                $itemSpan = ceil(12 / $limitResults);
                if ($itemSpan <= 0) {
                    $itemSpan = 1;
                }

                // Prepare social profile.
                if (!empty($displayCreator)) {
                    $socialProfile = (!$socialProfiles) ? null : $socialProfiles->getLink($project->user_id);
                    $profileName   = JHtml::_("crowdfunding.socialProfileLink", $socialProfile, $project->user_name);
                }

                // Prepare information about project funding state.
                if ($displayInfo) {
                    $raised = $amount->setValue($project->funded)->formatCurrency();

                    $today = new Crowdfunding\Date();
                    $daysLeft = $today->calculateDaysLeft($project->funding_days, $project->funding_start, $project->funding_end);

                    $math = new Prism\Math();
                    $math->calculatePercentage($project->funded, $project->goal, 0);
                    $fundedPercent = (string)$math;
                }

                ?>
                <div class="col-md-<?php echo $itemSpan; ?>">

                    <div class="thumbnail cf-mlh-project">
                        <?php if (!empty($image)) { ?>

                            <?php if ($params->get("image_link", 0)) { ?>
                                <a href="<?php echo $projectLink; ?>">
                            <?php } ?>
                            <img src="<?php echo $image["image"]; ?>"
                                 alt="<?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?>"
                                 width="<?php echo $image["width"]; ?>" height="<?php echo $image["height"]; ?>">
                            <?php if ($params->get("image_link", 0)) { ?>
                                </a>
                            <?php } ?>

                        <?php } ?>

                        <div class="caption">
                            <h3>
                                <a href="<?php echo $projectLink; ?>">
                                    <?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?>
                                </a>
                            </h3>

                            <?php if ($displayCreator) { ?>
                            <span class="font-xxsmall">
                                <?php echo JText::sprintf("MOD_CROWDFUNDINGLATEST_BY_S", $profileName); ?>
                            </span>
                            <?php } ?>

                            <?php if (!empty($displayDescription)) { ?>
                                <p><?php echo htmlspecialchars($description, ENT_QUOTES, "UTF-8"); ?></p>
                            <?php } ?>
                        </div>

                        <?php if ($displayInfo) { ?>
                        <div class="cf-mlh-caption-info absolute-bottom">
                            <?php echo JHtml::_("crowdfunding.progressbar", $fundedPercent, $daysLeft, $project->funding_type); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="bolder"><?php echo $fundedPercent; ?>%</div>
                                    <div class="text-uppercase"><?php echo JText::_("MOD_CROWDFUNDINGLATEST_FUNDED"); ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bolder"><?php echo $raised; ?></div>
                                    <div class="text-uppercase"><?php echo JText::_("MOD_CROWDFUNDINGLATEST_RAISED"); ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bolder"><?php echo $daysLeft; ?></div>
                                    <div class="text-uppercase"><?php echo JText::_("MOD_CROWDFUNDINGLATEST_DAYS_LEFT"); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    <?php } ?>
</div>