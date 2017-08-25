/**
 * @package ats
 * @copyright Copyright (c)2011-2016 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

akeeba.jQuery(document).ready(function($){
    var counter = 0;
    var timer;
    var counting = false;
    var $timespent = $('#ats-timespent');

    function tick()
    {
        counter += 15;
        timer = setTimeout(tick, 15000);
        $timespent.val(Math.ceil(counter / 60))
    }

    $('#timerBtn').click(function(){
        if(!counting){
            timer = setTimeout(tick, 15000);
            counting = true;
            $('#counting').show();
            $(this).html(Joomla.JText._('COM_ATS_TIMER_STOP'))
        }
        else{
            counting = false;
            clearTimeout(timer);
            $('#counting').hide();
            $(this).html(Joomla.JText._('COM_ATS_TIMER_START'))
        }
    });

    $('#ticketSubmit').click(function(){
        if(counting){
            clearTimeout(timer);
        }
    })
});
