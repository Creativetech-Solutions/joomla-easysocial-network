<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2015 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v7.1.1
 * @build-date      2016/11/18
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modJFBCSocialShareHelper
{
    function addPxToString($amount)
    {
        if(strpos($amount, "px")===false)
            $amount .= "px";
        return $amount;
    }
}