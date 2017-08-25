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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <base href="<?php echo JURI::root(); ?>" target="_blank" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
    </head>
    <body style="margin:0;padding:0;background:#161b33;">

        <table style="border-collapse:collapse;min-height:100% !important;width:100% !important;table-layout:fixed;margin:0 auto;background:#161b33;margin:0;padding:50px 0 80px;color:#798796;font-family:'Arial';font-size:14px;">

            <tr>
                <td style="padding-top:20px;padding-bottom: 20px;text-align: center;">
                    <img src="<?php echo JURI::root(); ?>images/template/logo_lg.png" title="Dowalo" alt="dowalo-large-logo" width="359" height="79" />
                </td>
            </tr>
            <tr>
                <td align="center" style="min-height:100% !important;width:100% !important;">

                    <table cellpadding="0" cellspacing="0" border="0" style="width:600px;table-layout:fixed;margin:0 auto;background:#fff;border:1px solid #ededed;border-top-color:#f4f4f4;border-bottom-color:#f4f4f4;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;">
                        <tbody>

                            <?php echo $contents; ?>

                        </tbody>
                    </table>

                </td>
            </tr>
            <tr>
                <td style="padding-top:20px;padding-bottom: 20px;text-align: center;">
                    <img src="<?php echo JURI::root(); ?>images/logo-small.png" width="29" height="29" title="Dowalo" alt="dowalo-small-logo" style=" vertical-align: middle;" />
                    <span style="color: white;font-size: 14px;padding-left: 10px;">Dowalo Inc.</span>
                </td>
            </tr>
        </table>

    </body>
</html>
