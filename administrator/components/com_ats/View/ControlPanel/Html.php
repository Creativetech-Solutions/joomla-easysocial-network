<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\View\ControlPanel;

use JText;

defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
    protected $needsdlid;
    protected $needsMenuItem;
    protected $akeebaCommonDateObsolescence;
    protected $akeebaCommonDatePHP;
    protected $statsIframe;

    protected function onBeforeMain($tpl = null)
	{
        $platform = $this->container->platform;

        /** @var \Akeeba\TicketSystem\Admin\Model\ControlPanel $model */
        $model = $this->getModel();
        /** @var \Akeeba\TicketSystem\Admin\Model\Stats $stats */
        $stats = $this->container->factory->model('Stats')->tmpInstance();

		$this->akeebaCommonDatePHP          = $platform->getDate('2015-09-03 00:00:00', 'GMT')->format(JText::_('DATE_FORMAT_LC1'));
		$this->akeebaCommonDateObsolescence = $platform->getDate('2016-06-03 00:00:00', 'GMT')->format(JText::_('DATE_FORMAT_LC1'));
        $this->needsdlid                    = $model->needsDownloadID();
        $this->needsMenuItem                = $model->needsCategoriesMenu();
        $this->statsIframe                  = $stats->collectStatistics(true);
	}
}