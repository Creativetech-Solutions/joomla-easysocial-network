<?php if ((isset($featuredProjects) && $featuredProjects)) { ?>
    <div class="es-snackbar">
        <span>
            <?php echo JText::_("COM_EASYSOCIAL_FEATURED"); ?>
        </span>
        <span>
            <?php echo JText::_("COM_EASYSOCIAL_PROJECT"); ?>
        </span>
    </div>



    <div class="es-projects-list clearfix<?php echo!$featuredProjects ? ' is-empty' : ''; ?>">
        <?php if ($featuredProjects) { ?>
            <?php
            foreach ($featuredProjects as $project) {
                $streamId = $project->getStreamId('create');

                // Retrieve the comments library
                $comments = $project->getComments(); //changed create to add. hmmmm 
                // Retrieve the likes library
                $likes = $project->getLikes('create', $streamId);

//                $stream = FD::stream();
//                $options = array('comments' => $comments, 'likes' => $project->likes);
//
//                $project->actions = $stream->getActions($options);
                ?>
                <div class="row  es-project-item js-eq-height-container" data-apps-projects-item data-id="<?php echo $project->id; ?>">
                    <div class="es-project-content">
                        <div class="col-md-6 es-project-thumbnail">        
                            <a href="<?php echo $project->getPermalink('detail'); ?>">
                                <div class="es-project-cover" style="background-image: url('<?php echo $project->getOriginal(); ?>')"></div>
                            </a>
                        </div>
                        <div class="col-md-6 js-eq-height">
                            <div class="content-wrapper featured">


                                <div class="es-project-content content-box">
                                    <div class="es-project-title content-title">
                                        <a href="<?php echo $project->getPermalink('detail'); ?>"><?php echo $project->getTitle(); ?></a>
                                    </div>
                                    <div class="es-project-meta  content-avatar-area">
                                        <div class="es-avatar es-avatar-sm es-stream-avatar pull-left" data-comments-item-avatar="">
                                            <?php if ($project->getAuthor()) { ?>
                                                <?php echo $this->loadTemplate('site/avatar/default', array('user' => $project->getAuthor())); ?>						
                                            <?php } else { ?>
                                                <img src="<?php echo $project->getAuthor()->getAvatar(); ?>" alt="<?php echo $this->html('string.escape', $project->getAuthor()->getName()); ?>" />
                                            <?php } ?>
                                        </div>
                                        <a href="<?php echo $project->getAuthor()->getPermalink(); ?>" class="actor-column">
                                            <?php echo $project->getAuthor()->getName(); ?>
                                        </a>
                                    </div>
                                    <span class="time-span">
                                        <?php
                                        echo FD::date($project->getCreatedDate())->toLapsed();
                                        ?>
                                    </span>


                                    <div class="content-description mt-10"><?php echo $project->getDescription(); ?></div>

                                    <?php
                                    if (isset($project->category)) {
                                        ?>
                                        <div class="category-box"><i class="fa fa-tag" aria-hidden="true"></i> <?= $project->category->getTitle() ?></div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="content-bottom">

                                    <div class="es-action-wrap pr-20">
                                        <ul class="fd-reset-list es-action-feedback">
                                            <?php
                                            $repost = FD::get('Repost', $project->id, 'projects', SOCIAL_APPS_GROUP_USER);
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
                                                <a href="<?= $project->getEditLink() ?>">
                                                    <?php echo JText::_('APP_PROJECTS_EDIT_BUTTON'); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-apps-projects-delete data-id="<?php echo $project->id; ?>">
                                                    <?php echo JText::_('APP_PROJECTS_DELETE_BUTTON'); ?>
                                                </a>
                                            </li>
                                            <?php if (!$project->isFeatured()) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" data-project-feature><?php echo JText::_('COM_EASYSOCIAL_PROJECT_FEATURE_PROJECT'); ?></a>
                                                </li>
                                            <?php } ?>

                                            <?php if (!$project->isUnfeatured()) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" data-project-unfeature><?php echo JText::_('COM_EASYSOCIAL_PROJECT_UNFEATURE_PROJECT'); ?></a>
                                                </li>
                                            <?php } ?>
                                            
                                        </ul>
                                    </div>
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
                <div><?php echo JText::_('COM_EASYSOCIAL_NO_PROJECTS_AVAILABLE_CURRENTLY'); ?></div>
            </div>
    <?php } ?>
    </div>
<?php } ?>