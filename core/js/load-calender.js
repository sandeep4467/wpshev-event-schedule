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
                var time = 'Full Day';
                var html = '';

                var id = response.data.id;
                var title = response.data.title;
                var full_day = response.data.full_day;
                var event_type = response.data.event_type;
                var content = response.data.description;

                if (full_day != 1) {
                    time = moment(response.data.event_time, 'hh:mm A').format('hh:mm A');
                }

                var flag_class = '';
                if (event_type == 'workout') {
                    var flag_class = 'workout';
                } else if (event_type == 'meal') {
                    var flag_class = 'meal';
                } else {
                    var flag_class = 'rest';
                }

                html += '<h2><span class="flag ' + flag_class + '"></span>' + title + ' - ' + time + '</h2>';
                html += '<div class="event-content">' + content + '</div>';
                if (editable != 'false') {
                    html += '<button data-id="' + id + '" id="delete-event">Delete Event</button>';
                }



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
    var full_day = '';
    var event_time = '';
    var event_type = '';

    $.each(obj, function(index, value) {

        id = obj[index].id;
        event_title = obj[index].title;
        event_start = moment(obj[index].event_date).format("YYYY-MM-DD");
        full_day = obj[index].full_day;
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

        if (full_day == 1) {
            events.push({
                'id': id,
                'title': event_title,
                'start': event_start,
                'allDay': true,
                'color': event_color
            });
        } else {
            events.push({
                'id': id,
                'title': event_title,
                'start': event_start + 'T' + event_time,
                'color': event_color
            });
        }

    });

    var settings = {
        showNonCurrentDates: false,
        defaultView: 'month',
        header: {
            left: 'prev, title',
            center: '',
            right: 'month,agendaWeek,listWeek, next'
        },
        eventLimit: true,
        dayNamesShort: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        viewRender: function(currentView) {
            $('.ev-popup').remove();
            if (editable != 'false') {
                $(".fc-left").append('<button class="ev-popup"><i class="muscle-icon"></i> Add New Event</button>');
            }
        }
    };


    if (!$.isEmptyObject(obj)) {
        settings['events'] = events;
        settings['timeFormat'] = 'h:mm a',
            settings['allDayText'] = 'All Day',
            settings['eventClick'] = function(calEvent, jsEvent, view) {
                load_single_event(calEvent.id, wpshev_ajax_object.calender_editable);
            },
            settings['eventRender'] = function(objEvent, element, view) {
                maxDate = moment().add(2, 'weeks');
                if (view.name === "month") {
                    if (element.find(".fc-content").find(".fc-time").length < 1) {
                        element.find(".fc-content").find(".fc-title").before("<span class='all-day-label'>All Day</span> - ");
                    }
                }
                // Future
                var enable_next_month = $('#enable_next_month').val();
                //alert(enable_next_month);
                if (enable_next_month == 'disable') {
                    $(".fc-next-button").prop('disabled', true);
                    $(".fc-next-button").addClass('fc-state-disabled');
                } else {
                    $(".fc-next-button").removeClass('fc-state-disabled');
                    $(".fc-next-button").prop('disabled', false);
                }
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

var show_feedback_popup = $('#show_feedback_popup').val();
if (show_feedback_popup == 'true') {
$.magnificPopup.open({
  items: {
    src: "<div class='white-popup feedback-popup'><h3>Let us know how did it go!</h3><textarea id='feedback' placeholder='Write your feedback here.'></textarea><small>Next month will automatically starts in 5 days, if review is not submitted.</small><button id='submit-feedback'>Submit Review & Proceed</button><div class='clear'></div></div>",
    type: 'inline'
  }
});
}