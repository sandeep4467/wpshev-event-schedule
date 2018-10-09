(function($) {
    $(document).ready(function() {
        /*Add Event*/
        $('#ev-add-event').submit(function(e) {
            e.preventDefault();
            var form = $(this);

            var data = {
                action: 'ev_add_event',
                data: form.serialize() + '&activeEditor=' + tinymce.activeEditor.getContent()
            };
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: fitness_ajaxurl.ajaxurl,
                data: data,
                success: function(response) {
                    if (response.status == 'success') {

                        var event_title = $("[name='event_title']").val();
                        var event_start = moment($("[name='event-start-date']").val()).format("YYYY-MM-DD[T]HH:mm:ss");
                        var event_end = moment($("[name='event-end-date']").val()).format("YYYY-MM-DD[T]HH:mm:ss");

                        alert(event_end);

                        var event={
                            'id': response.lastid,
                            'title': event_title,
                            'start': event_start,
                            'end': event_end
                        };

                        $('#calendar').fullCalendar( 'renderEvent', event, true);


                        $("[name='event_title']").val('');
                        $("[name='event-start-date']").val('');
                        $("[name='event-end-date']").val('');

                        var tinymce_editor_id = 'kv_frontend_editor'; 
                        tinymce.get(tinymce_editor_id).setContent('');

                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: {
                                right: 20,
                                top: 120
                            },
                            hideAfter: 5000   // in milli seconds
                        });
                    }
                    if (response.status == 'error') {
                        $.toast({
                            heading: 'Information',
                            text: response.message,
                            icon: 'info',
                            loader: true,        // Change it to false to disable loader
                            loaderBg: '#9EC600',  // To change the background
                            position: {
                                    right: 20,
                                    top: 120
                            },
                            hideAfter: 5000   // in milli seconds
                        })
                    }



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
            return false;
        })

        /*Print Calender*/
        var customer_id = $("input[name=customer_id]").val();
        var instructor_id = $("input[name=user_id]").val();
        get_events_calender(customer_id, instructor_id, true);

        /*Delete Event*/
        function delete_event(id) {
            var data = {
                action: 'ev_delete_event',
                event_id: id
            };
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: fitness_ajaxurl.ajaxurl,
                data: data,
                success: function(response) {

                    if (response.status == 'success') {
                        var magnificPopup = $.magnificPopup.instance; // save instance in magnificPopup variable
                        magnificPopup.close(); // Close popup that is currently opened

                        $("#calendar").fullCalendar('removeEvents', response.id);
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
        $('body').on('click', '#delete-event', function() {
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure to want delete this?',
                boxWidth: '30%',
                type: 'red',
                useBootstrap: false,
                buttons: {
                    confirm: function() {
                        delete_event($('#delete-event').data('id'));
                    },
                    cancel: function() {
                        var magnificPopup = $.magnificPopup.instance; // save instance in magnificPopup variable
                        magnificPopup.close(); // Close popup that is currently opened   
                    }
                }
            });
        });

    });
}(jQuery));