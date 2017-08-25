<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer">


    <?php
    $footerarray = array('footer-1', 'footer-2', 'footer-3', 'footer-4');
    if (JRequest::getVar('option') == 'com_sppagebuilder') {
        ?>
        <div class="container">
            <!-- SPOTLIGHT -->
            <div class="t3-spotlight t3-footnav row">
                <?php
                foreach ($footerarray as $i => $moduleposition):
                    ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <?php if ($this->countModules($moduleposition)) : ?>
                            <jdoc:include type="modules" name="<?php echo $moduleposition ?>" style="T3Xhtml"/>

                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </div>
            <!-- SPOTLIGHT -->
        </div>
        <!-- //FOOT NAVIGATION -->
    <?php } ?>
    <section class="t3-copyright text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 copyright <?php $this->_c('footer') ?>">
                    <jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
                </div>

            </div>
        </div>
    </section>

</footer>
<!-- //FOOTER -->