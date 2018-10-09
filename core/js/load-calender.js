var $ = jQuery.noConflict();

function load_single_event(id, editable) {
    var data = {
        action: 'ev_get_event',
        event_id: id
    };
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: fitness_ajaxurl.ajaxurl,
        data: data,
        success: function(response) {

            if (response.status == 'success') {

                var id = response.data.id;
                var title = response.data.title;
                var start_date = moment(response.data.start_date_time).format("YYYY-MMMM-DD hh:mm A");
                var end_date = moment(response.data.end_date_time).format("YYYY-MMMM-DD hh:mm A");
                var content = response.data.description;

                var html = '';
                if (editable) {
                    html += '<div class="event-popup-action"><button data-id="' + id + '" id="delete-event">Delete Event</button></div>';
                }
                html += '<h2>' + title + '</h2>';
                html += '<span><strong>Start Date: </strong>' + start_date + '</span> ';
                html += '<span><strong>End Date: </strong>' + end_date + '</span>';
                html += '<div class="event-content">' + content + '</div>';



                $.magnificPopup.open({
                    items: {
                        src: '<div class="white-popup">' + html + '</div>', // can be a HTML string, jQuery object, or CSS selector
                        type: 'inline'
                    }
                });
            }

            if (response.status == 'error') {
                $.toast({
                    heading: 'Error',
                    text: response.message,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: {
                        right: 20,
                        top: 120
                    },
                });
            }
            $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeOut();

        },
        beforeSend: function() {
            $('.ajax-laoder').show();
        },
        complete: function() {
            $('.ajax-laoder').hide();
        },
        error: function(error) {
            $('.ajax-laoder').hide();
            console.info("Error AJAX not working: " + error);
        }
    });
}

function print_calender(obj, editable) {


    var events = new Array();

    var event_title = '';
    var event_start = '';
    var event_end = '';

    $.each(obj, function(index, value) {

        id = obj[index].id;
        event_title = obj[index].title;
        event_start = moment(obj[index].start_date_time).format("YYYY-MM-DD[T]HH:mm:ss");
        event_end = moment(obj[index].end_date_time).format("YYYY-MM-DD[T]HH:mm:ss");

        events.push({
            'id': id,
            'title': event_title,
            'start': event_start,
            'end': event_end,
        });
    });

    var settings = {
        defaultView: 'month',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        eventLimit: true
    };

    if (!$.isEmptyObject(obj)) {
        settings['events'] = events;

        settings['eventClick'] = function(calEvent, jsEvent, view) {
            load_single_event(calEvent.id, editable);
        }
    }

    $('#wpshev-loader').hide();
    $('#calendar').fullCalendar(settings);
}

function get_events_calender(customer_id, instructor_id, editable) {

    var data = {
        action: 'ev_get_events',
        customer_id: customer_id,
        instructor_id: instructor_id
    };
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: fitness_ajaxurl.ajaxurl,
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                print_calender(response.data, editable);
            }
            if (response.status == 'error') {
                $.toast({
                    heading: 'Error',
                    text: response.message,
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: {
                        right: 20,
                        top: 120
                    },
                });
            }
            $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeOut();

        },
        beforeSend: function() {
            $('.ajax-laoder').show();
        },
        complete: function() {
            $('.ajax-laoder').hide();
        },
        error: function(error) {
            $('.ajax-laoder').hide();
            console.info("Error AJAX not working: " + error);
        }
    });
}
