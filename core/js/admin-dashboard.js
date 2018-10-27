    (function($) {
    $(document).ready(function() {
        $('#assign_instructor_form').submit(function(e) {
            e.preventDefault();
            
            var client_id = $('input[name=client_user_id]').val();
            var access_limited_time_type = $('input[name=access_limited_time_type]').val();
            var access_limited_time_value = $('input[name=access_limited_time_value]').val();
            var level_id = $('input[name=level_id]').val();
            var price = $('input[name=price]').val();

            var instructor_id = $('input[name=instructor_id]:checked').val();

            var data = {
                action: 'ev_assign_instructor',
                client_id: client_id,
                instructor_id: instructor_id,
                access_limited_time_type: access_limited_time_type,
                access_limited_time_value: access_limited_time_value,
                level_id: level_id,
                price: price,
                security: wpshev_ajax_object.ajax_nonce
            };
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: wpshev_ajax_object.admin_url,
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: {
                                right: 20,
                                top: 120
                            },
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
                    window.setTimeout(function() {
                       // location.reload(true);
                    }, 5000)
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
    });
}(jQuery));   