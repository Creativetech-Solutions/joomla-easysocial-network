/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//show sidebar on lg and up
(function ($, viewport) {
    function hidesidebar() {



        if (viewport.is('<lg')) {
            $("#es-sidebar").collapse("hide");
            $('.es-container').addClass('menuhidden');
        } else {
//            if (!$("a#sidebar-toggle").hasClass('collapsed')) {
//                $("#es-sidebar").collapse("show");
//            }
            if (!$('a#sidebar-toggle').is(".collapsed")) {
                $("#es-sidebar").collapse("show");
                $('.es-container').removeClass('menuhidden');
            }
        }
    }
    $(document).ready(function () {
        hidesidebar();
        $('a#sidebar-toggle').on('click', function () {
            if (!$(this).is(".collapsed")) {
                $('.es-container').addClass('menuhidden');
            } else {
                $('.es-container').removeClass('menuhidden');
            }
        });
        
        $('a.profile-sidebar-toggle').on('click', function () {
            if (!$(this).is(".collapsed")) {
                $('.es-container').addClass('menuhidden');
            } else {
                $('.es-container').removeClass('menuhidden');
            }
        });
    });

    $(window).resize(function () {
        hidesidebar();
    });
})(jQuery, ResponsiveBootstrapToolkit);
