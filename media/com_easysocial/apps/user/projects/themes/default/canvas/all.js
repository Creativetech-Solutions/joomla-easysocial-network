/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
EasySocial.require()
        .script('<?php echo rtrim( JURI::root() , ' / ' );?>/media/com_easysocial/apps/user/projects/assets/js/projects.js')
        .done(function ($)
        {
            $(".action-title-comments a").on('touchstart click', function () {
                console.log($(this));
                $(this).closest(".es-project-item").find(".es-comments-wrap").toggle();
                return true;
            });

        });