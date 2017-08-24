<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

// Make sure ATS is installed, otherwise bail out
if(!file_exists(JPATH_ADMINISTRATOR.'/components/com_ats'))
{
	return;
}

// PHP version check
if(defined('PHP_VERSION'))
{
	$version = PHP_VERSION;
}
elseif(function_exists('phpversion'))
{
	$version = phpversion();
}
else
{
	$version = '5.0.0'; // all bets are off!
}

if(!version_compare($version, '5.4.0', '>='))
{
    return;
}

// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
if(function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
	if(function_exists('error_reporting'))
    {
		$oldLevel = error_reporting(0);
	}

	$serverTimezone = @date_default_timezone_get();

	if(empty($serverTimezone) || !is_string($serverTimezone))
    {
        $serverTimezone = 'UTC';
    }

	if(function_exists('error_reporting'))
    {
		error_reporting($oldLevel);
	}

	@date_default_timezone_set( $serverTimezone);
}

// Include F0F's loader if required
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
    throw new RuntimeException('FOF 3.0 is not installed', 500);
}

/**
 * ATS Search plugin
 */
class plgSystemAtsreplybyemail extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	public function onAfterInitialise()
	{
		$container = \FOF30\Container\Container::getInstance('com_ats');

        /** @var \Akeeba\TicketSystem\Site\Model\EmailCheck $model */
        $model = $container->factory->model('EmailCheck')->tmpInstance();

		if(!$model->shouldCheckForEmail())
        {
            return;
        }

		try
        {
			$model->checkEmail();
		}
        catch (Exception $exc)
        {
			JLog::add($exc->getMessage(), JLog::ERROR);
		}
	}
}