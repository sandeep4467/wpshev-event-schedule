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

                url: wpshev_ajax_object.admin_url,

                data: data,

                success: function(response) {

                    if (response.status == 'success') {

                        $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeOut();

                        $('#ev-add-event').find("input[type=text], textarea").val("");

                        var tinymce_editor_id = 'kv_frontend_editor';

                        tinymce.get(tinymce_editor_id).setContent('');

                        document.getElementById("ev-add-event").reset();

                        location.reload();



                    }

                    if (response.status == 'error') {

                        $.toast({

                            heading: 'Information',

                            text: response.message,

                            icon: 'info',

                            loader: true, // Change it to false to disable loader

                            loaderBg: '#9EC600', // To change the background

                            position: {

                                right: 20,

                                top: 120

                            },

                            hideAfter: 5000 // in milli seconds

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

        var customer_id = $("input[name=client_id]").val();

        var instructor_id = $("input[name=instructor_id]").val();

        var job_id = $("input[name=job_id]").val();




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

                url: wpshev_ajax_object.admin_url,

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



        function clearInput() {

            $('#chat-message').val('');

        };



        /*========= Chat Module =================*/


        function load_chat(client_id, instructor_id) {

            var data = {

                action: 'load_chat',

                client_id: client_id,

                instructor_id: instructor_id

            };



            $.ajax({

                type: 'POST',

                dataType: "json",

                url: wpshev_ajax_object.admin_url,

                data: data,

                success: function(response) {

                    if (response.status == 'success') {

                        $('#user-chat').html(response.data);

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

        }



        var client_id = $("input[name=client_id]").val();

        var instructor_id = $("input[name=instructor_id]").val();

        load_chat(client_id, instructor_id);




        setInterval(function() {



            load_chat(client_id, instructor_id);

            $(".msg_container_base").stop().animate({

                scrollTop: $(".msg_container_base")[0].scrollHeight

            }, 1000);

        }, 2000);



        function send_message() {



            var message = $("#chat-message").val();

            var by = $("input[name=by]").val();

            var name = $("input[name=user_name]").val();

            var pic = $("input[name=user_pic]").val();



            var html = '';



            html += '<div class="chat-repeater">';

            html += '<figure class="user-img">';

            html += '<img src="' + pic + '">';

            html += '</figure>';

            html += '<div class="chat-text">';

            html += '<span class="user-info"> ' + name + ' <strong>Just Now</strong></span>';

            html += '<p>' + message + '</p>';

            html += '</div>';

            html += '</div>';



            $('#user-chat').append(html);



            clearInput();

            $(".msg_container_base").stop().animate({

                scrollTop: $(".msg_container_base")[0].scrollHeight

            }, 1000);

            var data = {

                action: 'add_chat',

                client_id: client_id,

                instructor_id: instructor_id,

                by: by,

                message: message,

                message_time: new Date()

            };



            $.ajax({

                type: 'POST',

                dataType: "json",

                url: wpshev_ajax_object.admin_url,

                data: data,

                success: function(response) {

                    if (response.status == 'success') {




                    }

                    if (response.status == 'error') {

                        $.toast({

                            heading: 'Information',

                            text: response.message,

                            icon: 'info',

                            loader: true, // Change it to false to disable loader

                            loaderBg: '#9EC600', // To change the background

                            position: {

                                right: 20,

                                top: 120

                            },

                            hideAfter: 5000 // in milli seconds

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

        }

        // Chat AJAX

        $('#send').click(function(e) {

            e.preventDefault();

            send_message();

        });



        $(document).keypress(function(e) {

            if (e.ctrlKey && e.keyCode == 13) {

                send_message();

            }

        });

        /*========= Chat Module End =================*/



        /*=================Start Job==================*/

        $('#proceed-to-start').click(function(e) {
            $(this).text('Proceeding...').addClass('disable-btn');
            e.preventDefault();
            var data = {
                action: 'ev_start_job',
                job_id: wpshev_ajax_object.job_id,
                client_id: customer_id,
                instructor_id: instructor_id,
                started_by: 'client'
            };
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: wpshev_ajax_object.admin_url,
                data: data,
                success: function(response) {
                    setTimeout(function() {
                        $('#proceed-to-start').text('Redirecting...').addClass('disable-btn');
                    }, 2000);
                    if (response.status == 'success') {
                        $.dialog({
                            title: 'Awesome!! Hold On',
                            content: 'We are getting things ready...',
                            icon: 'fa fa-smile-o',
                            closeIcon: false,
                            animation: 'scale',
                            type: 'blue'

                        });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);

                    }
                    if (response.status == 'error') {
                        $.toast({

                            heading: 'Information',

                            text: response.message,

                            icon: 'info',

                            loader: true, // Change it to false to disable loader

                            loaderBg: '#9EC600', // To change the background

                            position: {

                                right: 20,

                                top: 120

                            },

                            hideAfter: 5000 // in milli seconds

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

        $('#proceed-to-start-by-instructor').click(function(e) {
            $(this).text('Proceeding...').addClass('disable-btn');
            e.preventDefault();
            var data = {
                action: 'ev_start_job',
                job_id: wpshev_ajax_object.job_id,
                client_id: customer_id,
                instructor_id: instructor_id,
                started_by: 'instructor'
            };
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: wpshev_ajax_object.admin_url,
                data: data,
                success: function(response) {
                    setTimeout(function() {
                        $('#proceed-to-start').text('Redirecting...').addClass('disable-btn');
                    }, 2000);
                    if (response.status == 'success') {
                        $.dialog({
                            title: 'Awesome!! Hold On',
                            content: 'We are getting things ready...',
                            icon: 'fa fa-smile-o',
                            closeIcon: false,
                            animation: 'scale',
                            type: 'blue'

                        });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);

                    }
                    if (response.status == 'error') {
                        $.toast({

                            heading: 'Information',

                            text: response.message,

                            icon: 'info',

                            loader: true, // Change it to false to disable loader

                            loaderBg: '#9EC600', // To change the background

                            position: {

                                right: 20,

                                top: 120

                            },

                            hideAfter: 5000 // in milli seconds

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
        /*=================== Get all notes ==================*/



        function get_all_notes(job_id) {



            var data = {

                action: 'ev_get_notes',

                job_id: job_id

            };

            $.ajax({

                type: 'POST',

                dataType: "json",

                url: wpshev_ajax_object.admin_url,

                data: data,

                success: function(response) {

                    if (response.status == 'success') {

                        $('#note-container').html(response.data);

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

        }



        get_all_notes(job_id);



        /*=================== Add note ==================*/

        $('#send-note').click(function(e) {

            e.preventDefault();

            var note = $('#note').val();

            if (note == '') {

                alert('Please add the note!!');

            }



            var data = {

                action: 'ev_add_note',

                instructor_id: instructor_id,

                job_id: job_id,

                note: note

            };

            $.ajax({

                type: 'POST',

                dataType: "json",

                url: wpshev_ajax_object.admin_url,

                data: data,

                success: function(response) {

                    if (response.status == 'success') {

                        get_all_notes(job_id);

                        $('#note').val('');

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

        });



        /*=================== Delete note ==================*/

        $("body").on("click", ".delete-note", function(e) {

            e.preventDefault();

            var id = $(this).data('id');

            var e_id = $(this).parents('.note-repater').attr('id');



            if (id == '') {

                alert('Id is required!!');

            }



            $.confirm({

                title: 'Confirm!',

                content: 'Are you sure to want delete this?',

                boxWidth: '30%',

                type: 'red',

                useBootstrap: false,

                buttons: {

                    confirm: function() {

                        var data = {

                            action: 'ev_delete_note',

                            id: id

                        };

                        $.ajax({

                            type: 'POST',

                            dataType: "json",

                            url: wpshev_ajax_object.admin_url,

                            data: data,

                            success: function(response) {

                                $('#' + e_id).remove();

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

                    },

                    cancel: function() {}

                }

            });

            return false;

        });




    });

}(jQuery));