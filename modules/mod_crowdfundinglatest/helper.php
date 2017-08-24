<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

class CrowdfundingLatestModuleHelper
{
    /**
     * @param string $type
     * @param object $project
     * @param Joomla\Registry\Registry $componentParams
     * @param string $imagesDirectory
     *
     * @return array
     */
    public static function getImage($type, $project, $componentParams, $imagesDirectory)
    {
        $image = array();

        // Prepare image
        switch ($type) {

            case "large":
                $image["image"]  = (!$project->image) ? "media/com_crowdfunding/images/no_image_50x50.png" : $imagesDirectory . "/" . $project->image;
                $image["width"]  = $componentParams->get("image_width", 200);
                $image["height"] = $componentParams->get("image_height", 200);
                break;

            case "small":
                $image["image"]  = (!$project->image_small) ? "media/com_crowdfunding/images/no_image_50x50.png" : $imagesDirectory . "/" . $project->image_small;
                $image["width"]  = $componentParams->get("image_small_width", 100);
                $image["height"] = $componentParams->get("image_small_height", 100);
                break;


            case "square":
                $image["image"]  = (!$project->image_square) ? "media/com_crowdfunding/images/no_image_50x50.png" : $imagesDirectory . "/" . $project->image_square;
                $image["width"]  = $componentParams->get("image_square_width", 50);
                $image["height"] = $componentParams->get("image_square_height", 50);
                break;

            default: // none

                break;
        }

        return $image;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public static function getProjects($limit)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select(
                "a.title, a.short_desc, a.image, a.image_small, a.image_square, " .
                "a.goal, a.funded, a.funding_start, a.funding_end, a.funding_days, " .
                "a.user_id, a.funding_type, " .
                $query->concatenate(array("a.id", "a.alias"), ":") . " AS slug, " .
                $query->concatenate(array("b.id", "b.alias"), ":") . " AS catslug, " .
                "c.name AS user_name"
            )
            ->from($db->quoteName("#__crowdf_projects", "a"))
            ->innerJoin($db->quoteName("#__categories", "b") . " ON a.catid = b.id")
            ->leftjoin($db->quoteName("#__users", "c") . " ON a.user_id = c.id")
            ->where("a.published = 1")
            ->where("a.approved = 1")
            ->order("a.funding_start DESC");

        if (!$limit) {
            $limit = 5;
        }

        $db->setQuery($query, 0, (int)$limit);

        $results = $db->loadObjectList();

        if (!$results) {
            $results = array();
        }

        return $results;
    }
}