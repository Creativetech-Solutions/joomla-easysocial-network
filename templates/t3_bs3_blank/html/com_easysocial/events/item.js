EasySocial
.require()
.script('site/events/item','eventExtrs')
.done(function($){
    $('[data-event-item]').addController('EasySocial.Controller.Events.Item', {
        id: <?php echo $event->id; ?>
    });

    $( '[data-event-extras]' ).implement( EasySocial.Controller.eventExtrs );
});
