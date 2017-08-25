<?php
/**
 * @package   JE Tour component
 * @copyright Copyright (C) 2009-2010 Joomlaextensions.co.in All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
 * Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
 * */
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.JFactory::getApplication()->getTemplate().DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'plg_mvcoverride'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'mp3.php';

$document = JFactory::getDocument();
$document->addStyleSheet(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/css/jquery.mCustomScrollbar.css');
$document->addStyleSheet(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/css/hap_mini.css');
JHtml::_('jquery.framework');
$document->addScript(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/js/jquery.mCustomScrollbar.concat.min.js');
$document->addScript(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/js/new_cb.js');
$document->addScript(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/js/new.js');

$mp3file = new MP3File(JPATH_BASE.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_easysocial'.DIRECTORY_SEPARATOR.'audios'.DIRECTORY_SEPARATOR.$audio->user_id.DIRECTORY_SEPARATOR.$audio->file_title);
//$artist = FD::user($audio->user_id)->name;
if($featured){
    $audioid = 'featured';
}else{
    $audioid = $audio->id;
}
?>

<script type="text/javascript">

    var hap_player;
    jQuery(document).ready(function () {

        var settings = {
            instanceName: "instance-<?= $audioid ?>",
            sourcePath: "",
            playlistList: "#hap-playlist-list-<?= $audioid ?>",
            activePlaylist: "playlist-<?= $audioid ?>",
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

        var hap_player = jQuery("#hap-wrapper-<?= $audioid ?>").hap(settings);
        jQuery("#hap-wrapper-<?= $audioid ?> .hap-next-seek").click(function () {
            var currentTime = hap_player.getCurrentTime();
            hap_player.seek(parseInt(currentTime + 10));
            console.log(parseInt(currentTime + 10));
            return false;
        });

        jQuery("#hap-wrapper-<?= $audioid ?> .hap-prev-seek").click(function () {
            var currentTime = hap_player.getCurrentTime();
            console.log(parseInt(currentTime - 10));
            hap_player.seek(parseInt(currentTime - 10));
            return false;
        });
    });

</script>

<!-- player code -->   
<div id="hap-wrapper-<?= $audioid ?>" class="clearfix hap-player  <?php
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
    <div style="display: none;" id="hap-playlist-list-<?= $audioid ?>">
        <ul id="playlist-<?= $audioid ?>" >
            <li class="hap-playlist-item" data-type="audio" data-mp3="<?= $audio->getFileUrl() ?>" data-artist="<?= $audio->getAuthor()->getName(); ?>" data-title="<?= $audio->getTitle() ?>" data-thumb="<?= $audio->getThumbnail(true) ?>"  data-download="<?php echo $audio->getFileUrl() ?>"  data-target="_blank"></li>
        </ul>
    </div>
</div>
<?php if(JFactory::getApplication()->input->get('clayout')): ?>
<div class="musica-duration"><?php echo MP3File::formatTime($mp3file->getDuration()); ?></div>
<?php endif; ?>



