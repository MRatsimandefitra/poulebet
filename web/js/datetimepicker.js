$(document).ready(function(){
    $('#datepic').datetimepicker({
        /*yearOffset:10,*/
        lang:'fr',
        timepicker: false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
        minDate:'2006-01-01', // yesterday is minimum date
        maxDate:'2022-12-22' // and tommorow is maximum date calendar
    });

    $('#timepic').datetimepicker({
        datepicker:false,
        format:'H:i',
        step:5
    });
});
