var $ = jQuery.noConflict();



function load_single_event(id, editable) {
    var data = {
        action: 'ev_get_event',
        event_id: id
    };
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: wpshev_ajax_object.admin_url,
        data: data,
        success: function(response) {

            if (response.status == 'success') {

                var id = response.data.id;
                var title = response.data.title;
                var event_date = moment(response.data.event_date).format("DD-MMMM-YYYY");
                var time = moment(response.data.event_time).format("hh:mm A");
                var content = response.data.description;

                var html = '';
                if (editable != 'false') {
                    html += '<div class="event-popup-action"><button data-id="' + id + '" id="delete-event">Delete Event</button></div>';
                }
                html += '<h2>' + title + '</h2>';
                html += '<span><strong>Date: </strong>' + event_date + '</span> ';
                html += '<span><strong>Time: </strong>' + event_time + '</span> ';
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


    $.each(obj, function(index, value) {

        id = obj[index].id;
        event_title = obj[index].title;
        event_start = moment(obj[index].event_date).format("YYYY-MM-DD");
        event_time = obj[index].event_time;
        event_type = obj[index].event_type;


        switch (event_type) {
            case 'rest':
                 event_color = '#ed7a14';
                break;
            case 'workout':
                 event_color = '#ed145b';
                break;
            default:
                 event_color = '#00a651';
        }

        events.push({
            'id': id,
            'title': event_title,
            'start': event_start + 'T' + event_time,
            'color' :event_color
        });
    });

    var settings = {
        defaultView: 'month',
        header: {
            left: 'prev, title',
            center: '',
            right: 'month,agendaWeek,listWeek, next'
        },
        eventLimit: true,
        dayNamesShort: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        viewRender: function(view) {
            $('.ev-popup').remove();
            if (editable != 'false') {
                $(".fc-left").append('<button class="ev-popup"><i class="muscle-icon"></i> Add New Event</button>');
            }
        }
    };

    if (!$.isEmptyObject(obj)) {
        settings['events'] = events;
        timeFormat: 'H(:mm)',
        settings['eventClick'] = function(calEvent, jsEvent, view) {
                load_single_event(calEvent.id, wpshev_ajax_object.calender_editable);
        },
        settings['eventRender'] = function(event, element) {
         
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
        url: wpshev_ajax_object.admin_url,
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                print_calender(response.data, wpshev_ajax_object.calender_editable);
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