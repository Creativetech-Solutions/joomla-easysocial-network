<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;
use JFactory;
use JHtml;
use JTable;
use JText;
use JUri;

defined('_JEXEC') or die;

class Html
{
    /**
     * Creates the CSS class accordingly to the ticket status
     *
     * @param $value
     *
     * @return string   CSS class for the ticket status
     */
    public static function getStatusClass($value)
    {
        switch($value) {
            case 'O':
                return 'label-important label-danger ats-status-open';
            case 'C':
                return 'label-success ats-status-close';
            case 'P':
                return 'label-warning ats-status-pending';
            default:
                return 'label-warning ats-status-'.$value;
        }
    }

    /**
     * Create an HTML element accordingly to the post origin
     *
     * @param   string  $origin
     *
     * @return  string  Html element for the origin of the post
     */
    public static function getPostOriginIcon($origin)
    {
        if($origin == 'web')
        {
            $origin_icon = '<span class="icon-globe"></span> ';
        }
        elseif ($origin == 'email')
        {
            $origin_icon = '<span class="icon-envelope"></span> ';
        }
        else
        {
            $origin_icon = '';
        }

        return $origin_icon;
    }

    /**
     * Decode ticket status, including custom ones defined by the user
     *
     * @param   string|int   $status       Status to decode
     * @param   boolean      $create_span  Should I wrap the output in a span?
     *
     * @return  string       Decoded HTML
     */
    public static function decodeStatus($status, $create_span = false)
    {
        $standard = array('O', 'P', 'C');
        $custom   = ComponentParams::getCustomTicketStatuses();

        if(in_array($status, $standard))
        {
            $text  = JText::_('COM_ATS_TICKETS_STATUS_'.$status);
            $class = 'ats-ticket-status-'.$status;
        }
        elseif(isset($custom[$status]))
        {
            $text = $custom[$status];
            // Custom status are always considered "pending"
            $class = 'ats-ticket-status-P';
        }
        else
        {
            return '';
        }

        if($create_span)
        {
            return '<span class="'.$class.'">'.$text.'</span>';
        }
        else
        {
            return $text;
        }
    }

    /**
     * Uses the ATS plugins to fetch ana avatar for the user
     *
     * @param \JUser $user The user for which to fetch an avatar for
     * @param int $size The size (in pixels), defaults to 64
     * @return string The URL to the avatar image
     */
    public static function getAvatarURL($user, $size = 64)
    {
        $container = Container::getInstance('com_ats');
        $container->platform->importPlugin('ats');

        $jResponse = $container->platform->runPlugins('onATSAvatar',array($user, $size));

        $url = '';

        if(!empty($jResponse))
        {
            foreach($jResponse as $response)
            {
                if($response)
                {
                    $url = $response;
                }
            }
        }

        return $url;
    }

    /**
     * Creates a dropdown list containing managers. If $baselink param is not passed, we assume
     * we're using it in AJAX mode, ie no link is created
     *
     * @param   string   $baselink    Baselink used to create urls. If null, a dummy link is created (javascript:void(0))
     * @param   array    $class       Array containing classes for the holding div (index 'div') and for the links (index 'a')
     * @param   int      $category    Ticket category
     *
     * @return  string   Dropdown html code
     */
    public static function buildManagerdd($baselink = null, $class = null, $category = null)
    {
        $managers = Permissions::getManagers($category);

        $div	= isset($class['div']) ? $class['div'] : '';
        $a		= isset($class['a']) ? $class['a'] : '';

        $html  = '<div class="btn-group '.$div.'">';
        // I have to hardcode the fontsize, since in backend it's overrided by template css
        $html .= 	'<a class="btn '.$a.' dropdown-toggle" data-toggle="dropdown" href="#" style="font-size:11px">';
        //$html .= 		JText::_('COM_ATS_TICKETS_ASSIGN_TO').'&nbsp;';
        $html .= 		'<span class="caret"></span>';
        $html .= 	'</a>';
        $html .= 	'<ul class="dropdown-menu">';

        // Create options at the top of the managers list
        $container    = Container::getInstance('com_ats');
        $fakeManagers = array();
        $myId         = $container->platform->getUser()->id;

        // -- Assign to me
        if (array_key_exists($myId, $managers))
        {
            $fakeManagers[] = (object)array('id' => $myId, 'name' => '<span class="label label-info"><span class="icon icon-flag"></span></span> ' . JText::_('COM_ATS_TICKET_ASSIGN_TOME'));
        }
        // -- Unassign
        $fakeManagers[] = (object)array('id' => 0, 'name' => '<span class="label label-important label-danger"><span class="icon icon-white icon-fire"></span></span> ' . JText::_('COM_ATS_TICKET_UNASSIGN'));

        $merged = array_merge($fakeManagers, $managers);

        foreach ($merged as $manager)
        {
            if (is_null($baselink))
            {
                $link  = '#';
                $class = $manager->id > 0 ? 'assignto' : 'unassign';
                $input = '<input type="hidden" class="' . $class . '" value="' . $manager->id . '" />';
            }
            else
            {
                if (!is_null($manager->id))
                {
                    $link  = $baselink . $manager->id;
                }
                else
                {
                    $link  = '#';
                }
                $input = '';
            }

            // I have to hardcode the fontsize, since in backend it's overrided by template css
            $html .= '<li><a href="' . $link . '">' . $manager->name . '</a>' . $input . '</li>';
        }

        $html .=	'</ul>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Creates a dropdown list of statuses, including the custom ones created by the user
     *
     * @param    array    $config
     *
     * @return   string   HTML to create a dropdown list
     */
    public static function createStatusDropdown($config = array())
    {
        $custom = ComponentParams::getCustomTicketStatuses();

        if (!array_key_exists('title', $config))
        {
            $config['title'] = JText::_('COM_ATS_TICKETS_STATUS_SELECT');
        }

        if (!array_key_exists('btn_style', $config))
        {
            $config['btn_style'] = 'btn-inverse' ;
        }

        if (!array_key_exists('div_style', $config))
        {
            $config['div_style'] = '' ;
        }

        $html  = '<div class="btn-group select-status ' . $config['div_style'] . '">';
        $html .= 	'<a class="btn btn-mini btn-default btn-xs dropdown-toggle ' . $config['btn_style'] . '" data-toggle="dropdown" href="#" style="font-size:11px">';
        $html .=		$config['title'];
        $html .=		'<span class="caret"></span>';
        $html .=	'</a>';
        $html .=	'<ul class="dropdown-menu">';
        // Standard statuses
        $html .=		'<li><a href="#" data-status="O"><span class="badge badge-important badge-danger">O</span> '.JText::_('COM_ATS_TICKETS_STATUS_O').'</a></li>';
        $html .=		'<li><a href="#" data-status="P"><span class="badge badge-warning">P</span> '.JText::_('COM_ATS_TICKETS_STATUS_P').'</a></li>';

        foreach($custom as $value => $text)
        {
            $html .=	'<li><a href="#" data-status="'.$value.'">'.$text.'</a></li>';
        }

        $html .=		'<li><a href="#" data-status="C"><span class="badge badge-success">C</span> '.JText::_('COM_ATS_TICKETS_STATUS_C').'</a></li>';
        $html .=	'</ul>';
        $html .= '</div>';

        return $html;
    }

    public static function getSignature(\JUser $user)
    {
        static $signatures = array();

        if ($user->username == 'system')
        {
            return '';
        }

        $container = Container::getInstance('com_ats');

        if (!array_key_exists($user->username, $signatures))
        {
            $db    = $container->db;

            $query = $db->getQuery(true)
                        ->select($db->qn('profile_value'))
                        ->from($db->qn('#__user_profiles'))
                        ->where($db->qn('user_id') . ' = ' . $db->q($user->id))
                        ->where($db->qn('profile_key') . ' = ' . $db->q('ats.signature'));
            $signature = $db->setQuery($query)->loadResult();

            if (!is_null($signature))
            {
                $signature = Filter::filterText($signature, $user);
            }

            $signatures[$user->username] = empty($signature) || is_null($signature) ? '' : $signature;
        }

        return $signatures[$user->username];
    }

    /**
     * Fetches the correct user that posted the ticket
     *
     * @param   int     $userid
     *
     * @return \JUser
     */
    public static function getPostUser($userid)
    {
        $container = Container::getInstance('com_ats');

        if($userid == -1)
        {
            $postUser = clone $container->platform->getUser();

            $postUser->username = 'system';
            $postUser->email    = 'noreply@example.com';
            $postUser->name     = JText::_('COM_ATS_CLI_SYSTEMUSERLABEL');
        }
        else
        {
            $postUser = $container->platform->getUser($userid);
        }

        return $postUser;
    }

    public static function loadposition($position, $style = -2)
    {
        $document	= \JFactory::getDocument();
        $renderer	= $document->loadRenderer('module');
        $params		= array('style'=>$style);

        $contents = '';

        foreach (\JModuleHelper::getModules($position) as $mod)
        {
            $contents .= $renderer->render($mod, $params);
        }

        return $contents;
    }

    /**
     * Checks if the user wants and can use AJAX reply. If so, load the required javascript
     *
     * @return bool
     */
    public static function ajaxReply()
    {
        $navigator = \JBrowser::getInstance();

        // If I am using a WYSIWYG editor or I don't want AJAX or I'm using an IE version lower than 10, then disable AJAX posting
        if(ComponentParams::getParam('editor', 'bbcode') == 'wysiwyg' || !ComponentParams::getParam('ajaxreplies', 1) || ($navigator->isBrowser('msie') && $navigator->getVersion() < 10))
        {
            return false;
        }
        else
        {
            $container = Container::getInstance('com_ats');

            $container->template->addJS('media://com_ats/js/ajax_posting.js', false, false, $container->mediaVersion);
            $container->template->addJS('media://com_ats/js/jquery.form.js', false, false, $container->mediaVersion);
            $container->template->addJS('media://com_ats/js/jquery.blockUI.js', false, false, $container->mediaVersion);
            JText::script('COM_ATS_COMMON_RELOAD');
            JText::script('COM_ATS_COMMON_RELOADING');

            return true;
        }
    }

    /**
     * Gets the total amount of tickets created by a specific user
     *
     * @param   integer $userid     ID of the user
     *
     * @return  integer Number of created tickets
     */
    public static function getTicketsCount($userid)
    {
        static $count = array();

        if(!isset($count[$userid]))
        {
            $container = Container::getInstance('com_ats');

            // Let's directly use the db, so I can be faster
            $db = $container->db;

            $query = $db->getQuery(true)
                        ->select('COUNT(*)')
                        ->from($db->qn('#__ats_tickets'))
                        ->where($db->qn('created_by').' = '.$db->q($userid));
            $count[$userid] = $db->setQuery($query)->loadResult();
        }

        return $count[$userid];
    }

    /**
     * Gets the total amount of time spent supporting a specific user
     *
     * @param   integer  $userid  ID of the user
     *
     * @return  integer  Total time spent supporting the user, in minutes
     */
    public static function getTimeSpentPerUser($userid)
    {
        static $timePerUser = array();

        if(!isset($timePerUser[$userid]))
        {
            $container = Container::getInstance('com_ats');

            // Let's directly use the db, so I can be faster
            $db = $container->db;

            $query = $db->getQuery(true);
            $query
                        ->select('SUM(' . $query->qn('timespent') . ')')
                        ->from($db->qn('#__ats_tickets'))
                        ->where($db->qn('created_by').' = '.$db->q($userid));
            $timePerUser[$userid] = $db->setQuery($query)->loadResult();
        }

        return $timePerUser[$userid];
    }

    /**
     * Returns the maximum allowed size for an attachment in human format (ie 1Mb)
     *
     * @return  string
     */
    public static function getUploadLimits()
    {
        $mediaLimit = \JComponentHelper::getParams('com_media')->get('upload_maxsize', 0) * 1024 * 1024;

        $umf = Format::toBytes(ini_get('upload_max_filesize'));
        $pms = Format::toBytes(ini_get('post_max_size'));

        if( ($umf * $pms) == 0 )
        {
            $uploadLimit = max($umf, $pms);
        }
        else
        {
            $uploadLimit = min($umf, $pms);
        }

        $uploadLimit = min($uploadLimit, $mediaLimit);
        $uploadLimit = Format::bytesToSize($uploadLimit);

        return $uploadLimit;
    }

    /**
     * Returns the allowed file extensions for uploads
     *
     * @return array
     */
    public static function allowedExtensions()
    {
        $extensions = \JComponentHelper::getParams('com_media')->get('upload_extensions');
        $extensions = explode(',', $extensions);

        // Put everything lowercase...
        $extensions = array_map('strtolower', $extensions);

        // ... and return unique values
        return array_unique($extensions);
    }

	/**
	 * In Joomla 3.5 the modal window to select a Joomla user changed. Since we have to support
	 * versions before 3.5, too, we have to update our code accordingly
	 *
	 * @param   string     $input_id    ID of the input field
	 * @param   string     $name        Name of the input field
	 * @param   string     $value       Value of the field
	 * @param   string     $class       Any additional class for the field
	 *
	 * @return string
	 */
	public static function modalChooseUser($input_id, $name, $value = null, $class = null)
	{
		if (version_compare(JVERSION, '3.5', 'ge'))
		{
			return static::modalChooseUser35($input_id, $name, $value, $class);
		}
		else
		{
			return static::modalChooseUser34($input_id, $name, $value, $class);
		}
	}

	public static function modalChooseUser34($input_id, $name, $value = null, $class = null)
	{
		$html = array();
		$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=' . $input_id;

		// Initialize some field attributes.
		$attr = $class ? ' class="' . $class . '"' : '';

		// Initialize JavaScript field attributes.
		//$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal_' . $input_id);

		// Build the script.
		$script = array();
		$script[] = '	function jSelectUser_' . $input_id . '(id, title) {';
		$script[] = '		var old_id = document.getElementById("' . $input_id . '_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("' . $input_id . '_id").value = id;';
		$script[] = '			document.getElementById("' . $input_id . '_name").value = title;';
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('user');

		if ($value)
		{
			$table->load($value);
		}
		else
		{
			$table->name = JText::_('JLIB_FORM_SELECT_USER');
		}

		// Create a dummy text field with the user name.
		$html[] = '<div>';
		$html[] = '	<input type="text" id="' . $input_id . '_name"' . ' value="' . htmlspecialchars($table->name, ENT_COMPAT, 'UTF-8') . '"'
			. ' disabled="disabled"' . $attr . ' />';
		$html[] = '</div>';

		// Create the user select button.
		$html[] = '		<a class="btn btn-primary modal_' . $input_id . '" title="' . JText::_('JLIB_FORM_CHANGE_USER') . '"' . ' href="' . $link . '"'
			. ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
		$html[] = '			' . JText::_('JLIB_FORM_CHANGE_USER') . '</a>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="' . $input_id . '_id" name="' . $name . '" value="' . (int) $value . '" />';

		return implode("\n", $html);
	}

	public static function modalChooseUser35($input_id, $name, $value = null, $class = null)
	{
		JFactory::getDocument()->addScript(JUri::root().'media/jui/js/fielduser.js');
		$selectText = JText::_('JLIB_FORM_CHANGE_USER');

		// Load the current username if available.
		$table = JTable::getInstance('user');
		if ($value)
		{
			$table->load($value);
		}
		else
		{
			$table->name = JText::_('JLIB_FORM_SELECT_USER');
		}

		$html =<<<HTML
<div class="field-user-wrapper"
	data-url="index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;required=0&amp;field={$input_id}&amp;"
	data-modal=".modal"
	data-modal-width="100%"
	data-modal-height="400px"
	data-input=".field-user-input"
	data-input-name=".field-user-input-name"
	data-button-select=".button-select"
>
	<div class="input-append">
		<input type="text" id="{$input_id}_name" value="{$table->name}" readonly class="field-user-input-name " />
		<a class="btn btn-primary button-select" title="{$selectText}"><span class="icon-user"></span></a>
		<div id="userModal_jform_created_by" tabindex="-1" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>{$selectText}</h3>
			</div>
			
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button></div>
			</div>
		</div>
		
		<input type="hidden" id="{$input_id}_id" name="$name" value="$value" class="field-user-input " />
</div>

HTML;

		return $html;

	}
}