<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// get params
$sitename = $this->params->get('sitename');
$slogan = $this->params->get('slogan', '');
$logotype = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';
$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;

if (!$sitename) {
    $sitename = JFactory::getConfig()->get('sitename');
}

$logosize = 'col-xs-3 col-sm-2';
$headright = $this->countModules('head-search or header_links or signin or languageswitcherload or profile_links');
$urlmain = JFactory::getUser()->guest ? JUri::base() : FRoute::dashboard();

$classtext = '-user';
 if (JFactory::getUser()->guest) {
     $classtext = 'guest'.$classtext;
 }
 else{
     $classtext = 'registered'.$classtext;
 }
 
 $input = JFactory::getApplication()->input;
 $option = $input->get('option',false);
 $view = $input->get('view',false);
 $layout = $input->get('layout',false);
 $clayout = $input->get('clayout',false);
 $menuViews = array('profile','apps');
 $menuLayouts = array('edit','timeline');
 $cmenuLayouts = array('form');
 $showMenu = $option=="com_easysocial" && in_array($view, $menuViews) && (in_array($layout, $menuLayouts) || in_array($clayout, $cmenuLayouts));
?>
<!-- HEADER -->
<div id="header_container" <?='class="'.$classtext.'"'?>>


    <header id="t3-header" class="container t3-header">
        <div class="row">

            <?php
            if (JFactory::getUser()->guest) {
                ?>
                <a href="javascript:void(0)" id="menu_pull"></a>
                <?php
            } else if($showMenu) {
                ?>
                <a id="sidebar-toggle" data-target="#es-sidebar" data-toggle="collapse"></a>
                <?php
            }
            ?>
            <?php if ($this->countModules('header_links_mobile')) : ?>
                <!-- Header Links Mobile -->
                <div class="header_links_mobile">
                    <jdoc:include type="modules" name="<?php $this->_p('header_links_mobile') ?>" style="raw" />
                </div>
                <!-- //Header links Mobile-->
            <?php endif ?>
            <!-- LOGO -->
            <div class="<?php echo $logosize ?> logo <?=$showMenu? 'menu-yes': ''?>">
                <div class="logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
                    <a href="<?php echo $urlmain ?>" title="<?php echo strip_tags($sitename) ?>">
                        <?php if ($logoimgsm) : ?>
                            <img class="logo-img-sm" src="<?php echo JUri::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
                        <?php endif ?>
                        <span><?php echo $sitename ?></span>
                    </a>
                    <small class="site-slogan"><?php echo $slogan ?></small>
                </div>
            </div>
            <!-- //LOGO -->
            <?php if ($this->countModules('head-search-mobile')) : ?>
                <!-- HEAD SEARCH -->
                <div class="serch-div-res" style="display: none;">
                    <jdoc:include type="modules" name="<?php $this->_p('head-search-mobile') ?>" style="raw" />
                </div>
                <!-- //HEAD SEARCH -->
            <?php endif ?>


            <?php if ($headright): ?>
                <div class="col-xs-9 col-sm-10 pull-right">
                    <?php if ($this->countModules('languageswitcherload')) : ?>
                        <!-- LANGUAGE SWITCHER -->
                        <div class="languageswitcherload">
                            <jdoc:include type="modules" name="<?php $this->_p('languageswitcherload') ?>" style="raw" />
                        </div>
                        <!-- //LANGUAGE SWITCHER -->
                    <?php endif ?>

                    <?php if ($this->countModules('signin')) : ?>
                        <!-- SIGN IN -->
                        <div class="sign_up_login">
                            <jdoc:include type="modules" name="<?php $this->_p('signin') ?>" style="raw" />
                        </div>
                        <!-- //SIGN IN -->
                    <?php endif ?>  
                    <?php if ($this->countModules('profile_links')) : ?>
                        <!-- Header Links -->
                        <div class="profile_links">
                            <jdoc:include type="modules" name="<?php $this->_p('profile_links') ?>" style="raw" />
                        </div>
                        <!-- //Profile links -->
                    <?php endif ?>
                    <?php if ($this->countModules('head-search')) : ?>
                        <!-- HEAD SEARCH -->
                        <div class="head-search <?php $this->_c('head-search') ?>">
                            <jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />
                        </div>
                        <!-- //HEAD SEARCH -->
                    <?php endif ?>
                    <span class="sb-icon-search-mob serch-div">
                        <i class="fa fa-search"></i>
                    </span>
                    <?php if ($this->countModules('header_links')) : ?>
                        <!-- Header Links -->
                        <div class="header_links">
                            <jdoc:include type="modules" name="<?php $this->_p('header_links') ?>" style="raw" />
                        </div>
                        <!-- //Header links -->
                    <?php endif ?>


                </div>
            <?php endif ?>

        </div>
    </header>
</div>
<!-- //HEADER -->
