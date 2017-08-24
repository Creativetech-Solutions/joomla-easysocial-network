<?php
/**
 * @package Blue Flame Network (bfNetwork)
 * @copyright Copyright (C) 2011, 2012, 2013, 2014, 2015, 2016 Blue Flame IT Ltd. All rights reserved.
 * @license GNU General Public License version 3 or later
 * @link https://myJoomla.com/
 * @author Phil Taylor / Blue Flame IT Ltd.
 *
 * bfNetwork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * bfNetwork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this package.  If not, see http://www.gnu.org/licenses/
 */

// Decrypt or die
require 'bfEncrypt.php';

/**
 * If we have got here then we have already passed through decrypting
 * the encrypted header and so we are sure we are now secure and no one
 * else cannot run the code below......
 */

define('BFUPGRADE_RELEASE_TYPE', "lts"); // test lts sts

require './lib/autoloader.php';
new AcuAutoloader();

class bfUpgrade
{
    /**
     * We pass the command to run as a simple integer in our encrypted
     * request this is mainly to speed up the decryption process, plus its a
     * single digit(or 2) rather than a huge string to remember :-)
     */
    private $_methods = array(
        1 => 'getAllUpdates',
        2 => 'downloadFile',
        3 => 'createRestorationFile',
        4 => 'extractUpdate',
        5 => 'finishup',
    );

    /**
     * PHP 5 Constructor,
     * I inject the request to the object
     *
     * @param stdClass $dataObj
     */
    public function __construct($dataObj)
    {
        // init Joomla
        require 'bfInitJoomla.php';


        // Set the request vars
        $this->_dataObj = $dataObj;
    }

    /**
     * I'm the controller - I run methods based on the request integer
     */
    public function run()
    {
        if (property_exists($this->_dataObj, 'c')) {

            $c = ( int )$this->_dataObj->c;
            if (array_key_exists($c, $this->_methods)) {

                // call the right method
                $this->{$this->_methods [$c]} ();
            } else {

                // Die if an unknown function
                bfEncrypt::reply('error', 'No Such method #err1 - ' . $c);
            }
        } else {

            // Die if an unknown function
            bfEncrypt::reply('error', 'No Such method #err2');
        }
    }

    /**
     * I tick over to download the correct package file
     */
    public function downloadFile()
    {

        // Use Akeeba - We love you Nicolas!
        $api            = new AcuDownload();

        // init array
        $params         = array();

        // tell it which file to use
        $params['file'] = $this->_dataObj->fileUrl;

        // make sure we resume a part downloaded file if any
        if ($this->_dataObj->frag) {
            $params['frag'] = $this->_dataObj->frag;
        } else {
            $params['frag'] = -1;
        }

        // get the frag needed
        $retArray = $api->importFromURL($params);

        // tock back to myjoomla
        bfEncrypt::reply('success', json_encode($retArray));
    }

    /**
     * Create the restore ini file, thats actually a PHP file :-)
     */
    public function createRestorationFile()
    {
        $res = $this->createRestorationINI();
        bfEncrypt::reply('success', $res);
    }

    /**
     * Purges the Joomla! update cache. We ARE NOT using this cache, but the CMS
     * does. We want to bust the cache to provent Joomla! from reporting updates
     * after we install an update through our component
     *
     * @return  bool  True on success
     */
    public function purgeJoomlaUpdateCache()
    {
        bfLog::log('running purgeJoomlaUpdateCache');

        $db = JFactory::getDbo();

        // Modify the database record
        $update_site                       = new stdClass;
        $update_site->last_check_timestamp = 0;
        $update_site->enabled              = 1;
        $update_site->update_site_id       = 1;
        $db->updateObject('#__update_sites', $update_site, 'update_site_id');

        $query = $db->getQuery(TRUE)
                    ->delete($db->quoteName('#__updates'))
                    ->where($db->quoteName('update_site_id') . ' = ' . $db->quote('1'));
        $db->setQuery($query);

        if (method_exists($db, 'execute')) {
            if ($db->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if ($db->query()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * Make sure the db schema is updated - even Akeeba doesnt do this :-)
     */
    public function finishup()
    {
        require 'bfInitJoomla.php';
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        // Because of the crappy Joomla early days of updates in 3.1.0
        $sql = "CREATE TABLE IF NOT EXISTS `#__content_types` (
                  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `type_title` varchar(255) NOT NULL DEFAULT '',
                  `type_alias` varchar(255) NOT NULL DEFAULT '',
                  `table` varchar(255) NOT NULL DEFAULT '',
                  `rules` text NOT NULL,
                   `field_mappings` text NOT NULL,
                   `router` varchar(255) NOT NULL  DEFAULT '',
                  PRIMARY KEY (`type_id`),
                  KEY `idx_alias` (`type_alias`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10000;";
        $db  = JFactory::getDbo();
        $db->setQuery($sql);
        if (method_exists($db, 'execute')) {
            $db->execute();
        } else {
            $db->query();
        }

        // Let Joomla handle the grunt work
        $filePath = JPATH_ROOT . '/administrator/components/com_admin/script.php';

        if (file_exists($filePath)) {

            require_once($filePath);

            $o = new JoomlaInstallerScript();
            if (method_exists($o, 'preflight')) {
                $o->preflight('update', NULL);
            }

            if (method_exists($o, 'update')) {
                $o->update(NULL);
            }

            // need to upgrade db as well.
            require JPATH_ROOT . '/administrator/components/com_installer/models/database.php';
            $model = new InstallerModelDatabase();
            $model->fix();

        }

        @unlink(dirname(__FILE__) . '/tmp/myjoomla-upgradefile.zip');
        @unlink(dirname(__FILE__) . '/tmp/restoration.php');

        bfEncrypt::reply('success', array(
            'msg' => json_encode(true)
        ));
    }

    /**
     * Returns the (cached) list of updates for every section: installed version, current
     * branch updates, sts/lts updates, testing updates.
     *
     * @param   boolean $force Should I forcibly reload the update information, refreshing the cache?
     *
     * @return  array|null  The updates array, null if crap hits the fan
     */
    public function getAllUpdates($force = FALSE)
    {

        // Get the component parameters
        if (version_compare(JVERSION, '3.0.0', 'ge')) {
            JLoader::import('cms.component.helper');
        } else {
            JLoader::import('joomla.application.component.helper');
        }

        $params = JComponentHelper::getParams('com_cmsupdate');

        // Do I have to check for updates?
        if (!$force) {
            // Check with the specified frequency which has to be between 1 hour and 30 days
            $frequency = $params->get('frequency', 6);

            if (($frequency < 0) || ($frequency > 720)) {
                $frequency = 6;
            }

            // Get the last time we checked for updates
            $lastCheck = $params->get('lastcheck', 0);

            $nextCheckTimeStamp = $lastCheck + 3600 * $frequency;

            $force = $nextCheckTimeStamp <= time();
        }

        // Do I have a cache? If not I have to force an update fetch.
        $cache = NULL;

        if (!$force) {
            $cacheEncoded = $params->get('updatecache', '');

            if (!empty($cacheEncoded)) {
                $cache = json_decode($cacheEncoded, TRUE);
            }

            if (empty($cache)) {
                $cache = NULL;
                $force = TRUE;
            }
        }

        // If we are forced to perform an update fetch do it and refresh the cache
        if ($force) {
            // Get the update sources we are configured to use
            $sources = array(
                'lts'    => TRUE,
                'sts'    => TRUE,
                'test'   => TRUE,
                'custom' => $params->get('customurl', ''),
            );

            switch ($params->get('updatesource', 'all')) {
                case 'custom':
                    $sources['lts']     = FALSE;
                    $sources['sts']     = FALSE;
                    $sources['testing'] = FALSE;
                    break;

                case 'testing':
                    $sources['lts']    = FALSE;
                    $sources['sts']    = FALSE;
                    $sources['custom'] = '';
                    break;

                case 'sts':
                    $sources['lts']    = FALSE;
                    $sources['test']   = FALSE;
                    $sources['custom'] = '';
                    break;

                case 'lts':
                    $sources['sts']    = FALSE;
                    $sources['test']   = FALSE;
                    $sources['custom'] = '';
                    break;
            }

            // Get the updates
            $provider = new AcuUpdateProviderJoomla();
            $cache    = $provider->getUpdates($sources);

            // JSON-encode them
            $cacheEncoded = json_encode($cache);

            // Save the cache
            $params->set('updatecache', $cacheEncoded);
            $params->set('lastcheck', time());

            $component = JComponentHelper::getComponent('com_cmsupdate');

            $db    = JFactory::getDbo();
            $query = $db->getQuery(TRUE)
                        ->update('#__extensions')
                        ->set('params' . ' = ' . $db->quote($params->toString('JSON')))
                        ->where('extension_id' . ' = ' . $db->quote($component->id));
            $db->setQuery($query);
            if (method_exists($db, 'execute')) {
                $db->execute();
            } else {
                $db->query();
            }
        }


        if ($this->hasAkeebaBackup()) {
            $db    = JFactory::getDbo();
            $query = $db->getQuery(TRUE)
                        ->select('MAX(id)')
                        ->from('#__ak_stats')
                        ->where('`origin` != "restorepoint"');
            $db->setQuery($query);
            $lastBackup = $db->loadResult();

            if ($lastBackup > 0) {
                $query = 'SELECT *
                            FROM
                                #__ak_stats
                            WHERE
                                tag <> "restorepoint"
                            ORDER BY `backupstart` DESC LIMIT 1 ';

                $db->setQuery($query);
                $lastBackup = $db->loadObjectList();
            }
        } else {
            $lastBackup = '';
        }

        $data = array($cache,
                      'Currently_Installed_Version' => JVERSION,
                      'PHP_VERSION'                 => PHP_VERSION,
                      'hasAkeebaBackup'             => $this->hasAkeebaBackup(),
                      'lastBackupDetails'           => $lastBackup);

        bfEncrypt::reply('success', array(
            'msg' => json_encode($data)
        ));
    }

    /**
     * Checks if the site has Akeeba Backup 3.1 or later installed
     *
     * @return  boolean  True if Akeeba Backup is installed and enabled
     */
    public function hasAkeebaBackup()
    {
        // Is the component installed, at all?
        JLoader::import('joomla.filesystem.folder');

        if (!JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeeba')) {
            return FALSE;
        }

        // Make sure the component is enabled
        JLoader::import('cms.component.helper');
        $component = JComponentHelper::getComponent('com_akeeba', TRUE);

        if (!$component->enabled) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Returns information about whether we need to update Joomla!
     *
     * @param   boolean $force Set to true to forcibly reload from the network
     *
     * @return  object
     */
    public function getUpdateInfo($force = FALSE)
    {
        static $updateInfo = NULL;

        if (!empty($updateInfo) && !$force) {
            return $updateInfo;
        }

        $updateInfo = array(
            'status'    => FALSE,
            'source'    => 'none',
            'installed' => NULL,
            'current'   => NULL,
            'sts'       => NULL,
            'lts'       => NULL,
            'test'      => NULL,
        );

        $data = $this->getAllUpdates($force);

        if (empty($data)) {
            return (object)$updateInfo;
        }

        $updateInfo = (object)array_merge($updateInfo, $data);

        // Get the minnotify setting
        $params    = JComponentHelper::getParams('com_cmsupdate');
        $minnotify = $params->get('minnotify', 'current');

        $provider = new AcuUpdateProviderJoomla();
        $jVersion = $provider->sanitiseVersion(JVERSION);

        // We trigger an update only when there is a new release of the minimum specified stability available for download
        switch ($minnotify) {
            case 'test':
                // Do we have a testing release?
                if (!empty($updateInfo->test['version']) && ($updateInfo->test['version'] != $jVersion)) {
                    $updateInfo->status = TRUE;
                    $updateInfo->source = 'test';
                    break;
                }
            // Do not break; we have to fall through the rest of the switch

            case 'lts':
                // Do we have an lts release?
                if (!empty($updateInfo->lts['version']) && ($updateInfo->lts['version'] != $jVersion)) {
                    $updateInfo->status = TRUE;
                    $updateInfo->source = 'lts';
                    break;
                }
            // Do not break; we have to fall through the rest of the switch

            case 'sts':
                // Do we have an sts release?
                if (!empty($updateInfo->sts['version']) && ($updateInfo->sts['version'] != $jVersion)) {
                    $updateInfo->status = TRUE;
                    $updateInfo->source = 'sts';
                    break;
                }
            // Do not break; we have to fall through the rest of the switch

            case 'current':
                // Do we have a current branch release?
                if (!empty($updateInfo->current['version']) && ($updateInfo->current['version'] != $jVersion)) {
                    $updateInfo->status = TRUE;
                    $updateInfo->source = 'current';
                    break;
                }
                break;
        }

        return $updateInfo;
    }

    /**
     * Returns an array with the configured FTP options
     *
     * @return  array
     */
    public function getFTPOptions()
    {
        // Initialise from Joomla! Global Configuration
        $config   = JFactory::getConfig();
        $retArray = array(
            'enable'  => $config->get('ftp_enable', 0),
            'host'    => $config->get('ftp_host', 'localhost'),
            'port'    => $config->get('ftp_port', '21'),
            'user'    => $config->get('ftp_user', ''),
            'pass'    => $config->get('ftp_pass', ''),
            'root'    => $config->get('ftp_root', ''),
            'tempdir' => $config->get('tmp_path', ''),
        );

        // Get the username and password from the state variables, if it exists
        $stateUser = ''; //$this->getState('user', '', 'raw');
        $statePass = ''; //$this->getState('pass', '', 'raw');

        if (!empty($stateUser)) {
            $retArray['user'] = $stateUser;
        }

        if (!empty($statePass)) {
            $retArray['pass'] = $statePass;
        }

        // Apply the FTP credentials to Joomla! itself
        JLoader::import('joomla.client.helper');
        JClientHelper::setCredentials('ftp', $retArray['user'], $retArray['pass']);

        return $retArray;
    }

    /**
     * Creates the restoration.ini file which is used during the update
     * package's extraction. This file tells Akeeba Restore which package to
     * read and where and how to extract it.
     *
     * @return  boolean  True on success
     */
    public function createRestorationINI()
    {
        // Get a password
        $password = $this->getRandomString(64);

        $this->setState('update_password', $password);

        // Get the absolute path to site's root
        $siteroot = JPATH_SITE;
        $siteroot = str_replace('\\', '/', $siteroot);

        $jreg    = JFactory::getConfig();
        $tempdir = dirname(__FILE__) . '/tmp';
        $file    = dirname(__FILE__) . '/tmp/myjoomla-upgradefile.zip';

        $data = "<?php\ndefined('_AKEEBA_RESTORATION') or die();\n";
        $data .= '$restoration_setup = array(' . "\n";

        $ftpOptions = $this->getFTPOptions();
        $engine     = $ftpOptions['enable'] ? 'hybrid' : 'direct';

        $data .= <<<ENDDATA
	'kickstart.security.password' => '$password',
	'kickstart.tuning.max_exec_time' => '5',
	'kickstart.tuning.run_time_bias' => '75',
	'kickstart.tuning.min_exec_time' => '0',
	'kickstart.procengine' => '$engine',
	'kickstart.setup.sourcefile' => '{$tempdir}/myjoomla-upgradefile.zip',
	'kickstart.setup.destdir' => '$siteroot',
	'kickstart.setup.restoreperms' => '0',
	'kickstart.setup.filetype' => 'zip',
	'kickstart.setup.dryrun' => '0'
ENDDATA;

        if ($ftpOptions['enable']) {
            // Get an instance of the FTP client
            JLoader::import('joomla.client.ftp');

            if (version_compare(JVERSION, '3.0', 'ge')) {
                $ftp = JClientFTP::getInstance(
                    $ftpOptions['host'], $ftpOptions['port'], array('type' => FTP_BINARY),
                    $ftpOptions['user'], $ftpOptions['pass']
                );
            } else {
                $ftp = JFTP::getInstance(
                    $ftpOptions['host'], $ftpOptions['port'], array('type' => FTP_BINARY),
                    $ftpOptions['user'], $ftpOptions['pass']
                );
            }

            // Is the tempdir really writable?
            $writable = @is_writeable($tempdir);

            if ($writable) {
                // Let's be REALLY sure
                $fp = @fopen($tempdir . '/test.txt', 'w');
                if ($fp === FALSE) {
                    $writable = FALSE;
                } else {
                    fclose($fp);
                    unlink($tempdir . '/test.txt');
                }
            }

            // If the tempdir is not writable, create a new writable subdirectory
            if (!$writable) {
                JLoader::import('joomla.filesystem.folder');

                $dest = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $tempdir . '/cmsupdate'), '/');

                if (!@mkdir($tempdir . '/cmsupdate')) {
                    $ftp->mkdir($dest);
                }

                if (!@chmod($tempdir . '/cmsupdate', 511)) {
                    $ftp->chmod($dest, 511);
                }

                $tempdir .= '/cmsupdate';
            }

            // Just in case the temp-directory was off-root, try using the default tmp directory
            $writable = @is_writeable($tempdir);

            if (!$writable) {
                $tempdir = JPATH_ROOT . '/tmp';

                // Does the JPATH_ROOT/tmp directory exist?
                if (!is_dir($tempdir)) {

                    JLoader::import('joomla.filesystem.file');
                    JFolder::create($tempdir, 511);

                    $htAccessContents = "order deny,allow\ndeny from all\nallow from none\n";
                    JFile::write($tempdir . '/.htaccess', $htAccessContents);
                }

                // If it exists and it is unwritable, try creating a writable cmsupdate subdirectory
                if (!is_writable($tempdir)) {
                    JLoader::import('joomla.filesystem.folder');

                    $dest = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $tempdir . '/cmsupdate'), '/');
                    if (!@mkdir($tempdir . '/cmsupdate')) {
                        $ftp->mkdir($dest);
                    }
                    if (!@chmod($tempdir . '/cmsupdate', 511)) {
                        $ftp->chmod($dest, 511);
                    }

                    $tempdir .= '/cmsupdate';
                }
            }

            // If we still have no writable directory, we'll try /tmp and the system's temp-directory
            $writable = @is_writeable($tempdir);

            if (!$writable) {
                if (@is_dir('/tmp') && @is_writable('/tmp')) {
                    $tempdir = '/tmp';
                } else {
                    // Try to find the system temp path
                    $tmpfile = @tempnam("dummy", "");
                    $systemp = @dirname($tmpfile);
                    @unlink($tmpfile);

                    if (!empty($systemp)) {
                        if (@is_dir($systemp) && @is_writable($systemp)) {
                            $tempdir = $systemp;
                        }
                    }
                }
            }

            $data .= <<<ENDDATA
	,
	'kickstart.ftp.ssl' => '0',
	'kickstart.ftp.passive' => '1',
	'kickstart.ftp.host' => '{$ftpOptions['host']}',
	'kickstart.ftp.port' => '{$ftpOptions['port']}',
	'kickstart.ftp.user' => '{$ftpOptions['user']}',
	'kickstart.ftp.pass' => '{$ftpOptions['pass']}',
	'kickstart.ftp.dir' => '{$ftpOptions['root']}',
	'kickstart.ftp.tempdir' => '$tempdir'
ENDDATA;
        }

        $data .= ');';

        // Remove the old file, if it's there...
        JLoader::import('joomla.filesystem.file');

        $componentPaths          = array();
        $componentPaths['admin'] = dirname(__FILE__) . '/tmp';

        $configpath = $componentPaths['admin'] . '/restoration.php';

        if (file_exists($configpath)) {
            if (!@unlink($configpath)) {
                JFile::delete($configpath);
            }
        }

        // Write the new file. First try directly.
        if (function_exists('file_put_contents')) {
            $result = @file_put_contents($configpath, $data);
            if ($result !== FALSE) {
                $result = TRUE;
            }
        } else {
            $fp = @fopen($configpath, 'wt');
            if ($fp !== FALSE) {
                $result = @fwrite($fp, $data);
                if ($result !== FALSE) {
                    $result = TRUE;
                }
                @fclose($fp);
            }
        }

        if ($result === FALSE) {
            $result = JFile::write($configpath, $data);
        }

        return $password;
    }

    /**
     * Create a (semi-)random string
     *
     * @param   integer $l Length of the random string, default 32 characters
     * @param   string  $c Character set to pick characters from
     *
     * @return  string  Your random string
     */
    protected function getRandomString($l = 32, $c = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
    {
        for ($s = '', $cl = strlen($c) - 1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i) {
            ;
        }

        return $s;
    }

    /**
     * Confuse FOF integration
     * @return bool
     */
    protected function setState()
    {
        return TRUE;
    }

}

// init this class
$upgradeController = new bfUpgrade($dataObj);

// Run the tool method
$upgradeController->run();
