<?php
/**
* @version 1.0.0
* @package RSTemplate! 1.0.0
* @copyright (C) 2007-2013 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die;

class plgSystemRSTemplate extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->_plugin = JPluginHelper::getPlugin('system', 'rstemplate');

		JFactory::getLanguage()->load('plg_system_rstemplate', JPATH_ADMINISTRATOR, null, false, false);
	}
	
	public function rs_escape($string) {
		return htmlentities($string, ENT_QUOTES, 'utf-8');
	}

	public function onAfterDispatch()
	{
		$app 	= JFactory::getApplication();
		if ($app->isAdmin()) {
			$db 	= JFactory::getDbo();
			
			$query	= $db->getQuery(true);
			$query->select('*')
				  ->from($db->qn('#__template_styles'))
				  ->where($db->qn('client_id').' = '.$db->q(0))
				  ->where($db->qn('home').' = '.$db->q(1));
			$db->setQuery($query);
			$template = $db->loadObject();
			
			// get params
			$params	= new JRegistry;
			$params->loadString($template->params);
			
			if ($app->input->get('displayshortcodes', 0, 'int')) {
				// get output
				JHtml::_('behavior.tooltip');
				JHtml::_('behavior.formvalidation');
				JHtml::_('behavior.keepalive');
				
				// load template language file
				$this->loadLanguage('tpl_'.$template->template, JPATH_SITE);

				$lang = JFactory::getLanguage();
				$lang->load('plg_system_rstemplate', JPATH_ADMINISTRATOR, 'en-GB', true);
				$lang->load('plg_system_rstemplate', JPATH_ADMINISTRATOR, $lang->getDefault(), true);
				$lang->load('plg_system_rstemplate', JPATH_ADMINISTRATOR, null, true);
				
				$template_path  = JPATH_SITE.'/templates/'.$template->template;
				
				if (file_exists($template_path.'/html/shortcodes/default.php')) {
					ob_end_clean();
					require_once $template_path.'/html/shortcodes/default.php';
				} else {
					echo '<div style="background-color: #F2DEDE; border-color: #EED3D7; color: #B94A48;line-height: 1px;padding: 8px 16px 8px 12px;"><div class="alert alert-error"><h4 class="alert-heading">Error</h4><p>'.JText::_('PLG_SYSTEM_RSTEMPLATE_HTML_NOT_INSTALLED').'</p></div></div>';
				}

				exit();
			}
		}
	}
}