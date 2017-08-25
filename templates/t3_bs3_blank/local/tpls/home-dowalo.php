<?php
/**
 * ------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 * ------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 * ------------------------------------------------------------------------------
 */
defined('_JEXEC') or die;

$user = JFactory::getUser();
$app = JFactory::getApplication();
if(!$user->guest){
    $urldashboard = FRoute::dashboard();
    //$urlmain = $app->redirect($urldashboard);
}
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
      class='<jdoc:include type="pageclass" />'>

    <head>
    <jdoc:include type="head" />
    <?php $this->loadBlock('head') ?>
    <?php $this->addCss('layouts/home-dowalo') ?>
</head>

<body>

    <div class="header_and_slider">
        <?php $this->loadBlock('header-dowalo') ?>
        <div id="main_slider">
            <?php $this->loadBlock('slider') ?>
        </div>
    </div>
    <?php $this->loadBlock('mainbody-home-1') ?>

    <?php $this->loadBlock('footer-home') ?>



</body>
</html>