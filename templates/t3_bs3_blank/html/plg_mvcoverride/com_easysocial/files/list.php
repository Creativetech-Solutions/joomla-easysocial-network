<?php
defined('_JEXEC') or die('Unauthorized Access');
$document = JFactory::getDocument();
$document->addStyleSheet(rtrim(JURI::root(), '/') . 'templates/'.JFactory::getApplication()->getTemplate().'/html/plg_mvcoverride/com_easysocial/files/style.css');
$Audioapp = FD::table('app');
$Audioapp->loadByElement('audios', SOCIAL_APPS_GROUP_USER, 'apps');
$user = FD::user();
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.comment-icon').live('click',function(){
            var parent = jQuery(this).closest('.audio-item');
            parent.find('.comment-list').slideToggle();
        });
        jQuery('.nav.nav-tabs > li').click(function(){
            var classTab = jQuery(this).find('a').attr('href');
            if(classTab == '#tracks'){
                jQuery('.featured-container').hide();
                jQuery('.audios-container').show();
            }else if(classTab == '#featured'){
                jQuery('.audios-container').hide();
                jQuery('.featured-container').show();
            }
        });
        jQuery('.sort').find('a').click(function(){
            var orderby = jQuery(this).data('orderby');
            var direction;
            switch(orderby){
                case 'date':
                    direction = (jQuery(this).data('direction') == 'desc') ? 'asc' : 'desc';
                    direction = '&datedirection=' + direction;
                    break;
                case 'title':
                    direction = (jQuery(this).data('direction') == 'desc') ? 'asc' : 'desc';
                    direction = '&titledirection=' + direction;
                    break;
                case 'plays':
                    direction = (jQuery(this).data('direction') == 'desc') ? 'asc' : 'desc';
                    direction = '&playsdirection=' + direction;
                    break;
                case 'likes':
                    direction = (jQuery(this).data('direction') == 'desc') ? 'asc' : 'desc';
                    direction = '&likesdirection=' + direction;
                    break;
            }
            var url = '<?php echo FRoute::apps(array('layout' => 'canvas', 'clayout' => 'list', 'id' => $audios[0]->getAppAlias())); ?>&orderby='+orderby+direction;
            window.location = url;
        });
    });
</script>
<div id="audios-list">
    <div class="header">
        <h1 class="member-name"><?php echo JText::sprintf(JText::_('COM_EASYSOCIAL_USER_AUDIO_NAME'),$owner); ?></h1>
        <div class="back-to-profile"><?php echo JText::sprintf(JText::_('COM_EASYSOCIAL_AUDIO_BACK_TO_PROFILE'),$audios[0]->getAuthor()->getPermalink(),$owner); ?></div>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"> <a href="#tracks" role="tab" data-toggle="tab"><span><?php echo count($audios); ?></span><?php echo JText::_('COM_EASYSOCIAL_AUDIO_TRACKS'); ?></a></li>
        <li role="presentation"> <a href="#featured" aria-controls="profile" role="tab" data-toggle="tab"><span><?php echo count($featuredAudios); ?></span><?php echo JText::_('COM_EASYSOCIAL_AUDIO_FEATURED'); ?></a></li>
    </ul>
    <div class="add-audio">
        <span></span>
        <a href="<?= FRoute::apps(array('layout' => 'canvas', 'clayout' => 'form', 'id' => $Audioapp->getAlias())) ?>"><?php echo JText::_('COM_EASYSOCIAL_AUDIO_ADD_AUDIO'); ?></a>
    </div>
    <ul class="sort">
        <li><?php echo JText::_('COM_EASYSOCIAL_AUDIO_SORT'); ?></li>
        <li <?php if($orderby == 'date'){ echo 'class="active"';} ?>><a data-orderby="date" data-direction="<?php echo $datedirection; ?>" href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_AUDIO_SORT_DATE'); ?><div <?php if($orderby == 'date'){ echo 'class="arrow-'.$datedirection.'"';} ?>></div></a></li>
        <li <?php if($orderby == 'title'){ echo 'class="active"';} ?>><a data-orderby="title" data-direction="<?php echo $titledirection; ?>" href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_AUDIO_SORT_TITLE'); ?><div <?php if($orderby == 'title'){ echo 'class="arrow-'.$titledirection.'"';} ?>></div></a></li>
        <li <?php if($orderby == 'plays'){ echo 'class="active"';} ?>><a data-orderby="plays" data-direction="<?php echo $playsdirection; ?>" href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_AUDIO_SORT_PLAYS'); ?><div <?php if($orderby == 'plays'){ echo 'class="arrow-'.$playsdirection.'"';} ?>></div></a></li>
        <li <?php if($orderby == 'likes'){ echo 'class="active"';} ?>><a data-orderby="likes" data-direction="<?php echo $likesdirection; ?>" href="javascript:void(0);"><?php echo JText::_('COM_EASYSOCIAL_AUDIO_SORT_LIKES'); ?><div <?php if($orderby == 'likes'){ echo 'class="arrow-'.$likesdirection.'"';} ?>></div></a></li>
    </ul>
    <div class="audios-container">
        <?php foreach($audios as $audio): ?>
            <div class="audio-item">
                <?php
                if($audio->thumbnail){
                    if(file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_easysocial' . DIRECTORY_SEPARATOR . $audio->uid . DIRECTORY_SEPARATOR . $audio->thumbnail)){
                        $src = JUri::root().'media/com_easysocial/audios/' . $audio->uid . '/'.$audio->thumbnail;
                    }else{
                        $src = JUri::root().'media/com_easysocial/apps/user/audios/assets/icons/no-audio.png';
                    }
                }else{
                    $src = JUri::root().'media/com_easysocial/apps/user/audios/assets/icons/no-audio.png';
                }
                ?>
                <div class="inner">
                    <figure><img src="<?php echo $src; ?>"/></figure>
                    <div class="top">
                        <div>
                            <div class="title"><?php echo $audio->title; ?></div>
                            <div class="author"><?php echo JText::sprintf(JText::_('COM_EASYSOCIAL_AUDIO_FROM'),$audio->getAuthor()->getPermalink(),$audio->getAuthor()); ?></div>
                        </div>
                        <div>
                            <div class="date"><?php echo FD::date($audio->getCreatedDate())->toLapsed(); ?></div>
                            <div class="category"><?php echo $audio->category->getTitle(); ?></div>
                        </div>
                    </div>
                    <div class="description"><?php echo ($audio->description) ? $audio->description : JText::_('COM_EASYSOCIAL_AUDIO_NO_DESCRIPTION'); ?></div>
                    <div class="player">
                        <?php  echo $this->loadTemplate('apps/user/audios/default.player.mini', array('audio' => $audio, 'eqheight' => true)); ?>
                    </div>
                    <div class="bottom">
                        <ul>
                            <li>
                                <span></span>
                                <span><?php echo $audio->getHits(); ?></span>
                            </li>
                            <li><?php echo $audio->getLikes('create', $audio->getStreamId('create'))->button(); ?></li>
                            <li>
                                <span class="comment-icon"></span>
                                <span><?php echo $audio->getComments()->getCount(); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="comment-list"><?php echo $audio->getComments()->getHtml(); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="featured-container">

        <?php foreach($featuredAudios as $featuredAudio): ?>
            <div class="audio-item">
                <?php
                if($featuredAudio->thumbnail){
                    if(file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_easysocial' . DIRECTORY_SEPARATOR . $audio->uid . DIRECTORY_SEPARATOR . $audio->thumbnail)){
                        $src = JUri::root().'media/com_easysocial/audios/' . $featuredAudio->uid . '/'.$featuredAudio->thumbnail;
                    }else{
                        $default_image_class = 'class="default-img"';
                        $src = JUri::root().'media/com_easysocial/apps/user/audios/assets/icons/no-audio.png';
                    }
                }else{
                    $default_image_class = 'class="default-img"';
                    $src = JUri::root().'media/com_easysocial/apps/user/audios/assets/icons/no-audio.png';
                }
                ?>
                <div class="inner">
                    <figure <?php echo ($default_image_class) ? $default_image_class : ''; ?>><img src="<?php echo $src; ?>"/></figure>
                    <div class="top">
                        <div class="title"><?php echo $featuredAudio->title; ?></div>
                        <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                            <?php if ($featuredAudio->getAuthor()) { ?>
                                <?php echo $this->loadTemplate('site/avatar/default', array('user' => $featuredAudio->getAuthor())); ?>
                            <?php } else { ?>
                                <img src="<?php echo $featuredAudio->getAuthor()->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $featuredAudio->getAuthor()->getName()); ?>" />
                            <?php } ?>
                        </div>
                        <a href="<?php echo $featuredAudio->getAuthor()->getPermalink(); ?>" class="author"><?php echo $featuredAudio->getAuthor(); ?></a>
                        <div class="date"><?php echo FD::date($featuredAudio->getCreatedDate())->toLapsed(); ?></div>
                        <div class="description"><?php echo ($featuredAudio->description) ? $featuredAudio->description : JText::_('COM_EASYSOCIAL_AUDIO_NO_DESCRIPTION'); ?></div>
                        <div class="category"><?php echo $featuredAudio->category->getTitle(); ?></div>

                    </div>
                    <div class="player">
                        <?php echo $this->loadTemplate('apps/user/audios/default.player.mini', array('audio' => $featuredAudio, 'eqheight' => true, 'featured' => true)); ?>
                    </div>
                    <div class="bottom">
                        <ul>
                            <li>
                                <span></span>
                                <span><?php echo $featuredAudio->getHits(); ?></span>
                            </li>
                            <li><?php echo $featuredAudio->getLikes('create', $featuredAudio->getStreamId('create'))->button(); ?></li>
                            <li>
                                <span class="comment-icon"></span>
                                <span><?php echo $featuredAudio->getComments()->getCount(); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="comment-list"><?php echo $featuredAudio->getComments()->getHtml(); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>