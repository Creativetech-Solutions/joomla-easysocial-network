(function ($) {
    $(document).ready(function ($) {
        $(".action-title-comments a").click(function () {
            $(this).closest("li.streamItem").find(".es-comments-wrap").toggle();
            return true;
        });
        //put comments under the text
//        $("li.streamItem .bottom-stream").each(function (index) {
//            $(this).before($(this).find('.action-contents-comments'));
//        });
    });
})(jQuery);
//
/////* masonry init */
//(function(){
//
//      /*var 
//        speed = 700,   // animation speed
//        $wall = jQuery(document).find('.es-streams'),
//
//        masonryOptions = {         // initial masonry options
//          columnWidth: 110, 
//          itemSelector: '.streamItem',
//          animate: true,
//          animationOptions: {
//            duration: speed,
//            queue: true
//          }
//        }
//      ;*/
//	  var timerVar;
//	  var masonryOptions = {         // initial masonry options
//			  columnWidth: 200, 
//			  itemSelector: '.streamItem',
//			  animate: true,
//			  animationOptions: {
//				duration: 700,
//				queue: true
//			  }
//		};
//	  
//	  jQuery(document).ready(function(){
//		  
//		 var masonryOptions = {         // initial masonry options
//			  columnWidth: 200, 
//			  itemSelector: '.streamItem',
//			  animate: true,
//			  animationOptions: {
//				duration: 700,
//				queue: true
//			  }
//		};
//		  jQuery(document).find('.es-stream-list').masonry(masonryOptions);
//		  
//		  
//		  jQuery('a.btn-stream-updates').on('click',function(){
//			  //jQuery('.es-stream-list').bind("DOMSubtreeModified", hideMsg);
//			  timerVar = setInterval(function(){ hideMsg() }, 3000);
//		  });
//		  
//	  });
//	  
//	  function hideMsg(){
//		  console.log('hideMsg');
//		  if(jQuery('li.pagination.streamPage').html() == ''){
//				jQuery(document).find('.es-stream-list').masonry(masonryOptions);
//		  		jQuery('.es-stream-list').find('li.streamItem').css('position','absolute'); 
//				clearInterval(timerVar); 
//		  }
//		  
//	  }
//	  
//	  jQuery(window).load(function(){
//		  
//		 var masonryOptions = {         // initial masonry options
//			  columnWidth: 70, 
//			  itemSelector: '.streamItem',
//			  animate: true,
//			  animationOptions: {
//				duration: 700,
//				queue: true
//			  }
//		};
//		  jQuery(document).find('.es-stream-list').masonry(masonryOptions);
//	  });
//
//      // run on window.load so we can capture any incoming hashes
//      /*$(window).load(function(){
//        // run masonry on start-up to capture all the boxes we'll need
//        $wall.masonry(masonryOptions);
//        if ( window.location.hash ) {
//          // get rid of the '#' from the hash
//          var possibleFilterClass = window.location.hash.replace('#', '');
//          switch (possibleFilterClass) {
//          // if the hash matches the following words
//          case 'editorial' : case 'posters' : case 'otherthings' : case 'books' : case 'comics' : case 'animation' : case 'things' : case 'info' : 
//            // set masonry options animate to false
//            masonryOptions.animate = false;
//            // hide boxes that don't match the filter class
//            $wall.children().not('.'+possibleFilterClass)
//              .toggleClass('invis').hide();
//            // run masonry again, this time with the necessary stuff hidden
//            $wall.masonry(masonryOptions);
//            break;
//          }
//        }
//
//      });*/
//})(jQuery);
//
//(function(window, $, undefined){
//
//    /**
//     * Hooks into a given method
//     * 
//     * @param method
//     * @param fn
//     */
//    $.fn.hook = function (method, fn) {
//        var def = $.fn[method];
//
//        if (def) {
//            $.fn[method] = function() {
//                var r = def.apply(this, arguments); //original method
//                fn(this, method, arguments); //injected method
//                return r;
//            }
//        }
//    }
//
//})(window, jQuery);