(function($) {

    $(document).ready(function() {
        $('.ev-popup-with-zoom-anim').magnificPopup({
            type: 'inline',

            fixedContentPos: false,
            fixedBgPos: true,

            overflowY: 'auto',

            closeBtnInside: true,
            preloader: false,

            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });
    });
   
    $('#event-start-date, #event-end-date').datetimepicker({
        controlType: 'select',
        timeInput: true,
        timeFormat: 'hh:mm tt',
        minDate: 0
    });
    

   $('.client_id').change(function(){
     $('#select_client').submit();
   });
   $('.ev-popup').click(function(){
    $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeIn();
   })
   $('.ev-popup-close').click(function(){
    $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeOut();
   })
})(jQuery);