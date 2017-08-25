<?php if ((isset($featuredAudios) && $featuredAudios)) { ?>
    <div class="es-snackbar">
        <span>
            <?php echo JText::_("COM_EASYSOCIAL_FEATURED"); ?>
        </span>
        <span>
            <?php echo JText::_("COM_EASYSOCIAL_TRACK"); ?>
        </span>
    </div>



    <div class="es-audios-list clearfix<?php echo!$featuredAudios ? ' is-empty' : ''; ?>">
        <?php if ($featuredAudios) { ?>
            <?php
            foreach ($featuredAudios as $audio) {
                $streamId = $audio->getStreamId('create');

                // Retrieve the comments library
                $comments = $audio->getComments(); //changed create to add. hmmmm 
                // Retrieve the likes library
                $likes = $audio->getLikes('create', $streamId);

//                $stream = FD::stream();
//                $options = array('comments' => $comments, 'likes' => $audio->likes);
//
//                $audio->actions = $stream->getActions($options);
                ?>
                <div class="row  es-audio-item js-eq-height-container" data-apps-audios-item data-id="<?php echo $audio->id; ?>">
                    <div class="col-sm-5 col-md-4">        
                        <?php echo $this->loadTemplate('apps/user/audios/default.player', array('audio' => $audio, 'eqheight' => true)); ?>


                    </div>
                    <div class="col-sm-7 col-md-8 js-eq-height">
                        <div class="content-wrapper featured">


                            <div class="es-audio-content content-box">
                                <div class="es-audio-title content-title">
                                    <a href="<?php echo $audio->getPermalink(); ?>"><?php echo $audio->getTitle(); ?></a>
                                </div>
                                <div class="es-audio-meta  content-avatar-area">
                                    <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                        <?php if ($audio->getAuthor()) { ?>
                                            <?php echo $this->loadTemplate('site/avatar/default', array('user' => $audio->getAuthor())); ?>						
                                        <?php } else { ?>
                                            <img src="<?php echo $audio->getAuthor()->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $audio->getAuthor()->getName()); ?>" />
                                        <?php } ?>
                                    </div>
                                    <a href="<?php echo $audio->getAuthor()->getPermalink(); ?>" class="actor-column">
                                        <?php echo $audio->getAuthor()->getName(); ?>
                                    </a>
                                </div>
                                <span class="time-span">
                                    <?php
                                    echo FD::date($audio->getCreatedDate())->toLapsed();
                                    ?>
                                </span>
                               

                                <div class="content-description mt-10"><?php echo $audio->getDescription(); ?></div>

                               <?php 
                               if(isset($audio->category)){
                                   ?>
                                <div class="category-box"><i class="fa fa-tag" aria-hidden="true"></i> <?= $audio->category->getTitle()?></div>
                                <?php 
                               }
                               ?>
                            </div>

                            <div class="content-bottom">

                                <div class="es-action-wrap pr-20">
                                    <ul class="fd-reset-list es-action-feedback">
                                        <?php
                                        $repost = FD::get('Repost', $audio->id, 'audios', SOCIAL_APPS_GROUP_USER);
                                        ?>
                                        <li class="action-title-repost streamAction">
                                            <span>
                                                <a data-stream-action-repost href="javascript:void(0);" class="fd-small arepost iconitem-parent"><?php echo $repost->getButton(); ?></a>
                                            </span>
                                            <span class="numcount"><?php
                                                if (isset($repost)) {
                                                    echo $repost->getCount();
                                                }
                                                ?>
                                            </span>
                                        </li>
                                        <li class="action-title-comments streamAction"
                                            data-key="comments"
                                            data-streamItem-actions
                                            >
                                            <span class="iconitem-parent"><a data-stream-action-comments href="javascript:void(0);" class="fd-small acomments"><?php echo JText::_('COM_EASYSOCIAL_STREAM_COMMENT'); ?></a></span>
                                            <span class="numcount"><?php
                                                if (isset($comments)) {
                                                    echo $comments->getCount();
                                                }
                                                ?>
                                            </span>
            <!--                                             <div data-stream-counter class="es-stream-counter<?php echo ( $likes->getCount() == 0 ) ? ' hide' : ''; ?>">
                                        <div class="es-stream-actions"><?php echo $likes->toHTML(); ?></div>
                                    </div>-->
                                        </li>
                                        <li class="action-title-likes">
                                            <?php echo $likes->button(); ?>
                                        </li>

                                        <li class="action-title-hits streamAction">
                                            <span class="iconitem"></span>
                                            <span class="numcount"><?php
                                                echo $audio->getHits();
                                                ?>
                                            </span>
                                        </li>


                                        <li class="es-action-privacy">
                                            <?php //echo $privacyButton;   ?>
                                        </li>
                                    </ul>
                                </div>

                                <div class="es-stream-control pull-right btn-group">
                                    <a class="control-buton" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu fd-reset-list">
                                        <li>
                                            <a href="<?= $audio->getEditLink() ?>">
                                                <?php echo JText::_('APP_AUDIOS_EDIT_BUTTON'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" data-apps-audios-delete data-id="<?php echo $audio->id; ?>">
                                                <?php echo JText::_('APP_AUDIOS_DELETE_BUTTON'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" data-apps-audios-feature data-id="<?php echo $audio->id; ?>">
                                                <?php $featureText = !$audio->isFeatured() ? 'APP_AUDIOS_FEATURE_BUTTON' : 'APP_AUDIOS_UNFEATURE_BUTTON';
                                                echo JText::_($featureText);
                                                ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="row content-wrapper">
                    <div class="col-xs-12">
                        <div class="es-stream-actions">
            <?php echo $comments->getHTML(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

    <?php } else { ?>
            <div class="empty empty-hero">
                <i class="fa fa-film"></i>
                <div><?php echo JText::_('COM_EASYSOCIAL_NO_AUDIOS_AVAILABLE_CURRENTLY'); ?></div>
            </div>
    <?php } ?>
    </div>
<?php } ?>