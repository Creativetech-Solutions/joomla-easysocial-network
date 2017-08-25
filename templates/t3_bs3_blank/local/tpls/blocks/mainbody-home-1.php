<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

if (is_array($this->getParam('skip_component_content')) &&
        in_array(JFactory::getApplication()->input->getInt('Itemid'), $this->getParam('skip_component_content')))
    return;
?>

<?php if ($this->countModules('home-1')) : ?>
    <!-- HOME SL 1 -->
    <div class="homeblocks home-1 create-collaborate <?php $this->_c('home-1') ?>">
        <div class="circle"></div>

        <div class="container">
            <jdoc:include type="modules" name="<?php $this->_p('home-1') ?>" style="raw" />
        </div>
    </div>
    <!-- //HOME SL 1 -->
<?php endif ?>

<?php if ($this->countModules('home-2')) : ?>
    <!-- HOME SL 2 -->
    <div class="homeblocks home-2 talent-kinds <?php $this->_c('home-2') ?>">
        <div class="circle"></div>

        <div class="container">
            <jdoc:include type="modules" name="<?php $this->_p('home-2') ?>" style="raw" />
        </div>
    </div>
    <!-- //HOME SL 2 -->
<?php endif ?>

<?php if ($this->countModules('home-3')) : ?>
    <!-- HOME SL 3 -->
    <!--    <script>
        jQuery(function () {
            jQuery('.row .eq-height').matchHeight();
        });
    </script>-->
    <div class="homeblocks home-3 why-dowalo <?php $this->_c('home-3') ?>">
        <div class="container">
            <jdoc:include type="modules" name="<?php $this->_p('home-3') ?>" style="raw" />
        </div>
        <div class="circle"></div>

    </div>
    <!-- //HOME SL 3 -->
<?php endif ?>

<?php if ($this->countModules('home-4')) : ?>
    <!-- HOME SL 4 -->

    <div class="homeblocks home-4 find-need <?php $this->_c('home-4') ?>">
            <div class="bg-image container"><img class="img-responsive" src="images/template/dowalo_demo.png"/></div>

        <div class="container">
            <jdoc:include type="modules" name="<?php $this->_p('home-4') ?>" style="raw" />
        </div>
    </div>
    <!-- //HOME SL 4 -->
<?php endif ?>

<?php if ($this->countModules('home-5')) : ?>
    <!-- HOME SL 5 -->
    <div class="homeblocks home-5 get-social <?php $this->_c('home-5') ?>">
        <div class="container">
            <jdoc:include type="modules" name="<?php $this->_p('home-5') ?>" style="raw" />
        </div>
        <div class="circle"></div>
    </div>
    <!-- //HOME SL 5 -->
<?php endif ?>

