<?php
/**
 * @package      EasySocial
 * @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');

$middleName = ($middleName) ? ' '.$middleName : '';
$lastName = ($lastName) ? ' '.$lastName : '';
?>
<tr>
    <td style="padding: 24px 35px 0;">
        <div style="margin-bottom:15px;">
            <div style="font-family:Trebuchet MS;font-size:32px;font-weight:normal;color:#000000;display:block; margin: 4px 0">
                <?php echo JText::_('COM_EASYSOCIAL_EMAILS_HELLO'); ?>
                <span style="font-style:italic"><?php echo $firstName.$middleName.$lastName. ','; ?></span>
            </div>
            <div style="font-size:16px; font-family:Trebuchet MS;color: #696060;">
                <?php echo JText::_('COM_EASYSOCIAL_EMAIL_VERIFY_WELCOME_TEXT'); ?>
            </div>
        </div>
    </td>
</tr>


<tr>
    <td style="padding: 0px 35px 0px;">

        <div style="text-align:center;display:block;border: 1px solid #898686;">

        </div>

        <p style="color: #393939;">
            <?php echo JText::sprintf('COM_EASYSOCIAL_EMAILS_VERIFY_REGISTRATION_THANK_YOU_FOR_REGISTERING', $email); ?>
        </p>
        <span style="text-align:center;display: block;">
            <a href="<?php echo $activation; ?>" style="
               display:inline-block;
               text-decoration:none;
               font-weight:normal;
               padding:10px 15px;
               line-height:20px;
               color:#fff;
               font-size: 12px;
               background-color: #fd5f11;
               background-image: linear-gradient(to bottom, #fd5f11, #fd5f11);
               background-repeat: no-repeat;
               border-color: #c54809;
               border-style: solid;
               border-width: 1px;
               box-shadow: 0px 0px 0px;
               border-radius:2px;
               -moz-border-radius:2px;
               -webkit-border-radius:2px;
               "><?php echo JText::_('COM_EASYSOCIAL_EMAILS_ACTIVATE_NOW'); ?></a>
        </span>
        <p style="color: #393939;">
            <?php echo JText::_('COM_EASYSOCIAL_EMAILS_VERIFY_REGISTRATION_THANK_YOU_FOR_REGISTERING_SECOND_PART'); ?>
        </p>
        <p style="color: #393939;">
            Rock on, <br />
            Team Dowalo
        </p>
    </td>
</tr>