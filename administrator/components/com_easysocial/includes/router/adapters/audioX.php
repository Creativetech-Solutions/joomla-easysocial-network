<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class SocialRouterAudio extends SocialRouterAdapter
{
	/**
	 * Constructs the points urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function build(&$menu, &$query)
	{
		$segments = array();

		// Linkage to clusters
		if (isset($query['uid']) && isset($query['type']) && ($query['type'] != 'user')) {

            $addExtraSegments = true;

            // we need to determine if we need to add below segments or not
            if (isset($query['Itemid'])) {
                $xMenu = JFactory::getApplication()->getMenu()->getItem($query['Itemid']);

                if ($xMenu) {
                    $xquery = $xMenu->query;
                    $xView = $query['type'] == 'group' ? 'groups' : 'events';

                    if ($xquery['view'] == $xView && isset($xquery['layout']) && $xquery['layout'] == 'item' && isset($xquery['id'])) {
                        $xId = (int) $xquery['id'];
                        $tId = (int) $query['uid'];
                        if ($xId == $tId) {
                            $addExtraSegments = false;
                        }
                    }
                }
            }

			$type = $query['type'];

            if ($addExtraSegments) {
                $segments[] = 'user';
                //$segments[] = $this->translate('audio_' . $type . '_LAYOUT_ITEM');
                $segments[] = $query['uid'];
            }

			unset($query['uid']);
			unset($query['type']);
		}

		// If there is a menu but not pointing to the profile view, we need to set a view
		if ($menu && $menu->query['view'] != 'audio') {
			$segments[]	= $this->translate($query['view']);
		}

		// If there's no menu, use the view provided
		if (!$menu) {
			$segments[]	= $this->translate($query['view']);
		}

        // We also need to insert the uid and type if the videos belong to a user.
        if (isset($query['uid']) && isset($query['type']) && $query['type'] == 'user') {

            $type = $query['type'];

            $segments[] = $type;
            $segments[] = $query['uid'];

            unset($query['uid']);
            unset($query['type']);            
        }

		// Get available variables
		$layout = isset($query['layout']) ? $query['layout'] : '';

		// Video id
		if (isset($query['id'])) {
			$segments[] = $query['id'];

			unset($query['id']);
		}

		// Layout
		if (isset($query['layout'])) {
			$segments[] = $this->translate('audio_layout_' . $layout);
			unset($query['layout']);
		}

		unset($query['view']);

		return $segments;
	}

	/**
	 * Translates the SEF url to the appropriate url
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	An array of url segments
	 * @return	array 	The query string data
	 */
	public function parse(&$segments)
	{
        $vars = array();


        $app = JFactory::getApplication();
        $menu = $app->getMenu();

        // Get active menu
        $activeMenu = $menu->getActive();

        // For videos on group pages, we need to parse it differently as it was composed differently with a menu id on the site
        // The activemenu MUST have the appropriate query data
        if ($activeMenu && isset($activeMenu->query['view']) && isset($activeMenu->query['layout']) && isset($activeMenu->query['id'])) {

            // Since there is parts of the group in the menu parameters, we can safely assume that the user is viewing a group item page.
            if (($activeMenu->query['view'] == 'groups' || $activeMenu->query['view'] == 'events') && $activeMenu->query['layout'] == 'item' && $activeMenu->query['id']) {
                $uid = $activeMenu->query['id'];

                // we need to re-arrange the segments to simulate the groups videos.
                //
                // we need to remove the 1st element 1st so that we can prepend whatever uid / type we need for the group.
                $firstSegment = array_shift($segments);

                // here we add the type and uid. do not re-arrange the sequence. do so will affect the segment index in parsing at later on.
                $clusterType = $activeMenu->query['view'] == 'groups' ? 'group' : 'event';
                array_unshift($segments, $clusterType, $uid); // DO NOT REARRANGE!

                // now we add back the first element;
                array_unshift($segments, $firstSegment);
            }
        }

        $total = count($segments);

		// By default this view is going to be videos
		$vars['view'] = 'audio';

		$layouts = array($this->translate('audio_layout_default'), $this->translate('audio_layout_item'));

		// videos/id/item
		if (count($segments) == 3 && $segments[2] == $this->translate('audio_layout_item')) {
			$vars['layout']	= 'item';
			$vars['id'] = $this->getIdFromPermalink($segments[1]);

			return $vars;
		}

		// videos/form
		if (count($segments) == 2 && $segments[1] == $this->translate('audio_layout_default')) {
			$vars['layout'] = 'default';

			return $vars;
		}
		
		if (count($segments) == 2 && $segments[1] == $this->translate('audio_layout_form')) {
			$vars['layout'] = 'form';

			return $vars;
		}

		// videos/[type]/[uid]/[id]/form
		if (count($segments) == 5 && $segments[4] == $this->translate('audio_layout_default')) {
			$vars['type'] = $segments[1];
			$vars['uid'] = $segments[2];
			$vars['id'] = $segments[3];
			$vars['layout'] = 'default';
			return $vars;
		}

		// videos/[type]/[uid]/[id]/[item]
		if (count($segments) == 5 && $segments[4] == $this->translate('audio_layout_item')) {
			$vars['type'] = $segments[1];
			$vars['uid'] = $segments[2];
			$vars['id'] = $segments[3];
			$vars['layout'] = 'item';
			return $vars;
		}

		// videos/[type]/[uid]/form
		if (count($segments) == 4 && $segments[3] == $this->translate('audio_layout_default')) {
			$vars['type'] = $segments[1];
			$vars['uid'] = $segments[2];
			$vars['layout'] = 'default';
			return $vars;
		}

		// videos/[type]/[uid]
		if (count($segments) == 3) {
			$vars['type'] = $segments[1];

			if ($vars['type'] == 'user') {
				$vars['uid'] = $this->getUserId($segments[2]);
			} else {
				$vars['uid'] = $this->getIdFromPermalink($segments[2]);
			}
		}

		return $vars;
	}
}
