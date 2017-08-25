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
$layout = $input->get("layout", false);
$component = $input->get("option", false);
$id = $input->get("id", false,'INTEGER');
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
      class='<jdoc:include type="pageclass" />'>
    <head>
    <jdoc:include type="head" />
    <?php $this->loadBlock('head') ?>
    <?php
    if ($component == "com_easysocial") {
        
        if($view=="apps" && $id){
            //get app name
            $db = JFactory::getDbo();
            $query = 'SELECT alias FROM #__social_apps WHERE id='.$id.' AND visible=1';
            $db->setQuery($query);
            $appname = $db->loadResult();
            $view = 'app-'.$appname;
            $layout = $view.'-'.$layout;
        }
        $this->addScript(T3_TEMPLATE_URL.'/js/responsive-toolkit.js');
        $this->addScript(T3_TEMPLATE_URL.'/js/easysocial.js');
        
        $this->addCss('layouts/easysocial');
        $this->addCss('layouts/'.$view);
        $this->addCss('layouts/'.$layout);
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