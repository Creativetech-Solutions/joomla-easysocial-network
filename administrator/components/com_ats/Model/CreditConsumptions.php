<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Class CreditConsumptions
 *
 * @property    int $ats_credittransaction_id    Transaction ID linked to this consumption
 * @property    int $consumed                    Amount of consumed credits
 *
 * Filters:
 *
 * @method  $this   sum($flag)              Should I sum all the transactions?
 * @method  $this   transaction_ids($ids)   Filter the result by transactions id (credit balance increase)
 * @method  $this   ats_ticket_id($id)      Filter by ticket id
 * @method  $this   ats_post_id($id)        Filter by post id
 * @method  $this   type($type)             Type of charge (ticket or post)
 */
class CreditConsumptions extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
    }

    public function buildQuery($overrideLimits = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($overrideLimits);

        if($sum = $this->getState('sum', null, 'int'))
        {
            $this->addKnownField('consumed', 0, 'int');

            $query->clear('select')
                ->select(array(
                    $db->qn('ats_creditconsumption_id'),
                    $db->qn('ats_credittransaction_id'),
                    'SUM('.$db->qn('value').') AS '.$db->qn('consumed')
                ))
                ->group($db->qn('ats_credittransaction_id'));
        }

        if($tids = $this->getState('transaction_ids', array(), 'array'))
        {
            $query->where($db->qn('ats_credittransaction_id').' IN('.implode(',', $tids).')');
        }

        return $query;
    }
}