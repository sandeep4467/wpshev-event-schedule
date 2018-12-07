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
   


    $('#event-start-date').multiDatesPicker({
        dateFormat: "d-M-y"
    });

    $('#event-start-time').timepicker({
      controlType: 'select',
      oneLine: true,
      timeFormat: 'hh:mm tt'
    });

   $('.client_id').change(function(){
     $('#select_client').submit();
   });

    $('body').on('click','.ev-popup',function(){
    $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeIn();
   })

   $('.ev-popup-close').click(function(){
    $('.wpshev-popup-outer, .wpshev-custom-popup-overlay').fadeOut();
   })
   $( "#wpshev_tabs" ).tabs();
})(jQuery);