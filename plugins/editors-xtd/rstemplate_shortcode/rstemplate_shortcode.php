<?php
/**
* @version 1.0.0
* @package RSTemplate! 1.0.0
* @copyright (C) 2009 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die;

class PlgButtonRSTemplate_Shortcode extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return array A four element array of (article_id, article_title, category_id, object)
	 */
	public function onDisplay($name)
	{
		$user 	= JFactory::getUser();
		$app 	= JFactory::getApplication();

		$js = "

		function jSelectRSTemplateShortcode(shortcode)
		{
			jInsertEditorText(shortcode, '" . $name . "');
			SqueezeBox.close();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		JHtml::_('behavior.modal');

		/*
		 * Use the built-in element view to select the article.
		 * Currently uses blank class.
		 */
		$link = 'index.php?displayshortcodes=1&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

		$button = new JObject;
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text = JText::_('PLG_EDITORS-XTD_RSTEMPLATE_BUTTON');
		$button->name = 'power-cord';
		$button->options = "{handler: 'iframe', size: {x: 1000, y: 600}}";

		return $button;
	}
}
