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
 * Class CreditTransactions
 *
 * @property    int     ats_credittransaction_id    Primary Key
 * @property    string  type                        Type of the transaction, by default "ats"
 * @property    string  transaction_date            Date of the transaction
 * @property    string  unique_id                   ID of the transaction
 * @property    int     user_id                     Linked user
 * @property    int     value                       Value of the transaction
 *
 * Filters
 *
 * @method  $this   user_id($id)   Filter by user id
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class CreditTransactions extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
    }

    public function check()
    {
        $this->assertNotEmpty($this->user_id, 'COM_ATS_CREDITTRANSACTIONS_ERR_USER_ID');

        if(!intval($this->transaction_date))
        {
            $jNow = $this->container->platform->getDate();
            $this->transaction_date = $jNow->toSql();
        }

        if(empty($this->type))
        {
            $this->type = 'ats';
        }

        if(empty($this->unique_id))
        {
            $this->unique_id = $this->user_id.'_';

            if(function_exists('sha1'))
            {
                $this->unique_id .= sha1(microtime(true));
            }
            elseif(function_exists('md5'))
            {
                $this->unique_id .= md5(microtime(true));
            }
            else
            {
                $this->unique_id .= microtime(true);
            }
        }

        parent::check();
    }
}