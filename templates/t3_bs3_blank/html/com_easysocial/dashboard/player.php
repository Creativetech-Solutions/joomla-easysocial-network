<?php
/**
* @package   JE Tour component
* @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/

defined('_JEXEC') or die('Restricted access');
?>
<div class="audio-player pull-left">
    <audio id="audio-player<?php echo $audio->id;?>" src="<?php echo JURI::base(); ?>media/com_easysocial/audios/<?php echo $audio->id.'/'.$audio->file_title; ?>" type="audio/mp3" controls="controls"></audio>
    <script type="text/javascript">
            //jQuery.noConflict();
            jQuery(function(){
              jQuery('#audio-player<?php echo $audio->id;?>').mediaelementplayer({
                alwaysShowControls: false,
                features: ['playpause'],
                audioVolume: 'horizontal',
                audioWidth: 50,
                audioHeight: 50,
                iPadUseNativeControls: false,
                iPhoneUseNativeControls: false,
                AndroidUseNativeControls: false
              });
            });
    </script>
</div><!-- @end .audio-player -->