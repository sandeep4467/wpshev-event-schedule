(function($) {

    /*Add Event*/

    /*Delete Payment*/

    function delete_payment(id) {

        var data = {
            action: 'ev_delete_payment',
            ajax_nonce: wpshev_ajax_object.ajax_nonce,
            id: id

        };

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: wpshev_ajax_object.admin_url,
            data: data,
            success: function(response) {
                if (response.status == 'ok') {
                    $('#row_' + response.deleted_id).fadeOut('slow', function() {
                        // $('#row_' + response.deleted_id ).remove();
                    });

                }

            }

        });

    }

    $('body').on('click', '.delete', function() {
        var id = $(this).data('id');
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure to want delete this?',
            boxWidth: '30%',
            type: 'red',
            useBootstrap: false,
            buttons: {
                confirm: function() {
                    $('#delete_' + id).text('Deleting...').attr('disabled', 'disabled');
                    delete_payment(id);
                },
                cancel: function() {}
            }

        });

    })




    function update_payment_status(ids) {
        var data = {

            action: 'ev_update_payment_status',

            ajax_nonce: wpshev_ajax_object.ajax_nonce,

            ids: JSON.stringify(ids)

        };
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: wpshev_ajax_object.admin_url,
            data: data,
            success: function(response) {
                if (response.status == 'ok') {
                    $("input:checkbox:checked").attr("checked", false);
                    location.reload();

                }

            }

        });

    }



    $('#mark-paid').click(function() {
        var ids = new Array();

        var checked = $("#data-list input:checked").length > 0;

        if (!checked) {

            alert("Please check at least one checkbox");

            return false;

        }
        $("#data-list input:checkbox").each(function() {

            var $this = $(this);

            if ($this.is(":checked")) {

                ids.push($this.val());

            }

        });
        update_payment_status(ids);
    });

    $('#add-payment-form').submit(function(e) {
        e.preventDefault();
        $('button#add-btn').addClass('disable-btn');
        $('#loader').show();

        var instructor_id = $('#instructor_id').val();
        var name = $('#name').val();
        var purchased = $('#purchased').val();
        var payment_cycle = $('#payment_cycle').val();
        var amount = $('#amount').val();
        var status = $('#status').find('option:selected').val();
        var month_year = $('#month_year').val();

        var data = {
            action: 'ev_add_payment',
            ajax_nonce: wpshev_ajax_object.ajax_nonce,
            instructor_id: instructor_id,
            name: name,
            purchased: purchased,
            payment_cycle: payment_cycle,
            amount: amount,
            status: status,
            month_year: month_year
        };
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: wpshev_ajax_object.admin_url,
            data: data,
            success: function(response) {
              location.reload();
            }
        });

        return false;
    });

}(jQuery));