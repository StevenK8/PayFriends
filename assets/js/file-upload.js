(function($) {
  'use strict';
  if($('[name^="notification"]').length>0){
    $('#notificationDropdown').append('<span class="count-symbol bg-danger"></span>');
  }
  
  $(function() {
    $('.file-upload-browse').on('click', function() {
      var file = $(this).parent().parent().parent().find('.file-upload-default');
      file.trigger('click');
    });
    $('.file-upload-default').on('change', function() {
      $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });
  });
})(jQuery);