//$(window).load(function () {
//    var s = jQuery("div#es-sidebar");
//    var container = jQuery("div.es-content");
//    if (s.length === 0 || container.length === 0) {
//        return false;
//    }
//
//    var sticker_height = s.height();
//
//    jQuery(window).scroll(function () {
//        var windowpos = jQuery(window).scrollTop();
//        var posContainer = container.offset();
//        var posContainerTop = posContainer.top;
//        var posContainerBottom = posContainerTop + container.height();
//         var offset = container.offset().left + container.width() + 60; //padding of the left container
//                s.css("left", offset);
//    });
//
//
//});