<?php
$isGuest = ( FD::user()->id == 0 ) ? true : false;
// If is guest, then we don't add the action link, but we still show the content if settings enabled
if (
        !empty($comments) &&
        (
        (!$isGuest && $this->config->get('stream.comments.enabled') && $this->access->allowed('comments.read')
        ) ||
        ($isGuest && $this->config->get('stream.comments.guestview')
        )
        )
) {
    ?>
    <div class="es-stream-actions action-contents-comments"
         data-streamItem-contents
         data-streamItem-contents-comments
         data-action-contents-comments
         ><?php echo $comments->getHtml(array('hideEmpty' => true, 'hideForm' => $isGuest)); ?></div>
<?php } ?>