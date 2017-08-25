<?php
/**
 * @package         SourceCoast Extensions
 * @copyright (c)   2009-2015 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v7.1.1
 * @build-date      2016/11/18
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('sourcecoast.openGraph');

class OpenGraphPlugin extends JPlugin
{
    var $pluginName;
    var $extensionName;
    var $supportedActions;
    var $supportedObjects;
    var $jfbcOgActionModel;
    var $jfbcOgObjectModel;
    var $supportedComponents;
    var $supportedAutopostLabel;
    var $supportedAutopostTypes;
    var $jfbcLibrary;
    var $object;
    var $db;
    var $setsDefaultTags;

    private $openGraphLibrary;

    function __construct(&$subject, $config)
    {
        $this->pluginName = $config['name'];
        $this->extensionName = $config['name']; // Should be overridden by the plugin itself.
        if (class_exists('JFBCFactory'))
        {
            JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_jfbconnect/models');
            $this->jfbcOgActionModel = JModelLegacy::getInstance('OpenGraphAction', 'JFBConnectModel');
            $this->jfbcOgObjectModel = JModelLegacy::getInstance('OpenGraphObject', 'JFBConnectModel');
            $this->jfbcLibrary = JFBCFactory::provider('facebook');
        }

        if (class_exists('OpenGraphLibrary'))
            $this->openGraphLibrary = OpenGraphLibrary::getInstance();

        $this->hasDefaultTags = false;
        $this->db = JFactory::getDBO();

        parent::__construct($subject, $config);

        $this->init();
    }

    protected function init()
    {
    }

    /******* Triggers ******/
    public function onOpenGraphGetPlugins()
    {
        return $this;
    }

    public function onOpenGraphAfterRoute()
    {
        if (count($this->supportedActions) > 0)
        {
            if ($this->jfbcLibrary->userIsConnected())
                $this->setupDefinedActions();
        }
    }

    public function onOpenGraphAfterDispatch()
    {
        if ($this->inSupportedComponent())
        {
            $juri = JURI::getInstance();
            $url = $juri->toString();
            $queryVars = $this->jfbcOgActionModel->getUrlVars($url);
            $this->object = $this->findObjectType($queryVars);

            // Set the OG tags if this plugin has an object type set *or* will set OG tags even without objects set by the admin
            if ($this->object || $this->setsDefaultTags)
                $this->setOpenGraphTags();

            if ($this->object)
            {
                $this->setTypeTag();

                // If user is connected, see if there are any actions that should be setup
                if ($this->jfbcLibrary->userIsConnected())
                {
                    $this->setupTimedActions();
                    $this->setupPageActions();
                }
            }
        }
    }

    public function onOpenGraphAJAXAction($actionId, $objectId, $url)
    {
        $action = $this->jfbcOgActionModel->getAction($actionId);
        return $this->triggerAction($action, $url);
    }

    public function onOpenGraphFindObjectType($url)
    {
        $queryVars = $this->jfbcOgActionModel->getUrlVars($url);
        return $this->findObjectType($queryVars);
    }

    public function onOpenGraphGetBestImage($article)
    {
        return $this->getBestImage($article);
    }

    public function onOpenGraphGetBestText($article)
    {
        return $this->getBestText($article);
    }

    /******** End triggers ********/

    /******** Object Calls ********/
    protected function getObjects($type)
    {
        // Can we make this more efficient to load all the plugin objects once, and then just pick off the 'name' types
        // when we need them?
        return $this->jfbcOgObjectModel->getPluginObjects($this->pluginName, $type);
    }

    protected function addSupportedAction($systemName, $displayName)
    {
        $this->supportedActions[$systemName] = $displayName;
    }

    protected function addSupportedObject($systemName, $displayName)
    {
        $this->supportedObjects[$systemName] = $displayName;
    }

    private function inSupportedComponent()
    {
        // If none are defined, plugin always fired
        if (!$this->supportedComponents)
            return true;

        if (in_array(JFactory::getApplication()->input->getCmd('option'), $this->supportedComponents))
            return true;

        return false;
    }

    protected function addOpenGraphTag($name, $value, $isFinal)
    {
        $this->openGraphLibrary->addOpenGraphTag($name, $value, $isFinal, PRIORITY_NORMAL, "Open Graph - " . ucfirst($this->pluginName)." Plugin");
    }

    protected function skipOpenGraphTag($name)
    {
        $this->openGraphLibrary->blockTag($name, $this->pluginName);
    }

    protected function getDefaultObject($name)
    {
        $object = new ogObject();
        $object->loadDefaultObject($this->pluginName, $name);

        return $object;
    }

    // Setup any extra Open Graph tags specific to this object (title, image, description, video, etc)
    protected function setOpenGraphTags()
    {
        // Should be overridden by the plugin
    }

    // Actions that are triggered after a timeout while viewing the page.
    protected function setupTimedActions()
    {
        $doc = JFactory::getDocument();
        $actionAdded = false;
        foreach ($this->object->getAssociatedActions() as $action)
        {
            if ($action->params->get('og_auto_type') == "page_load" && $action->params->get('og_auto_timer') > 0)
            {
                $key = $this->getUniqueKey($this->getCurrentURL());
                $jq = JFBCFactory::config()->getSetting('jquery_load') ? 'jfbcJQuery' : 'jQuery';
                if ($action->actionReady($key))
                {
                    $actionAdded = true;
                    // The space between { jfbc.opengraph is required. Otherwise, our system plugin will strip it thinking it's a {JFBCxyz} tag.
                    // Not the perfect solution, but it works..
                    $doc->addScriptDeclaration($jq . "(document).ready(function () {" .
                        "setTimeout(function(){ jfbc.opengraph.triggerAction('" . $action->id . "','" . $this->getCurrentURL() . "');}, " . ($action->params->get('og_auto_timer') * 1000) . ");" .

                        "});"
                    );
                }
                else
                {
                    $data = $action->getLastPublished($key);
                    if ($data)
                    {
                        JFBConnectUtilities::loadLanguage('com_jfbconnect');
                        $actionAdded = true;
                        $date = new JDate($data->created);
                        $doc->addScriptDeclaration($jq . "(document).ready(function () {" .
                            "setTimeout(function(){jfbc.opengraph.actionPopup('<span class=\"ogMessage\">" .
                            JText::sprintf("COM_JFBCONNECT_OPENGRAPH_ACTION_EXISTS", $date->format(JText::_('DATE_FORMAT_LC4'))) . "</span>" .
                            '<span class="ogOptions"><a href="' . JRoute::_('index.php?option=com_jfbconnect&task=opengraph.userdelete&actionid=' . $data->id) . '" target="_blank">' . JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_DELETE') . '</a>' .
                            ' | <a href="' . JRoute::_('index.php?option=com_jfbconnect&view=opengraph&layout=activity') . '" target="_blank">' . JText::_('COM_JFBCONNECT_OPENGRAPH_ACTION_SEE_ALL') . '</a></span>' .
                            "')})" .
                            "});"
                        );
                    }
                }
            }
        }
        if ($actionAdded)
        {
            // Include our CSS file for styling the popup
            JFBCFactory::addStylesheet('jfbconnect.css');
        }
    }

    // Actions that are triggered immediately just by viewing the page.
    protected function setupPageActions()
    {
        foreach ($this->object->getAssociatedActions() as $action)
        {
            if ($action->params->get('og_auto_type') == "page_load" && $action->params->get('og_auto_timer') == 0)
            {
                if ($action->actionReady($this->getUniqueKey($this->getCurrentURL())))
                    $this->triggerAction($action);
            }
        }
    }

    protected function setupDefinedActions()
    {
        // get any defined action instances
        foreach ($this->supportedActions as $supportedAction)
        {
            $actions = $this->jfbcOgActionModel->getActionsOfType($this->pluginName, $supportedAction);
            foreach ($actions as $action)
            {
                $this->checkActionAfterRoute($action);
            }
        }
    }

    protected function checkActionAfterRoute($action)
    {
        // Override by plugin
    }

    protected function triggerAction($action, $url = null)
    {
        if (!$url)
            $url = $this->getCurrentURL();

        $uniqueKey = $this->getUniqueKey($url);
        $this->jfbcOgActionModel->triggerAction($action, $url, null, $uniqueKey);
    }

    // getUniqueKey
    // Used for generating a unique key per URL or 'item'. Used to ensure that certain actions aren't repeated within the
    // set action interval. For example, reading an article should only be triggered once (or at most every day).
    // By default, will decompose the URL to non-SEF and look for an id query string param.
    // If none exists, will use the MD5 hash of the full path/query string of URL.
    protected function getUniqueKey($url)
    {
        return $this->jfbcOgActionModel->getUniqueKey($url);
    }

    protected function setTypeTag()
    {
        $this->addOpenGraphTag('type', $this->object->getObjectPath(), true);
    }

    private function getCurrentURL()
    {
        $uri = JURI::getInstance();
        return $uri->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query'));
    }

    protected function getBestImage($article) { return null; }

    protected function getBestText($article) { return null; }

    /******** Get Images and Descriptions ********/

    protected function getFirstCategoryText($category, $numCharacters = 100, $socialGraphFirstText = '1')
    {
        $categoryText = '';
        if (isset($category->description))
            $categoryText = $this->getSelectedText($category->description, $socialGraphFirstText, $numCharacters);
        return $categoryText;
    }

    protected function getFirstArticleText($article, $numCharacters = 100, $socialGraphFirstText = '1')
    {
        $articleText = '';
        if (isset($article->introtext) && trim(strip_tags($article->introtext)) != "")
        {
            $articleText = $article->introtext;
        } else if (isset($article->text) && trim(strip_tags($article->text)) != "")
        {
            $articleText = $article->text;
        } else if (isset($article->fulltext) && trim(strip_tags($article->fulltext)) != "")
        {
            $articleText = $article->fulltext;
        }

        $articleText = $this->getSelectedText($articleText, $socialGraphFirstText, $numCharacters);

        return $articleText;
    }

    protected function getSelectedText($contentText, $socialGraphFirstText, $numCharacters)
    {
        $articleText = JFBConnectUtilities::trimNBSP($contentText);
        $articleText = strip_tags($articleText);
        $articleText = preg_replace('/\s+/', ' ', $articleText);
        $articleText = str_replace('{K2Splitter}', '', $articleText);
        JFBConnectUtilities::stripSystemTags($articleText, 'JFBC');
        JFBConnectUtilities::stripSystemTags($articleText, 'JLinked');
        JFBConnectUtilities::stripSystemTags($articleText, 'SC');
        JFBConnectUtilities::stripSystemTags($articleText, 'loadposition');
        $articleText = trim($articleText);

        $addEllipsis = false;
        if ($socialGraphFirstText == '1')
        {
            $addEllipsis = strlen($articleText) > $numCharacters;

            if (function_exists('mb_substr'))
                $articleText = mb_substr($articleText, 0, $numCharacters, 'UTF-8');
            else
                $articleText = substr($articleText, 0, $numCharacters);
        } else if ($socialGraphFirstText == '2')
        {
            if (function_exists('mb_split'))
                $parts = mb_split('\s+', $articleText); /* Note: mb_split does not use pattern delimiters of slashes before and after */
            else
                $parts = preg_split('/\s+/', $articleText);
            $selParts = array_slice($parts, 0, $numCharacters);
            $articleText = implode(" ", $selParts);
            $addEllipsis = count($parts) > $numCharacters;
        } else
            $articleText = '';

        if ($addEllipsis)
            $articleText .= '...';

        return $articleText;
    }

    protected function getFirstImage($article)
    {
        if (isset($article->text))
            $articleText = $article->text;
        else
            $articleText = $article->introtext . $article->fulltext;

        $fullImagePath = $this->getFirstImageFromText($articleText);
        return $fullImagePath;
    }

    protected function getFirstImageFromText($text)
    {
        $fullImagePath = '';
        if (preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', $text, $matches))
        {
            $fullImagePath = $this->getImageLink($matches[1][0]);
        }
        return $fullImagePath;
    }

    protected function getImageLink($path)
    {
        if ($path)
        {
            $juri = JURI::getInstance();
            $basePath = str_replace(array($juri->getScheme() . "://", $juri->getHost()), "", $juri->base());

            if (strpos($path, '//') === 0)
            {
                return $juri->getScheme() . ':' . $path;
            } else if (strpos($path, $basePath) === 0)
            {
                $path = substr($path, strlen($basePath));
                $path = $juri->base() . $path;
            } else if (strpos($path, "http") !== 0)
                $path = $juri->base() . $path;
        }
        return $path;
    }

    /******** Autopost ********/
    public function openGraphProfilesGetPlugins()
    {
        if($this->supportedAutopostTypes)
            return $this;
    }

    public function openGraphUpdatePending($articleId, $link, $ext)
    {
        // Override by plugin
    }

    public function onContentBeforeSave($context, $article, $isNew)
    {
        // Override by plugin
    }

    public function onContentChangeState($context, $pks, $value)
    {
        // Override by plugin
    }

    public function getAutopostMessage($objectType, $id)
    {
        // Override by plugin
        return '';
    }

    public function isArticlePublishPending($publish_up)
    {
        $currentDate = JFactory::getDate();
        $publishDate = new JDate($publish_up);

        $interval = $publishDate->diff($currentDate);

        return ($interval->invert == 1);
    }

    public function isArticleSpecial($accessType)
    {
        return $accessType == '3' || $accessType == '6'; //Special or Super User
    }

    public function getPublishedState($itemId, $layout)
    {
        /* We don't check the channel, so new channels that are created don't have
        updated content posted to them
        At the time of initial publishing, if content hasn't been autoposted before, it will go to all channels configured
        */
        $ext = $this->supportedComponents[0];
        $query = $this->db->getQuery(true);
        $query->select('*')
            ->from($this->db->qn('#__jfbconnect_autopost_activity'))
            ->where($this->db->qn('item_id') . '=' . $this->db->q($itemId) . ' AND ' .
                $this->db->qn('layout') . '=' . $this->db->q($layout) . ' AND ' .
                $this->db->qn('ext') . '=' . $this->db->q($ext) . ' AND ' .
                $this->db->qn('status') . '<>' . $this->db->q(0)
            );
        $this->db->setQuery($query);
        $activity = $this->db->loadObject();
        return $activity;
    }

    public function autopublish($ogType, $articleId, $link, $isPending = false)
    {
        //Need to make an AJAX call to front-end to get the SEF URL
        $http = JHttpFactory::getHttp();
        $sef = $http->get(JUri::root() . 'index.php?option=com_jfbconnect&task=ajax.sef&url=' . base64_encode($link));

        if($sef->code == 303)
        {
            $redirect = $sef->headers['Location'];
            $http2 = JHttpFactory::getHttp();
            $sef = $http2->get($redirect);
        }

        if ($sef && $sef->code == 200)
            $link = $sef->body;

        $objectType = $this->getLayout($ogType);

        $activity = $this->getPublishedState($articleId, $objectType);

        if($activity && $activity->status == 2)
        {
            $this->removePublish($ogType, $articleId);
            $activity = null;
        }
        if($activity == null)
        {
            // Get Mappings from Autopost table for the given object
            $query = $this->db->getQuery(true);
            $query->select('*')
                ->from($this->db->qn('#__jfbconnect_autopost'))
                ->where('(' . $this->db->qn('opengraph_type') . '=' . $this->db->q($this->pluginName) . ')', 'OR');
            if($ogType)
            {
                if(is_array($ogType))
                {
                    foreach($ogType as $type)
                        $query->where('(' . $this->db->qn('opengraph_type') . '=' . $this->db->q($type->id) . ')');
                }
                else
                    $query->where('(' . $this->db->qn('opengraph_type') . '=' . $this->db->q($ogType->id) . ')');
            }


            $this->db->setQuery($query);
            $mappings = $this->db->loadObjectList();

            $message = htmlspecialchars_decode($this->getAutopostMessage($objectType, $articleId));

            //Perform the post and save result
            $mappingsPerformed = array();
            foreach ($mappings as $mapping)
            {
                $channelId = $mapping->channel_id;
                if(!in_array($channelId, $mappingsPerformed))
                {
                    $this->performPost($channelId, $objectType, $articleId, $message, $link, $mapping->id, $isPending);
                    $mappingsPerformed[] = $channelId;
                }
            }
        }
    }

    public function updatePending($ogType, $articleId, $link)
    {
        //Delete pending and perform auto-publish again
        $this->removePublish($ogType, $articleId);
        $this->autopublish($ogType, $articleId, $link);
    }

    public function removePublish($ogType, $articleId)
    {
        $objectType = $this->getLayout($ogType);
        $ext = $this->supportedComponents[0];
        $query = $this->db->getQuery(true);
        $query->delete($this->db->qn("#__jfbconnect_autopost_activity"))
            ->where($this->db->qn('item_id') . '=' . $this->db->q($articleId) . ' AND ' .
                $this->db->qn('layout') . '=' . $this->db->q($objectType) . ' AND ' .
                $this->db->qn('ext') . '=' . $this->db->q($ext) . ' AND ' .
                $this->db->qn('status') . '=' . $this->db->q(2)
            );
        $this->db->setQuery($query);
        $this->db->execute();
    }

    private function getLayout($ogType)
    {
        if($ogType)
        {
            if(is_array($ogType))
                $objectType = $ogType[0]->system_name;
            else
                $objectType = $ogType->system_name;
        }
        else
        {
            $ext = $this->supportedComponents[0];
            if($ext=='com_content')
                $objectType = 'article';
            else if($ext == 'com_k2')
                $objectType = 'item';
            else if($ext == 'com_easyblog')
                $objectType = 'post';
            else //JomSocial
                $objectType = JRequest::getCmd('view');
        }


        return $objectType;
    }

    public function performPost($id, $layout, $articleId, $message, $link, $mappingId, $isPending)
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/tables/');
        $row = JTable::getInstance('Channel', 'Table');
        $row->load($id);

        if($row->published)
        {
            $options = new JRegistry();
            $options->loadObject($row->attribs);
            $provider = JFBCFactory::provider($row->provider);
            $channel = $provider->channel($row->type, $options);

            $post = new JRegistry();
            $post->set('message', $message);
            $post->set('link', $link);

            $channel->post($post, AP_TYPE_AUTOPUBLISH, $isPending, $mappingId, $articleId, $this->supportedComponents[0], $layout);
        }
    }
}