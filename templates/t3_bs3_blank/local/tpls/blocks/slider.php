<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if ($this->countModules('home-0')) :
    ?>
    <!-- HOME SL 1 -->
        <jdoc:include type="modules" name="<?php $this->_p('home-0') ?>" style="raw" />
    <!-- //HOME SL 1 -->
    <?php
 endif ?>