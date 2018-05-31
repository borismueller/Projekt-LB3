$(document).ready(function() {

  $('.props').click(function(e) {
    e.preventDefault();

    var props_url = $(this).attr('href');

    $.ajax({
      url: props_url,
      success: function (data) {
        $('.properties').html(data);
      }
    });

    return false;

  });

});
