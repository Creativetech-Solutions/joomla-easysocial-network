<?php
/**
 * @package      Crowdfunding
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

// no direct access
defined('_JEXEC') or die;

class CrowdfundingModelImport extends JModelForm
{
    protected $ignoredFiles = array('readme.txt');

    protected function populateState()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        // Load the filter state.
        $value = $app->getUserStateFromRequest('import.context', 'type', 'currencies');
        $this->setState('import.context', $value);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interrogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm|bool   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.import', 'import', array('control' => 'jform', 'load_data' => $loadData));
        if (!$form) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.import.data', array());

        return $data;
    }

    public function extractFile($file, $destFolder)
    {
        // extract type
        $zipAdapter = JArchive::getAdapter('zip');
        $zipAdapter->extract($file, $destFolder);

        $dir = new DirectoryIterator($destFolder);

        $filePath = '';

        foreach ($dir as $fileinfo) {
            $currentFileName = StringHelper::strtolower($fileinfo->getFilename());

            if (!$fileinfo->isDot() and !in_array($currentFileName, $this->ignoredFiles, true)) {
                $filePath = JPath::clean($destFolder . DIRECTORY_SEPARATOR . JFile::makeSafe($fileinfo->getFilename()));
                break;
            }
        }

        return $filePath;
    }

    public function uploadFile($uploadedFileData, $type)
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        jimport('joomla.filesystem.archive');

        $uploadedFile = ArrayHelper::getValue($uploadedFileData, 'tmp_name');
//        $uploadedName = ArrayHelper::getValue($uploadedFileData, 'name');
        $errorCode    = ArrayHelper::getValue($uploadedFileData, 'error');

        // Prepare size validator.
        $KB       = pow(1024, 2);
        $fileSize = ArrayHelper::getValue($uploadedFileData, 'size', 0, 'int');

        $mediaParams   = JComponentHelper::getParams('com_media');
        /** @var $mediaParams Joomla\Registry\Registry */

        $uploadMaxSize = $mediaParams->get('upload_maxsize') * $KB;

        // Prepare size validator.
        $sizeValidator = new Prism\File\Validator\Size($fileSize, $uploadMaxSize);

        // Prepare server validator.
        $serverValidator = new Prism\File\Validator\Server($errorCode, array(UPLOAD_ERR_NO_FILE));

        $file = new Prism\File\File($uploadedFile);
        $file->addValidator($sizeValidator);
        $file->addValidator($serverValidator);

        // Validate the file
        if (!$file->isValid()) {
            throw new RuntimeException($file->getError());
        }

        // Upload the file.
        $rootFolder      = JPath::clean($app->get('tmp_path'), '/');
        $filesystemLocal = new Prism\Filesystem\Adapter\Local($rootFolder);
        $sourceFile      = $filesystemLocal->upload($uploadedFileData);

        $fileName        = basename($sourceFile);

        // Extract file if it is archive
        $ext = StringHelper::strtolower(JFile::getExt($fileName));
        if (strcmp($ext, 'zip') === 0) {
            $destinationFolder = JPath::clean($app->get('tmp_path') .'/'. $type, '/');
            if (JFolder::exists($destinationFolder)) {
                JFolder::delete($destinationFolder);
            }

            $filePath = $this->extractFile($sourceFile, $destinationFolder);
        } else {
            $filePath = $sourceFile;
        }

        return $filePath;
    }

    /**
     *
     * Import currencies from XML file.
     * The XML file is generated by the current extension ( Crowdfunding )
     *
     * @param string $file    A path to file
     * @param bool   $resetId Reset existing IDs with new ones.
     */
    public function importCurrencies($file, $resetId = false)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        if (!empty($content)) {
            // Check for existed currencies.
            $db    = $this->getDbo();
            $query = $db->getQuery(true);
            $query
                ->select('COUNT(*)')
                ->from($db->quoteName('#__crowdf_currencies', 'a'));

            $db->setQuery($query);
            $result = $db->loadResult();

            if (!empty($result)) { // Update current currencies and insert newest.
                $this->updateCurrencies($content);
            } else { // Insert new ones
                $this->insertCurrencies($content, $resetId);
            }
        }
    }

    protected function insertCurrencies($content, $resetId)
    {
        $items = array();

        $db = $this->getDbo();

        // Generate data for importing.
        foreach ($content as $item) {
            $title = StringHelper::trim($item->title);
            $code  = StringHelper::trim($item->code);
            if (!$title or !$code) {
                continue;
            }

            $id = (!$resetId) ? (int)$item->id : 'null';

            $items[] = $id . ',' . $db->quote($title) . ',' . $db->quote($code) . ',' . $db->quote(StringHelper::trim($item->symbol)) . ',' . (int)$item->position;
        }

        $query = $db->getQuery(true);

        $query
            ->insert('#__crowdf_currencies')
            ->columns('id, title, code, symbol, position')
            ->values($items);

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Update the currencies with new columns.
     *
     * @param array $content
     */
    protected function updateCurrencies($content)
    {
        JLoader::register('CrowdfundingTableCurrency', JPATH_ADMINISTRATOR .'/components/com_crowdfunding/tables/currency.php');
        $db = $this->getDbo();

        foreach ($content as $item) {
            $code = StringHelper::trim($item->code);

            $keys = array('code' => $code);

            $table = new CrowdfundingTableCurrency($db);
            $table->load($keys);

            if (!$table->get('id')) {
                $table->set('title', StringHelper::trim($item->title));
                $table->set('code', $code);
                $table->set('position', 0);
            }

            // Update the symbol if missing.
            if (!$table->get('symbol') and !empty($item->symbol)) {
                $table->set('symbol', StringHelper::trim($item->symbol));
            }

            $table->store();
        }
    }

    /**
     * Import locations from TXT or XML file.
     * The TXT file comes from geodata.org
     * The XML file is generated by the current extension ( Crowdfunding )
     *
     * @param string $file    A path to file
     * @param array  $options
     */
    public function importLocations($file, array $options)
    {
        $ext = StringHelper::strtolower(JFile::getExt($file));

        if (strcmp($ext, 'xml') === 0) {
            $this->importLocationsXml($file, $options);
        } else { // Import from file.
            $this->importLocationsTxt($file, $options);
        }
    }

    protected function importLocationsTxt($file, array $options)
    {
        if (JFile::exists($file)) {
            $db    = $this->getDbo();

            $items   = array();
            $columns = array('id', 'name', 'latitude', 'longitude', 'country_code', 'timezone');

            $i = 0;
            foreach (Prism\Utilities\FileHelper::getLine($file) as $key => $geodata) {
                $item = mb_split("\t", $geodata);

                // Check for missing ascii characters name
                $name = StringHelper::trim($item[2]);
                if (!$name) {
                    // If missing ascii characters name, use utf-8 characters name
                    $name = StringHelper::trim($item[1]);
                }

                // If missing name, skip the record
                if (!$name) {
                    continue;
                }

                // Filter by population.
                if ($options['minimum_population'] > (int)$item[14]) {
                    continue;
                }

                // Filter by country.
                $countryCode = StringHelper::trim($item[8]);

                if ($options['country_code'] and strcmp($countryCode, $options['country_code']) !== 0) {
                    continue;
                }

                $id = (!$options['reset_id']) ? (int)$item[0] : 'null';

                $items[] =
                    $id . ',' . $db->quote($name) . ',' . $db->quote(StringHelper::trim($item[4])) . ',' .
                    $db->quote(StringHelper::trim($item[5])) . ',' . $db->quote($countryCode) . ',' . $db->quote(StringHelper::trim($item[17]));

                $i++;
                if ($i === 500) {
                    $i = 0;

                    $query = $db->getQuery(true);
                    $query
                        ->insert($db->quoteName('#__crowdf_locations'))
                        ->columns($db->quoteName($columns))
                        ->values($items);

                    $db->setQuery($query);
                    $db->execute();

                    $items   = array();
                }
            }

            if (count($items) > 0) {
                $query = $db->getQuery(true);

                $query
                    ->insert($db->quoteName('#__crowdf_locations'))
                    ->columns($db->quoteName($columns))
                    ->values($items);

                $db->setQuery($query);
                $db->execute();
            }

            unset($content, $items);
        }
    }

    protected function importLocationsXml($file, array $options)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        $columns = array('id', 'name', 'latitude', 'longitude', 'country_code', 'timezone');

        if ($content !== null) {
            $items = array();
            $db    = $this->getDbo();

            $i = 0;
            foreach ($content->location as $item) {
                // Check for missing ascii characters name
                $name = StringHelper::trim($item->name);

                // If missing name, skip the record
                if (!$name) {
                    continue;
                }

                // Reset ID
                $id = ((int)$item->id > 0 and !$options['reset_id']) ? (int)$item->id : 'null';

                $items[] =
                    $id . ',' . $db->quote($name) . ',' . $db->quote(StringHelper::trim($item->latitude)) . ',' . $db->quote(StringHelper::trim($item->longitude)) . ',' .
                    $db->quote(StringHelper::trim($item->country_code)) . ',' . $db->quote(StringHelper::trim($item->timezone));

                $i++;
                if ($i === 500) {
                    $i = 0;

                    $query = $db->getQuery(true);
                    $query
                        ->insert($db->quoteName('#__crowdf_locations'))
                        ->columns($db->quoteName($columns))
                        ->values($items);

                    $db->setQuery($query);
                    $db->execute();

                    $items   = array();
                }
            }

            if (count($items) > 0) {
                $query = $db->getQuery(true);
                $query
                    ->insert($db->quoteName('#__crowdf_locations'))
                    ->columns($db->quoteName($columns))
                    ->values($items);

                $db->setQuery($query);
                $db->execute();
            }

            unset($content, $items, $item);
        }
    }

    /**
     * Import countries from XML file.
     * The XML file is generated by the current extension ( Crowdfunding )
     * or downloaded from https://github.com/umpirsky/country-list
     *
     * @param string $file    A path to file
     * @param bool   $resetId Reset existing IDs with new ones.
     */
    public function importCountries($file, $resetId = false)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        if (!empty($content)) {
            // Check for existed countries.
            $db    = $this->getDbo();
            $query = $db->getQuery(true);
            $query
                ->select('COUNT(*)')
                ->from($db->quoteName('#__crowdf_countries', 'a'));

            $db->setQuery($query);
            $result = $db->loadResult();

            if (!empty($result)) { // Update current countries and insert newest.
                $this->updateCountries($content);
            } else { // Insert new ones
                $this->insertCountries($content, $resetId);
            }
        }
    }

    protected function insertCountries($content, $resetId)
    {
        $items = array();

        $db = $this->getDbo();

        foreach ($content->country as $item) {
            $name = StringHelper::trim($item->name);
            $code = StringHelper::trim($item->code);
            if (!$name or !$code) {
                continue;
            }

            $id = (!$resetId) ? (int)$item->id : 'null';

            $items[] =
                $id . ',' . $db->quote($name) . ',' . $db->quote($code) . ',' . $db->quote($item->locale) . ',' . $db->quote($item->latitude) . ',' .
                $db->quote($item->longitude) . ',' . $db->quote($item->currency) . ',' . $db->quote($item->timezone);
        }

        $columns = array('id', 'name', 'code', 'locale', 'latitude', 'longitude', 'currency', 'timezone');

        $query = $db->getQuery(true);

        $query
            ->insert($db->quoteName('#__crowdf_countries'))
            ->columns($db->quoteName($columns))
            ->values($items);

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Update the countries with new columns.
     *
     * @param SimpleXMLElement $content
     */
    protected function updateCountries($content)
    {
        JLoader::register('CrowdfundingTableCountry', JPATH_ADMINISTRATOR .'/components/com_crowdfunding/tables/country.php');
        $db = $this->getDbo();

        foreach ($content->country as $item) {
            $code = StringHelper::trim($item->code);

            $keys = array('code' => $code);

            $table = new CrowdfundingTableCountry($db);
            $table->load($keys);

            if (!(int)$table->id) {
                $table->set('name', StringHelper::trim($item->name));
                $table->set('code', $code);
            }

            $table->set('locale', StringHelper::trim($item->locale));
            $table->set('latitude', StringHelper::trim($item->latitude));
            $table->set('longitude', StringHelper::trim($item->longitude));
            $table->set('currency', StringHelper::trim($item->currency));
            $table->set('timezone', StringHelper::trim($item->timezone));

            $table->store();
        }
    }

    /**
     * Import states from XML file.
     * The XML file is generated by the current extension.
     *
     * @param string $file A path to file
     */
    public function importStates($file)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        $generator = (string)$content->attributes()->generator;

        switch ($generator) {
            case 'crowdfunding':
                $this->importCrowdfundingStates($content);
                break;

            default:
                $this->importUnofficialStates($content);
                break;
        }
    }

    /**
     * Import states that are based on locations,
     * and which are connected to locations IDs.
     */
    protected function importCrowdfundingStates($content)
    {
        if (!empty($content)) {
            $states = array();
            $db     = $this->getDbo();

            // Prepare data
            foreach ($content->state as $item) {
                // Check for missing state
                $stateCode = StringHelper::trim($item->state_code);
                if (!$stateCode) {
                    continue;
                }

                $id = (int)$item->id;

                $states[$stateCode][] = '(' . $db->quoteName('id') . '=' . (int)$id . ')';

            }

            // Import data
            foreach ($states as $stateCode => $ids) {
                $query = $db->getQuery(true);

                $query
                    ->update($db->quoteName('#__crowdf_locations'))
                    ->set($db->quoteName('state_code') . '=' . $db->quote($stateCode))
                    ->where(implode(' OR ', $ids));

                $db->setQuery($query);
                $db->execute();
            }

            unset($states, $content);
        }
    }

    /**
     * Import states that are based on not official states data,
     * and which are not connected to locations IDs.
     *
     * @param SimpleXMLElement $content
     *
     * @todo remove this in next major version.
     */
    protected function importUnofficialStates($content)
    {
        if (!empty($content)) {
            $states = array();
            $db     = $this->getDbo();

            foreach ($content->city as $item) {
                // Check for missing ascii characters title
                $name = StringHelper::trim($item->name);
                if (!$name) {
                    continue;
                }

                $code = StringHelper::trim($item->state_code);

                $states[$code][] = '(' . $db->quoteName('name') . '=' . $db->quote($name) . ' AND ' . $db->quoteName('country_code') . '=' . $db->quote('US') . ')';
            }

            foreach ($states as $stateCode => $cities) {
                $query = $db->getQuery(true);

                $query
                    ->update('#__crowdf_locations')
                    ->set($db->quoteName('state_code') . ' = ' . $db->quote($stateCode))
                    ->where(implode(' OR ', $cities));

                $db->setQuery($query);
                $db->execute();
            }

            unset($states, $content);
        }
    }

    public function removeAll($resource)
    {
        if (!$resource) {
            throw new InvalidArgumentException('COM_CROWDFUNDING_ERROR_INVALID_RESOURCE_TYPE');
        }

        $db = JFactory::getDbo();

        switch ($resource) {
            case 'countries':
                $db->truncateTable('#__crowdf_countries');
                break;

            case 'currencies':
                $db->truncateTable('#__crowdf_currencies');
                break;

            case 'locations':
                $db->truncateTable('#__crowdf_locations');
                break;
        }
    }
}