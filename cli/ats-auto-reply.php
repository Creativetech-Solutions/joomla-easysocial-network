<?php
/**
 *  @package ats
 *  @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 *  @license GNU General Public License version 3, or later
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  --
 *
 *  Command-line script to remove old attachments from tickets
 */
// Define ourselves as a parent file
define('_JEXEC', 1);
// Required by the CMS
define('DS', DIRECTORY_SEPARATOR);

$minphp = '5.4.0';
if (version_compare(PHP_VERSION, $minphp, 'lt'))
{
	$curversion = PHP_VERSION;
	$bindir = PHP_BINDIR;
	echo <<< ENDWARNING
================================================================================
WARNING! Incompatible PHP version $curversion
================================================================================

This CRON script must be run using PHP version $minphp or later. Your server is
currently using a much older version which would cause this script to crash. As
a result we have aborted execution of the script. Please contact your host and
ask them for the correct path to the PHP CLI binary for PHP $minphp or later, then
edit your CRON job and replace your current path to PHP with the one your host
gave you.

For your information, the current PHP version information is as follows.

PATH:    $bindir
VERSION: $curversion

Further clarifications:

1. There is absolutely no possible way that you are receiving this warning in
   error. We are using the PHP_VERSION constant to detect the PHP version you
   are currently using. This is what PHP itself reports as its own version. It
   simply cannot lie.

2. Even though your *site* may be running in a higher PHP version that the one
   reported above, your CRON scripts will most likely not be running under it.
   This has to do with the fact that your site DOES NOT run under the command
   line and there are different executable files (binaries) for the web and
   command line versions of PHP.

3. Please note that you MUST NOT ask us for support about this error. We cannot
   possibly know the correct path to the PHP CLI binary as we have not set up
   your server. Your host must know and give that information.

4. The latest published versions of PHP can be found at http://www.php.net/
   Any older version is considered insecure and must NOT be used on a live
   server. If your server uses a much older version of PHP than that please
   notify them that their servers are insecure and in need of an update.

This script will now terminate. Goodbye.

ENDWARNING;
	die();
}

// Load system defines
if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	$path = rtrim(__DIR__, DIRECTORY_SEPARATOR);
	$rpos = strrpos($path, DIRECTORY_SEPARATOR);
	$path = substr($path, 0, $rpos);
	define('JPATH_BASE', $path);
	require_once JPATH_BASE . '/includes/defines.php';
}

// Load the rest of the framework include files
if (file_exists(JPATH_LIBRARIES . '/import.legacy.php'))
{
	require_once JPATH_LIBRARIES . '/import.legacy.php';
}
else
{
	require_once JPATH_LIBRARIES . '/import.php';
}
require_once JPATH_LIBRARIES . '/cms.php';

// Load the JApplicationCli class
JLoader::import('joomla.application.cli');
JLoader::import('joomla.environment.request');
JLoader::import('joomla.environment.uri');
JLoader::import('joomla.utilities.date');
JLoader::import('joomla.application.component.helper');
JLoader::import('legacy.component.helper');

if (version_compare(JVERSION, '3.4.9999', 'ge'))
{
	// Joomla! 3.5 and later does not load the configuration.php unless you explicitly tell it to.
	JFactory::getConfig(JPATH_CONFIGURATION . '/configuration.php');
}

/**
 * Akeeba Ticket System auto-reply CLI app
 */
class ATSAutoreplyApp extends JApplicationCli
{
	/**
	 * JApplicationCli didn't want to run on PHP CGI. I have my way of becoming
	 * VERY convincing. Now obey your true master, you petty class!
	 *
	 * @param JInputCli $input
	 * @param JRegistry $config
	 * @param JDispatcher $dispatcher
	 */
	public function __construct(JInputCli $input = null, JRegistry $config = null, JDispatcher $dispatcher = null)
	{
		// Close the application if we are not executed from the command line, Akeeba style (allow for PHP CGI)
		if (array_key_exists('REQUEST_METHOD', $_SERVER))
		{
			die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
		}

		$cgiMode = false;

		if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv']))
		{
			$cgiMode = true;
		}

		// If a input object is given use it.
		if ($input instanceof JInput)
		{
			$this->input = $input;
		}
		// Create the input based on the application logic.
		else
		{
			if (class_exists('JInput'))
			{
				if ($cgiMode)
				{
					$query = "";
					if (!empty($_GET))
					{
						foreach ($_GET as $k => $v)
						{
							$query .= " $k";
							if ($v != "")
							{
								$query .= "=$v";
							}
						}
					}
					$query	 = ltrim($query);
					$argv	 = explode(' ', $query);
					$argc	 = count($argv);

					$_SERVER['argv'] = $argv;
				}

				$this->input = new JInputCLI();
			}
		}

		// If a config object is given use it.
		if ($config instanceof JRegistry)
		{
			$this->config = $config;
		}
		// Instantiate a new configuration object.
		else
		{
			$this->config = new JRegistry;
		}

		// If a dispatcher object is given use it.
		if ($dispatcher instanceof JDispatcher)
		{
			$this->dispatcher = $dispatcher;
		}
		// Create the dispatcher based on the application logic.
		else
		{
			$this->loadDispatcher();
		}

		// Load the configuration object.
		$this->loadConfiguration($this->fetchConfigurationData());

		// Set the execution datetime and timestamp;
		$this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
		$this->set('execution.timestamp', time());

		// Set the current directory.
		$this->set('cwd', getcwd());

		// Work around Joomla! 3.4.7's JSession bug
		if (version_compare(JVERSION, '3.4.7', 'eq'))
		{
			JFactory::getSession()->restart();
		}
	}

	/**
	 * The main entry point of the application
	 */
	public function execute()
	{
		$this->out('Akeeba Ticket System -- Autoreply script');
		$this->out('Copyright 2011-' . gmdate('Y') . ' Nicholas K. Dionysopoulos / AkeebaBackup.com');
		$this->out(str_repeat('=', 79));

        // Load FOF
        if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
        {
            die('FOF 3.0 is not installed');
        }

		// Load version defines
		$path = JPATH_ADMINISTRATOR . '/components/com_ats/version.php';

		@include_once $path;

		if (!defined('ATS_PRO')) define('ATS_PRO', 0);
		if (!defined('ATS_VERSION')) define('ATS_VERSION', 'dev');
		if (!defined('ATS_DATE')) define('ATS_DATE', date('Y-m-d'));

        $container = \FOF30\Container\Container::getInstance('com_ats');

		$params   = JComponentHelper::getParams('com_ats');

        // Prepare some $_SERVER superglobals required by JUri
        $siteURL = $params->get('siteurl', 'http://www.example.com');
        $uri     = JURI::getInstance($siteURL);

        $_SERVER['HTTP_HOST']   = $uri->toString(array('host', 'port'));
        $_SERVER['REQUEST_URI'] = $uri->getPath();

		$botusers = array();

        // Let's check if I have any username
		$rawData = trim($params->get('botusers', ''));

		// Just in case something funky happened...
        $rawData = str_replace("\\n", "\n", $rawData);
        $rawData = str_replace("\r", "\n", $rawData);
        $rawData = str_replace("\n\n", "\n", $rawData);

        if($rawData)
        {
        	$botusers = explode("\n", $rawData);
        }

		$this->out("Number of available bot users: ".count($botusers));

		if (count($botusers) <= 0)
		{
			$this->out('Will not proceed without any configured bot');

			return;
		}

        $wrong = array();

        // Let's check if every bot is a real user
        foreach($botusers as $bot)
        {
            if(!$container->platform->getUser($bot))
            {
                $wrong[] = $bot;
            }
        }

        if($wrong)
        {
            $this->out("The following users are flagged as bot, but they don't exist inside Joomla!: ");
            $this->out(implode(',', $wrong));
            $this->out('Will not proceed with misconfigured bots');

            return;
        }

        // Check if I have any rule to follow
        /** @var \Akeeba\TicketSystem\Site\Model\AutomaticReplies $model */
        $model = $container->factory->model('AutomaticReplies')->tmpInstance();
        $autoreplies = $model->enabled(1)->get(true);

        if(!$autoreplies)
        {
        	$this->out('There are no autoreplies configured, will not continue');

        	return;
        }

        try
        {
	        $result = $model->runAutoreplies();
	        $this->out('Bots replied to '.$result.' tickets');
        }
        catch(Exception $e)
        {
            $this->out($e->getMessage());
        }
	}

}
JApplicationCli::getInstance('ATSAutoreplyApp')->execute();
