jQuery(document).ready(function () {
    // set datetimepicker settings
    jQuery.datetimepicker.setLocale('nl');
    jQuery.datetimepicker.setDateFormatter('moment');

    // get documents
    var startDate = jQuery('#eventstart');
    var startDateHidden = jQuery('#eventstarthidden');

    var endDate = jQuery('#eventend');
    var endDateHidden = jQuery('#eventendhidden');

    var settings = function (el) {
        return {
            minDate: 0,
            defaultDate: new Date(),
            value: moment(el.val()).format('DD-MM-YYYY H:mm'),
            format: 'DD-MM-YYYY H:mm',
            formatDate: 'DD-MM-YYYY',
            formatTime: 'H:mm',
            step: 5,
            onSelectDate:function(date){
                console.log(moment(date).toISOString());
                el.val(moment(date).toISOString());
            }
        }
    };

    // run function on elements
    startDate.datetimepicker(settings(startDateHidden));
    endDate.datetimepicker(settings(endDateHidden));
});