/* woocommerce js */
(function($){
  $('.woocommerce-notices-wrapper').on('click',function(){
    $(this).html('');
  });
  $('.woocommerce-message:after').on('click',function(){
    $('.woocommerce-notices-wrapper').html('');
  });
})(jQuery);