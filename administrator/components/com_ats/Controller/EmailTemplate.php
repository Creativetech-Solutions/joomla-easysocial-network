<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use Akeeba\TicketSystem\Admin\Helper\Email;
use FOF30\Controller\DataController;
use JFactory;
use JText;
use JUri;

defined('_JEXEC') or die;

class EmailTemplate extends DataController
{
    public function testtemplate()
    {
	    $id = $this->input->getInt('ats_emailtemplate_id', 0);

	    $conf = JFactory::getConfig();

	    try
	    {
		    $mailer = Email::getMailer();
	    }
	    catch (\Exception $e)
	    {
		    $this->setRedirect('index.php?option=com_ats&view=EmailTemplate&id=' . $id,
			    JText::sprintf('COM_ATS_EMAILTEMPLATES_TEST_NOTSENT', $e->getMessage()),
			    'error'
		    );

		    return;
	    }

	    /** @var \Akeeba\TicketSystem\Admin\Model\EmailTemplates $model */
	    $model = $this->getModel();
	    $key   = $model->getKey($id);

	    list($body, $subject) = $model->getEmailTemplate($key);

	    $mailInfo = array(
		    'id'              => '12345',
		    'title'           => 'Template Test',
		    'url'             => JUri::root(),
		    'attachment'      => '',
		    'poster_username' => 'Poster Username',
		    'poster_name'     => 'Poster Name',
		    'user_username'   => 'Notified user Username',
		    'user_name'       => 'Notified user Name',
		    'text'            => 'This is the text contained inside the post',
		    'catname'         => 'Category title',
		    'sitename'        => $conf->get('sitename'),
		    'origin'          => 'ORIGIN',
		    'avatar'          => '',
		    'assigned_to'     => 'Assigned user',
	    );

	    try
	    {
		    $parsed = $model->parseTemplate($body, $subject, $mailInfo);

		    $mailer->addRecipient(JFactory::getUser()->email, JFactory::getUser()->name);
		    $mailer->setSubject($parsed['subject']);
		    $mailer->setBody($parsed['body']);

		    $mailer->Send();

		    $this->setRedirect('index.php?option=com_ats&view=EmailTemplate&id=' . $id, JText::_('COM_ATS_EMAILTEMPLATES_TEST_SENT'));
	    }
	    catch (\Exception $e)
	    {
		    // JMail died on us
		    $this->setRedirect('index.php?option=com_ats&view=EmailTemplate&id=' . $id,
			    JText::sprintf('COM_ATS_EMAILTEMPLATES_TEST_NOTSENT', $e->getMessage()),
			    'error'
		    );

		    return;
	    }
    }
}