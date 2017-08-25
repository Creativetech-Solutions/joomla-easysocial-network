<?php
/**
 * @package   JE Tour component
 * @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
 * Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
 * */
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/css/jquery.mCustomScrollbar.css');
$document->addStyleSheet(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/css/hap_mini.css');
JHtml::_('jquery.framework');
$document->addScript(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/js/jquery.mCustomScrollbar.concat.min.js');
$document->addScript(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/js/new_cb.js');
$document->addScript(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/js/new.js');

//$artist = FD::user($audio->user_id)->name;
?>

<script type="text/javascript">

    var hap_player;
    jQuery(document).ready(function () {

        var settings = {
            instanceName: "instance-<?= $audio->id ?>",
            sourcePath: "",
            playlistList: "#hap-playlist-list-<?= $audio->id ?>",
            activePlaylist: "playlist-<?= $audio->id ?>",
            activeItem: 0,
            volume: 0.5,
            autoPlay: false,
            preload: "auto",
            randomPlay: false,
            loopingOn: false,
            autoAdvanceToNextMedia: false,
            youtubeAppId: "",
            soundCloudAppId: "",
            usePlaylistScroll: false,
            playlistScrollOrientation: "vertical",
            playlistScrollTheme: "minimal",
            useTooltips: true,
            useKeyboardNavigationForPlayback: true,
            useDownload: true,
            autoReuseDownloadMail: true,
            useShare: false,
            facebookAppId: "",
            useNumbersInPlaylist: false,
            numberTitleSeparator: ".  ",
            artistTitleSeparator: "<br>",
            sortableTracks: false,
            playlistItemContent: "title,thumb"
        };

        var hap_player = jQuery("#hap-wrapper-<?= $audio->id ?>").hap(settings);
        jQuery("#hap-wrapper-<?= $audio->id ?> .hap-next-seek").click(function () {
            var currentTime = hap_player.getCurrentTime();
            hap_player.seek(parseInt(currentTime + 10));
            console.log(parseInt(currentTime + 10));
            return false;
        });

        jQuery("#hap-wrapper-<?= $audio->id ?> .hap-prev-seek").click(function () {
            var currentTime = hap_player.getCurrentTime();
            console.log(parseInt(currentTime - 10));
            hap_player.seek(parseInt(currentTime - 10));
            return false;
        });
    });

</script>

<!-- player code -->   
<div id="hap-wrapper-<?= $audio->id ?>" style="display:none" class="clearfix hap-player  <?php
if (isset($eqheight) && $eqheight == true) {
    echo "js-eq-height";
}
?> ">

    <div class="hap-player-holder">
        <div class="hap-player-controls">
            <div class="hap-playback-toggle hap-contr-btn"><i class="fa fa-play hap-contr-btn-i hap-icon-color"></i></div>
        </div>
        <div class="player-container">
            <div class="hap-info">
                <p class="hap-player-title"></p>

            </div>
            <div class="hap-seekbar-inner hap-tooltip-item">
                <div class="hap-progress-bg"></div>
                <div class="hap-load-level"></div>
                <div class="hap-progress-level"></div>
            </div>
        </div>
    </div>



    <div class="hap-tooltip"><p></p></div>  
    <div id="hap-playlist-list-<?= $audio->id ?>">
        <ul id="playlist-<?= $audio->id ?>" > 
            <li class="hap-playlist-item" data-type="audio" data-mp3="<?= $audio->getFileUrl() ?>" data-artist="<?= $audio->getAuthor()->getName(); ?>" data-title="<?= $audio->getTitle() ?>" data-thumb="<?= $audio->getThumbnail(true) ?>"  data-download="<?php echo $audio->getFileUrl() ?>"  data-target="_blank"></li>
        </ul>
    </div>
</div>



