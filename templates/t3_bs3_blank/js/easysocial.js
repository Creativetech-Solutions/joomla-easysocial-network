(function ($, viewport) {
    
    var mapmob;
    var myLatLng;
    var lat, lng;

    function activateComments() {
        $(".action-title-comments a").on('touchstart click', function () {
            $(this).closest(".content-wrapper").find(".es-comments-wrap").toggle();
            return true;
        });
    }
// make boxes equal height
    function heightCheck() {

        if (viewport.is('<sm'))
            return;
        $('.js-eq-height-container').each(function () {

            var highestBox = 0;
            //first set the height to auto to calculate real height
            $(this).find('.js-eq-height').css('height', 'auto');
            $(this).find('.js-eq-height').each(function () {
                if ($(this).height() > highestBox)
                    highestBox = $(this).height();
            });

            $(this).find('.js-eq-height').height(highestBox);

            // make additional boxes equal height within the same container
            var highestBoxOther = 0;
            for (var i = 1; i < 10; i++) {
                highestBoxOther = 0;
                $(this).find('.js-eq-height' + i).css('height', 'auto');
                $(this).find('.js-eq-height' + i).each(function () {
                    if ($(this).height() > highestBoxOther)
                        highestBoxOther = $(this).height();
                });
                $(this).find('.js-eq-height' + i).height(highestBoxOther);
            }
        });
    }
    function changeOrder() {
        var myArray = $("div.sort-it").clone();
        //var Audio = $("div.sort-it.audio-div").clone();
        myArray.sort(function (a, b) {
            var contentA = parseInt($(a).attr('data-sort'));
            var contentB = parseInt($(b).attr('data-sort'));
            return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
        });
        if($(".es-dashboard .es-container .sorted-container").hasClass("sorted") == false){
            
            $(".es-dashboard .es-container .audio-dashboard").html("");
            $(".es-dashboard .es-container .account_overview_siderbar").html("");
            $(".es-dashboard .es-container .sorted-container").addClass("sorted").append(myArray);
            $(".es-dashboard .es-container .sorted-container .location_siderbar #mapsidebar").after('<div id="mapsidebar_mb" style="width:220px;height:80px;margin-bottom: 20px;"></div>');
        }
    }
    $(document).ready(function ($) {
        activateComments();
        heightCheck();
        if (viewport.is('<sm')){
            changeOrder();
        }     
    });
    
    jQuery(window).load(function (){
        if (viewport.is('<sm')){
            /*setTimeout(function() {
                mapdata = jQuery(".es-dashboard .es-container .es-sidebar  .location_siderbar").html();
                console.log(mapdata);
                jQuery(".es-dashboard .es-container .es-sidebar .location_siderbar").html("");
                jQuery(".es-dashboard .es-container .sorted-container .location_siderbar").html(mapdata);
            }, 4000);*/
            //jQuery(".es-dashboard .es-container .sorted-container .location_siderbar #mapsidebar").after('<div id="mapsidebar_mb" style="width:220px;height:80px;margin-bottom: 20px;"></div>');
            jQuery(".es-dashboard .es-container .sorted-container .location_siderbar #mapsidebar").toggle();
            lat = jQuery(".es-dashboard .es-container .es-sidebar .location_siderbar").find("div#mapsidebar").data("lat");
            lng = jQuery(".es-dashboard .es-container .es-sidebar .location_siderbar").find("div#mapsidebar").data("lng");
            //console.log(lat);
            myLatLng = {lat: lat, lng: lng};
            mapmob = new google.maps.Map(document.getElementById('mapsidebar_mb'), {
              center: myLatLng,
              zoom: 17
            });
            var marker = new google.maps.Marker({
              position: myLatLng,
              map: mapmob
            });
        } 
    });

//also on windows resize
    $(window).resize(function () {
        heightCheck();
        if (viewport.is('<sm')){
            changeOrder();
            
            lat = jQuery(".es-dashboard .es-container .es-sidebar .location_siderbar").find("div#mapsidebar").data("lat");
            lng = jQuery(".es-dashboard .es-container .es-sidebar .location_siderbar").find("div#mapsidebar").data("lng");
            //console.log(lat);
            jQuery(".es-dashboard .es-container .sorted-container .location_siderbar #mapsidebar").css("display","none");
            myLatLng = {lat: lat, lng: lng};
            mapmob = new google.maps.Map(document.getElementById('mapsidebar_mb'), {
              center: myLatLng,
              zoom: 17
            });
            var marker = new google.maps.Marker({
              position: myLatLng,
              map: mapmob
            });
        }else if(viewport.is('>=sm')){
            $(".es-dashboard .es-container .audio-div .audio-dashboard").html($(".es-dashboard .es-container .sorted-container .audio-dashboard").html());
            $(".es-dashboard .es-container .es-sidebar .account_overview_siderbar").html($(".es-dashboard .es-container .sorted-container .account_overview_siderbar").html());
            //$(".es-dashboard .es-container .es-sidebar .location_siderbar").html($(".es-dashboard .es-container .sorted-container .location_siderbar").html());
            myLatLng = {lat: lat, lng: lng};
            mapmob = new google.maps.Map(document.getElementById('mapsidebar'), {
              center: myLatLng,
              zoom: 17
            });
            var marker = new google.maps.Marker({
              position: myLatLng,
              map: mapmob
            });
             
            $(".es-dashboard .es-container .sorted-container").removeClass("sorted").html("");
        }
            
    });


})(jQuery, ResponsiveBootstrapToolkit);
