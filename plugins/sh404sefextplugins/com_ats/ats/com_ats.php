<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die();

/**
 * This bit is sh404SEF code. All these magic variables? They come from the context which includes us.
 */
// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;

$sefConfig      = Sh404sefFactory::getConfig();
$shLangName     = '';
$shLangIso      = '';
$title          = array();
$shItemidString = '';
$dosef          = shInitializePlugin($lang, $shLangName, $shLangIso, $option);

if ($dosef == false)
{
	return;
}

// ------------------  standard plugin initialize function - don't change ---------------------------

// No FOF3? No SEF URLs. Also ATS will crash anyway.
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	return;
}

require_once JPATH_SITE . '/components/com_ats/router.php';


if (!function_exists('shAtsMenuName'))
{
	function shAtsMenuName($task, $Itemid, $option, $shLangName)
	{

		$shAtsDownloadName = shGetComponentPrefix($option);

		if (empty($shAtsDownloadName))
		{
			$shAtsDownloadName = getMenuTitle($option, $task, $Itemid, null, $shLangName);
		}

		if (empty($shAtsDownloadName) || $shAtsDownloadName == '/')
		{
			$shAtsDownloadName = 'AkeebaTicketSystem';
		}

		// Yannick says don't pre-process the segments...
		/*
		$sefConfig         = Sh404sefFactory::getConfig();
		return str_replace('.', $sefConfig->replacement, $shAtsDownloadName);
		*/

		return $shAtsDownloadName;
	}
}

if (!function_exists('shAtsTranslateView'))
{
	function shAtsTranslateView($view)
	{
		$viewMap = [
			'assignedticket'  => 'AssignedTicket',
			'assignedtickets' => 'AssignedTickets',
			'bucket'          => 'Bucket',
			'buckets'         => 'Buckets',
			'bucketreply'     => 'Bucket',
			'cannedreplies'   => 'CannedReplies',
			'categories'      => 'Categories',
			'juser'           => 'Juser',
			'jusers'          => 'Jusers',
			'latests'         => 'Latests',
			'latest'          => 'Latest',
			'managernote'     => 'ManagerNote',
			'managernotes'    => 'ManagerNotes',
			'my'              => 'My',
			'mies'            => 'Mies',
			'newticket'       => 'NewTicket',
			'newtickets'      => 'NewTickets',
			'post'            => 'Post',
			'posts'           => 'Posts',
			'ticket'          => 'Ticket',
			'tickets'         => 'Tickets',
		];

		$lowercaseView = strtolower($view);

		if (!array_key_exists($lowercaseView, $viewMap))
		{
			return $view;
		}

		return $viewMap[$lowercaseView];
	}
}

// sh404SEF sets variables named after the request parameters (a very insecure thing to do!). We need to reconstruct
// them into an array. This means one thing: lots and lots and lots of if-blocks...
$atsQuery = array();

if (isset($Itemid))
{
	$atsQuery['Itemid'] = $Itemid;
}

if (isset($option))
{
	$atsQuery['option'] = $option;
}

if (isset($view))
{
	$atsQuery['view'] = shAtsTranslateView($view);
}

if (isset($task))
{
	$atsQuery['task'] = $task;
}

if (isset($format))
{
	$atsQuery['format'] = $format;
}

if (isset($lang))
{
	$atsQuery['lang'] = $lang;
}

if (isset($category))
{
	$atsQuery['category'] = $category;
}

if (isset($id))
{
	$atsQuery['id'] = $id;
}

// Now let's keep track of which keys are set so we can later see which query string parameters we used in the router
// and call shRemoveFromGETVarsList on them.
$atsOriginalKeys = array_keys($atsQuery);

// These common URL parameters are always removed
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
shRemoveFromGETVarsList('view');
shRemoveFromGETVarsList('format');

if (isset($Itemid))
{
	shRemoveFromGETVarsList('Itemid');
}

global $shGETVars;

// Begin by adding the component's name / common prefix in the URL
$title[] = shAtsMenuName($task, $Itemid, $option, $shLangName);

// Use the ATS router to generate the URL
$atsSegments = AtsBuildRoute($atsQuery);

/**
 * Joomla expects the final segment for tickets to be in the form id:alias e.g. 123:something-or-another. Joomla SEF
 * will then convert it to 123-something-or-another. Basically, the colon tells Joomla where the ID stops and where the
 * alias –which can be ignored– starts. sh404SEF on the other hand will *strip* the colon resulting in the fugly segment
 * 123something-or-another which is just plain asinine. So we have to blatantly ignore Yannick saying that we shouldn't
 * pre-process the segments, converting colons to dashes so we can have the same URL as what Joomla itself would produce
 */
$atsSegments = array_map(function ($segment) {
	return str_replace(':', '-', $segment);
}, $atsSegments);

// Finally, let's merge the component's name / common prefix with the segments returned by ATS' router.
$title = array_merge($title, $atsSegments);

// Find which query string parameters were removed from $atsQuery and call shRemoveFromGETVarsList on them.
$atsRemainingKeys = array_keys($atsQuery);
$atsRemoveKeys = array_diff($atsOriginalKeys, $atsRemainingKeys);

if (!empty($atsRemoveKeys))
{
	array_map('shRemoveFromGETVarsList', $atsRemoveKeys);
}

/**
 * I have no idea why Yannick has an if-block. I mean, if that was false we returned in the initialization?!
 * Anyway, he says we shouldn't touch that code so I'm not touching it. I just find it subpar and want to make it clear
 * that this is not my code and no, I'm not stupid or drunk.
 */
// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef)
{
	$string = shFinalizePlugin($string, $title, $shAppendString, $shItemidString,
		(isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
		(isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------