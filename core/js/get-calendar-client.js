(function($) {
    $(document).ready(function() {
        /*Print Calender*/
        var customer_id = $("input[name=customer_id]").val();
        get_events_calender(customer_id, '', false);
    });
}(jQuery));