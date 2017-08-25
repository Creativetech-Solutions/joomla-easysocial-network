<?php
/**
* @package   JE Tour component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
/*JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');*/
JHtml::_('behavior.modal','a.modalaudio');
?>


<div data-es-audios class="es-container es-audios" data-audios-listing>
    <div class="es-content">
        
       	<?php /*?><form action="<?php //echo JRoute::_($this->request_url) ?>" method="post" name="eventForm" id="eventForm" enctype="multipart/form-data"> 
            <div class="col50 traininglisting">
            
            <h1 class="page_title"><?php echo JText::_( 'Audios' ); ?></h1>
             
            <?php $link 	= JRoute::_( 'index.php?tmpl=component&option='.$option.'&view=audio&set=1' );  ?>
            <p><?php echo JText::_('COM_COMMUNITY_UPLOAD_AUDIO');?></p>
            <a class='modalaudio' href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 700, y: 550}}" >
                <?php echo JText::_('COM_COMMUNITY_UPLOAD_AUDIO_FILE'); ?>
            </a>
            
            
            
            <div class="clr"></div>
             
            <input type="hidden" name="ordering" value="id" />
            <input type="hidden" name="cid[]" value="" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="view" value="audio" />
        </form><?php */?>

        <div class="es-audio-listing es-audio-item-group" data-audios-result>
        	<div class="col-md-12">
                <div class="col-md-3">
                    <div class="es-widget-create mr-10">
                        <a class="btn btn-es-primary btn-create btn-block" href="<?php echo $createLink;?>">
							<?php echo JText::_('COM_EASYSOCIAL_ADD_AUDIO');?>
                        </a>
                    </div>
                </div> 
            </div>
            <div class="col-md-12">
				<?php 
                echo $this->output('site/audio/default.featured.items', array('featuredAudios' => $featuredAudios, 'returnUrl' => $returnUrl));
                ?>
            </div>
            <div class="col-md-12 other-audios">
				<?php 
                echo $this->output('site/audio/default.items', array('audios' => $audios, 'returnUrl' => $returnUrl)); 
                ?>
            </div>
        </div>

    </div>
</div>


