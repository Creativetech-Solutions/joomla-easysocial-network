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

//load certain css for the views
$input = JFactory::getApplication()->input;
$view = $input->get("view", false);
$component = $input->get("option", false);

if($component=="com_easysocial"){
     require_once 'dowalo-easysocial.php';
}
else{
   

?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
      class='<jdoc:include type="pageclass" />'>

    <head>
    <jdoc:include type="head" />
    <?php $this->loadBlock('head') ?>
    <?php
    if ($component == "com_easysocial") {
        $this->addCss('layouts/'.$view);
    }
    if (JRequest::getVar('option') == 'com_sppagebuilder') {
        $this->addCss('layouts/how-it-works-page');
    }
    ?>
</head>

<body>
    <div class="header_and_slider">
        <?php $this->loadBlock('header-dowalo') ?>
    </div>
        <?php $this->loadBlock('mainbody') ?>
        <?php $this->loadBlock('footer-default') ?>
</body>
</html>
<?php }
?>