<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2015 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @build-date      2016/11/18
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('sourcecoast.articleContent');
jimport('sourcecoast.openGraphPlugin');

class plgOpenGraphJomSocial extends OpenGraphPlugin
{
    protected function init()
    {
        $this->extensionName = "JomSocial";
        $this->supportedComponents[] = 'com_community';
        $this->supportedAutopostLabel = "Group and Event";
        $this->supportedAutopostTypes[] = 'groups';
        $this->supportedAutopostTypes[] = 'events';

        // Default Open Graph tags are set for profile, album, and group pages even if the admin hasn't defined object types for those pages
        $this->setsDefaultTags = true;

        // Define all types of pages this component can create and would be 'objects'
        $this->addSupportedObject("Group", "groups");
        $this->addSupportedObject("Photo Album", "album");
        $this->addSupportedObject("Profile", "profile");
        $this->addSupportedObject("Event", "events");

        // Add actions that aren't passive (commenting, voting, etc).
        // Things that trigger just by loading the page should not be defined here unless extra logic is required
        // ie. Don't define reading an article
        $this->addSupportedAction("Album Image Upload", "image_upload");
        $this->addSupportedAction("Join Group", "group_join");
        $this->addSupportedAction("Create Group", "group_create");
        $this->addSupportedAction("Join Event", "event_join");
        $this->addSupportedAction("Create Event", "event_create");

        // Slick way to hook into JomSocial activity calls to do our actions. Basically, we're faking, from this point on, that we are a community plugin
        // Joomla doesn't check the type, but JomSocial does, so this works great for us!
        $this->_type = "community";
    }

    protected function findObjectType($queryVars)
    {
        // Setup Object type for page
        $task = array_key_exists('task', $queryVars) ? $queryVars['task'] : '';
        $view = array_key_exists('view', $queryVars) ? $queryVars['view'] : '';
        $type = $task == "photo" ? 'album' : $view;
        $type = ($view == 'featured' ? $task : $type);

        $objectTypes = $this->getObjects($type);
        $object = null;

        if ($view == 'profile' ||
            ($view == 'groups' && $task == 'viewgroup') ||
            ($view == 'photos' && $task == "photo") ||
            ($view == 'events' && $task == 'viewevent') ||
            ($view == 'featured' && $task == 'groups') ||
            ($view == 'featured' && $task == 'events')

        )
        {
            // If there's an object, that's the one we want since we don't current support multiple profile types
            if (array_key_exists('0', $objectTypes))
                $object = $objectTypes[0];
        }
        return $object;
    }

    protected function setOpenGraphTags()
    {
        $view = JRequest::getCmd('view');
        $task = JRequest::getCmd('task');

        if ($view == 'profile')
        {
            // Set image from profile. Title and description are done by JFBConnect automatically
            $jspath = JPATH_ROOT . '/components/com_community';
            include_once($jspath . '/libraries/core.php');
            // Get CUser object
            $userId = JRequest::getInt('userid');
            $user = CFactory::getUser($userId);
            $avatarUrl = $user->getThumbAvatar();
            $this->addOpenGraphTag('image', $avatarUrl, false);

            // Add the canonical URL to the profile
            $uri = JURI::getInstance();
            $url = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $url .= CRoute::_('index.php?option=com_community&view=profile&userid=' . $userId, false);
            $this->addOpenGraphTag('url', $url, true);
        }
        else if ($view == 'groups' && $task == 'viewgroup')
        {
            $id = JRequest::getInt('groupid');
            $url = $this->getGroupUrl($id);

            $this->addOpenGraphTag('url', $url, false);
            //$this->addOpenGraphTag('image', $group->getAvatar('avatar'), false);
        }
        else if ($view == 'photos' && $task == "album")
        {
            $albumId = JRequest::getInt('albumid');
            $album = JTable::getInstance('Album', 'CTable');
            $album->load($albumId);
            $url = CRoute::getExternalURL($album->getURI());
            $this->addOpenGraphTag('url', $url, true);

            $image = $album->getCoverThumbPath();
            $uri = JURI::getInstance();
            $base = $uri->toString(array('scheme', 'host', 'port'));
            if(strpos($image, $base) === false)
                $image = CRoute::getExternalURL($image);
            $this->addOpenGraphTag('image', $image, false);

            $this->addOpenGraphTag('description', $album->name, false);
        }
        else if ($view == 'photos' && $task == 'photo')
        {
            $photoId = JRequest::getInt('photoid');
            $photo = JTable::getInstance('Photo', 'CTable');
            $photo->load($photoId);

            $url = 'index.php?option=com_community&view=photos&task=photo&albumid='.$photo->albumid.'&userid='.$photo->creator.'&photoid='.$photoId;
            $url = CRoute::getExternalURL($url);
            $this->addOpenGraphTag('url', $url, true);

            $image = $photo->getOriginalURI();
            $this->addOpenGraphTag('image', $image, false);

            $this->addOpenGraphTag('description', $photo->caption, false);
        }
        else if ($view == 'events' && $task == 'viewevent')
        {
            $id = JRequest::getInt('eventid');
            $event = $this->getEvent($id);
            $url = $this->getEventUrl($id);

            $this->addOpenGraphTag('url', $url, false);

            if($event->cover != '')
            {
                $coverImage = JRoute::_(JURI::root(false) . $event->cover);
                $this->addOpenGraphTag('image', $coverImage, false);
            }
        }
    }

    // Override the default key generation since JomSocial URLs don't use id=, they use albumid= or profileid=, etc
    protected function getUniqueKey($url)
    {
        $queryVars = $this->jfbcOgActionModel->getUrlVars($url);

        $task = array_key_exists('task', $queryVars) ? $queryVars['task'] : '';
        $view = array_key_exists('view', $queryVars) ? $queryVars['view'] : '';

        if ($view == "photos" && $task == "album" && array_key_exists('albumid', $queryVars))
            return $queryVars['albumid'];
        if ($view == "group" || $task == 'groups')
            return $queryVars['groupid'];
        if ($view == 'event' || $task == 'events')
            return $queryVars['eventid'];

        return parent::getUniqueKey($url);
    }

    /* Helper functions */
    private function getGroup($id)
    {
        $group = JTable::getInstance('Group', 'CTable');
        $group->load($id);
        return $group;
    }
    private function getGroupUrl($id)
    {
        //$uri = JURI::getInstance();
        //$url = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));

        //$group = $this->getGroup($id);
        //$url .= $group->getLink();

        $itemID = JRequest::getInt('Itemid');
        $url = 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$id.'&Itemid='.$itemID;
        $url = CRoute::getExternalURL($url);
        return $url;
    }

    private function getEvent($id)
    {
        $event = JTable::getInstance('Event', 'CTable');
        $event->load($id);
        return $event;
    }

    private function getEventUrl($id)
    {
        $itemID = JRequest::getInt('Itemid');
        $url = 'index.php?option=com_community&view=events&task=viewevent&eventid='.$id;
        $url = CRoute::getExternalURL($url);
        return $url;
    }

    /************* DEFINED ACTIONS CALLS *******************/
    // JomSocial has triggers for most of it's actions already. We just need to hook into them.
    // If you're using this plugin as a reference, it's best to check out the checkActionAfterRoute($action) function in other
    // Open Graph plugins to see how most defined actions are triggered

    public function onPhotoCreate($params)
    {
        $actions = $this->jfbcOgActionModel->getActionsOfType($this->pluginName, "image_upload");

        if (is_array($params))
            $photoInfo = $params[0];
        else
            $photoInfo = $params;

        foreach ($actions as $action)
        {
            $url = 'index.php?option=com_community&view=photos&task=photo&albumid='.$photoInfo->albumid.'&userid='.$photoInfo->creator.'&photoid='.$photoInfo->id;
            $url = CRoute::getExternalURL($url);

            $this->triggerAction($action, $url);
        }
    }

    public function onGroupCreate($params)
    {
        $this->triggerGroupAction($params->id, 'group_create');
        $this->tryAutopublish('groups', $params);
    }

    public function onGroupJoin($params)
    {
        $this->triggerGroupAction($params->id, 'group_join');
    }

    private function triggerGroupAction($groupId, $actionName)
    {
        $url = $this->getGroupUrl($groupId);
        $actions = $this->jfbcOgActionModel->getActionsOfType($this->pluginName, $actionName);
        foreach ($actions as $action)
        {
            $this->triggerAction($action, $url);
        }
    }

    public function onEventCreate($params)
    {
        $this->triggerEventAction($params->id, 'event_create');
        $this->tryAutopublish('events', $params);
    }

    public function onEventJoin($params)
    {
        $this->triggerEventAction($params->id, 'event_join');
    }

    private function triggerEventAction($eventId, $actionName)
    {
        $url = $this->getEventUrl($eventId);
        $actions = $this->jfbcOgActionModel->getActionsOfType($this->pluginName, $actionName);
        foreach ($actions as $action)
        {
            $this->triggerAction($action, $url);
        }
    }

    private function tryAutopublish($objectType, $params)
    {
        $ogObjects = $this->getObjects($objectType);
        $isPending = $params->published != '1';
        //If event requires mod, object will be 1 but params will be 2.
        //If group requires mod, object will be 1 but params will be 0.
        //Want this to be pending

        if($objectType == 'events')
        {
            $url = $this->getEventUrl($params->id);
        }
        else if($objectType == 'groups')
        {
            $url = $this->getGroupUrl($params->id);
        }
        $this->autopublish($ogObjects[0], $params->id, $url, $isPending);
    }

    public function getAutopostMessage($objectType, $id)
    {
        if($objectType == 'events')
        {
            $event = $this->getEvent($id);
            $message = $event->summary;
        }
        else if($objectType == 'groups')
        {
            $group = $this->getGroup($id);

            if($group->summary != '')
                $message = $group->summary;
            else if($group->description != '')
                $message = $group->description;
            else
                $message = $group->name . " created";
        }

        return $message;
    }

    public function openGraphUpdatePending($articleId, $link, $ext)
    {
        //NOTE: If moderation is required, then the only way I've found to get
        //to autopublish is to visit on the front-end once it's published
        if($ext == $this->supportedComponents[0])
        {
            $view = JRequest::getCmd('view');

            if($view == 'events')
            {
                $event = $this->getEvent($articleId);
                $this->tryAutopublish($view, $event);
            }
            else if($view == 'groups')
            {
                $group = $this->getGroup($articleId);
                $this->tryAutopublish($view, $group);
            }
        }
    }

}