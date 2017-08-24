<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die();

use Akeeba\TicketSystem\Admin\Helper\Credits;

JLoader::import('joomla.plugin.plugin');

class plgAtsAlphauserpoints extends JPlugin
{
	public function onTicketsBeforeAdd($ticket, $category, $user)
	{
		$this->loadLanguage();

		// Should I run?
		if(!$this->performChecks($ticket, $category, $user))
		{
			// There is no error, simply I don't have to run, so return true
			return true;
		}

		$result = $this->convertAUPinATScredits($ticket, $category, $user);

		return $result;
	}

	private function convertAUPinATScredits($ticket, $category, $user)
	{
        $container     = FOF30\Container\Container::getInstance('com_ats');
		$consumePoints = $this->params->get('consumePoints');
		$giveCredits   = $this->params->get('giveCredits');

		if(!$giveCredits || !$consumePoints)
		{
			$ticket->setError(JText::_('PLG_ATS_ALPHAUSERPOINTS_MISSING_PARAMS'));
			return false;
		}

		// User has not enough credits to post a public or private ticket, let's convert
		// points into credits.
		// P.A. I don't know which kind of ticket will be created, so I check vs both types
		if( !Credits::haveEnoughCredits($user->id, $category->id, true, true) ||
			!Credits::haveEnoughCredits($user->id, $category->id, true, false))
		{
			$AUP_points = AlphaUserPointsHelper::getCurrentTotalPoints('', $user->id);

			if($AUP_points)
			{
				$units = floor($AUP_points / $consumePoints);

				// Not enough points, do nothing
				if(!$units)
				{
					return true;
				}

				$newCredits 	= $giveCredits * $units;
				$subtractPoints = $units * $consumePoints * -1;

				// Create a new transaction
                /** @var \Akeeba\TicketSystem\Site\Model\CreditTransactions $trans */
                $trans = $container->factory->model('Credittransactions')->tmpInstance();

				$trans->ats_credittransaction_id = null;
				$trans->type 					 = 'alphauserpoints';
				$trans->user_id 				 = $user->id;
				$trans->value					 = $newCredits;
				$trans->enabled					 = 1;

				if(!$trans->store())
				{
					return false;
				}

				// Subtract to AUP user
				// P.A. at the moment AUP API are bugged, the won't return the state of the transaction! ;(
				AlphaUserPointsHelper::newpoints('plgaup_ats_convert_points', '', '', 'ATS point convertion', $subtractPoints);

				// Purge credit cache
				Credits::purgeCache($user->id);
			}
		}

		return true;
	}

	private function performChecks($ticket, $category, $user)
	{
        if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
        {
            return false;
        }

		if(!file_exists(JPATH_ROOT.'/components/com_alphauserpoints/helper.php'))
		{
			// There is no AlphaUserPoint component, it's useless to continue
			return false;
		}

		require_once JPATH_ROOT.'/components/com_alphauserpoints/helper.php';

		// Fires only on new tickets
		if($ticket->ats_ticket_id)
		{
			return false;
		}

		return true;
	}
}
