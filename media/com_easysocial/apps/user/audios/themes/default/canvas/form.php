<?php
defined('_JEXEC') or die('Restricted access');
$aid = JFactory::getApplication()->input->get("aid", false, 'INT');
$edit = $aid;
$audioThumb = false;
$document = JFactory::getDocument();
$document->addStyleSheet(rtrim(JURI::root(), '/') . '/media/com_easysocial/apps/user/audios/assets/css/cropper.min.css');
if ($edit) {
    $audioThumb = $audio->getThumbnailURL();
}
?>
<div class="es-container">
    <div class="back-link"><a href="<?= FRoute::apps(array('layout' => 'canvas', 'id' => $app->getAlias())); ?>">← Back to Audios</a></div>
    <div class="add-audio-page">
        <div class="page-title">

            <?php if ($edit): ?>
                <span>Edit</span> <span>Audio</span>
            <?php else: ?>
                <span>Add</span> <span>New Audio</span>
            <?php endif ?>
        </div>
        <div class="spacer40"></div>
        <form id="audio-form" class="box" method="post" action="<?php echo JRoute::_('index.php'); ?>" enctype="multipart/form-data">

            <?php if (!$edit): ?>
                <div class="row step1">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3">
                        <div class="form es-audio">
                            <?php if (!empty($exceeded)) { ?>
                                <div class="alert alert-dismissable fade in alert-warning">
                                    <button data-story-attachment-clear-button class="close" type="button">×</button>
                                    <strong><?php echo JText::_('COM_EASYSOCIAL_PHOTOS_EXCEEDED'); ?></strong><br/><?php echo $exceeded ?>
                                </div>
                            <?php } else { ?>
                                <div class="box__input">
                                    <div class="drag-drop">
                                        Drag & drop audio here<br/>or
                                    </div>
                                    <input class="box__file" type="file" name="audio_file" <?= $edit ? "" : "required" ?> accept=".mp3" id="file" data-multiple-caption="{count} files selected"/> <!-- multiple -->
                                    <label for="file" id="upload" class="es-audio-upload-button">Choose audio to upload</label>
                                    <button type="submit" class="box__button es-audio-upload-button" style="display: none;">Upload</button>
                                </div>



                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row step2" <?php echo $edit ? "" : 'style="display: none;"'; ?>>


                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="row" id="stepButton" style="display:none;">
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3  xs-margin-top-20 xs-margin-bottom-20">
                            <a href="javascript:;" class="btn btn-orange pull-right">Select new audio</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div>
                                <div class="thumb__uploading">Uploading&hellip;</div>
                                <div class="thumb__success">Done!</div>
                                <div class="thumb__error">Error! <span></span>.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="ThumbContainer" class="col-xs-12 col-sm-5 <?php echo $audioThumb ? 'has-thumb' : '' ?>" >
<!--                            <input type="hidden" id="thumbnail" name="thumbnail"/>-->
                            <input id="thumbnail" class="thumb__file" type="file" name="thumbnail"  accept=".jpg,.jpeg,.png"/>
                            <input type="hidden" id="thumbnailImage" name="thumbnailImage"/>

                            <label for="thumbnail" id="pic_area" class="clearfix img-container text-center <?= $edit ? "" : "newAudio" ?>">

                                <div id="no-image-div">
                                    <i class="fa fa-camera-retro" aria-hidden="true"></i>
                                    <div><strong>Upload an image</strong><br/>for your audio</div>
                                </div>

                                <img <?= $audioThumb ? '' : 'style="display: none;"' ?> id="thumbnail_img" src="<?= $audioThumb ? $audioThumb : "" ?>" />

                            </label>
                            <div id="thumb-actions" class="clearfix" style="display: none;">
                                <div id="actions" class="clearfix">
                                    <div class="btn-group">

                                        <a id="buttonReset" type="button" class="btn btn-primary"  title="Reset">
                                            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="Reset">
                                                <span class="fa fa-refresh"></span>
                                            </span>
                                        </a>
                                        <a id="zoomIn" type="button" class="btn btn-primary"  title="Zoom In">
                                            <span class="docs-tooltip" data-toggle="tooltip" title="">
                                                <span class="fa fa-search-plus"></span>
                                            </span>
                                        </a>
                                        <a id="zoomOut" type="button" class="btn btn-primary"  title="Zoom Out">
                                            <span class="docs-tooltip" data-toggle="tooltip" title="" >
                                                <span class="fa fa-search-minus"></span>
                                            </span>
                                        </a>
                                    </div>

                                </div>
                                <div class="dow-grad-header blue">Preview</div>

                                <div class="clearfix preview"></div>
                            </div>



                        </div>
                        <div class="col-xs-12 col-sm-7">
                            <label id="filename"></label>
                            <?php
                            echo $categories;
                            ?>


                            <input type="text" required id="audio_title"  value="<?= $audio->title ?>" class="audio_title form-control" name="title" placeholder="<?php echo JText::_('COM_COMMUNITY_AUDIO_ENTER_TITLE'); ?>">
                            <textarea class=" form-control" name="description" placeholder="<?php echo JText::_('COM_COMMUNITY_AUDIO_ENTER_DESC'); ?>"><?= $audio->description ?></textarea>
                            <?php if (false) {//$audio->canAddTag()     ?>
                                <label><?php echo JText::_('COM_COMMUNITY_AUDIO_PEOPLE_TRACK'); ?></label>

                                <div class="es-video-tagging">
                                    <b><?php echo JText::_('COM_EASYSOCIAL_VIDEOS_PEOPLE_IN_THIS_VIDEO'); ?></b>

                                    <span class="ml-5 text-muted">
                                        &ndash;
                                        <a href="javascript:void(0);" data-video-tag><?php echo JText::_('COM_COMMUNITY_AUDIO_FIND_FRIEND'); ?></a>
                                    </span>
                                    <ul class="es-video-tag-friends fd-reset-list<?php echo!$tags ? ' is-empty' : ''; ?>" data-video-tag-wrapper>
                                        <?php echo $this->output('apps/user/audios/canvas/tags'); ?>
                                        <li class="empty" data-tags-empty>
                                            <?php echo JText::_('COM_EASYSOCIAL_VIDEOS_NO_TAGS_AVAILABLE'); ?>
                                        </li>
                                    </ul>
                                </div>
                                <input type="text"  class=" form-control" name="tags" placeholder="<?php echo JText::_('COM_COMMUNITY_AUDIO_ENTER_TAGS'); ?>">

                            <?php } ?>

                            <div class="clearfix"></div>
                            <a id="submit-form" class="btn btn-orange pull-right xs-margin-top-40" name="sub"><?= $edit ? "Update" : "Save & upload" ?></a>   
                            <div>
                                <div class="box__uploading">Uploading&hellip;</div>
                                <div class="box__success">Done!</div>
                                <div class="box__error">Error! <span></span>.</div>
                            </div>
                            <!--<input type="hidden" name="namespace" value="apps/user/audios/controllers/audios/store"/>
                            <input type="hidden" name="ajax" value="1"/>
                            <input type="hidden" name="format" value="ajax"/>
                            <input type="hidden" name="no_html" value="1"/>
                            <input type="hidden" name="tmpl" value="component"/>-->

                            <input type="hidden" name="controller" value="apps" />
                            <input type="hidden" name="task" value="controller" />
                            <input type="hidden" name="appController" value="audios" />
                            <input type="hidden" name="appTask" value="store" />
                            <input type="hidden" name="appId" value="<?php echo $app->id; ?>" />
                            <input type="hidden" name="option" value="com_easysocial" />
<!--                            <input type="hidden" name="ajax_url" value="<?= FRoute::_('index.php?ajax=1&tmpl=component&format=ajax&no_html=1') ?>"/>   -->
                            <input type="hidden" name="aid" value="<?= $aid ?>"/>
                            <input type="hidden" name="<?= ES::token() ?>" value="1"/>


                        </div>
                    </div>

                </div>
            </div>
        </form>

    </div>

</div>