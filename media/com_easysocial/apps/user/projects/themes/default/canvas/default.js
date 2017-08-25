EasySocial.require()
        .script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/projects/assets/js/projects.js')
        .done(function ($)
        {
            function activateComments() {
                $(".action-title-comments a").on('touchstart click', function () {
                    $(this).closest(".es-projects-featured").find(".es-comments-wrap").toggle();
                    return true;
                });
            }
            //$( '[data-stream-item]' ).addController( EasySocial.Controller.Stream.Item );
            activateComments();

        });



