<?php
/**
 * @package         CrowdfundingGallery
 * @subpackage      Plugins
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPLv3
 */

// no direct access
defined('_JEXEC') or die;

jimport('Crowdfunding.init');
jimport('Magicgallery.init');

/**
 * Crowdfunding Gallery Plugin
 *
 * @package        CrowdfundingGallery
 * @subpackage     Plugins
 */
class plgCrowdfundingGallery extends JPlugin
{
    protected $autoloadLanguage = true;

    /**
     * @var JApplicationSite
     */
    protected $app;

    protected $version = '1.0';

    /**
     * This method prepares a code that will be included to step 'Extras' on project wizard.
     *
     * @param string    $context This string gives information about that where it has been executed the trigger.
     * @param object    $item    A project data.
     * @param Joomla\Registry\Registry $params  The parameters of the component
     *
     * @return null|string
     */
    public function onExtrasDisplay($context, &$item, &$params)
    {
        if (strcmp('com_crowdfunding.project.extras', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }
        
        if (isset($item->user_id) and !$item->user_id) {
            return null;
        }

        // Check for installed component Magic Gallery.
        if (!JComponentHelper::isInstalled('com_magicgallery')) {
            return null;
        }

        $mediaFolder = CrowdfundingHelper::getImagesFolder($item->user_id);
        $mediaUri    = CrowdfundingHelper::getImagesFolderUri($item->user_id);

        $keys = array(
            'object_id' => (int)$item->id,
            'user_id'   => (int)$item->user_id,
            'extension' => 'com_crowdfunding'
        );

        $options = array(
            'load_entities' => true,
            'entity_state' => Prism\Constants::PUBLISHED
        );

        $gallery = new Magicgallery\Gallery\Gallery(JFactory::getDbo());
        $gallery->load($keys, $options);

        if (!$gallery->getId()) {
            $gallery->bind($keys);
            $gallery->setParam('path', $mediaFolder);
            $gallery->setParam('uri', $mediaUri);

            $gallery
                ->setTitle($item->title)
                ->setDescription(JText::sprintf('PLG_CROWDFUNDING_GALLERY_GALLERY_DESCRIPTION_S', $item->title))
                ->setCategoryId($this->params->get('category_id'))
                ->setStatus($this->params->get('gallery_default_status', Prism\Constants::UNPUBLISHED));
            
            $gallery->store();
        }

        $files = $gallery->getEntities();

        // Load jQuery
        JHtml::_('jquery.framework');
        JHtml::_('Prism.ui.pnotify');
        JHtml::_('Prism.ui.fileupload');
        JHtml::_('Prism.ui.joomlaHelper');

        // Include the translation of the confirmation question.
        JText::script('PLG_CROWDFUNDING_GALLERY_DELETE_QUESTION');

        // Get the path for the layout file
        $path = JPath::clean(JPluginHelper::getLayoutPath('crowdfunding', 'gallery'));

        // Render the login form.
        ob_start();
        include $path;
        $html = ob_get_clean();

        return $html;
    }
}
