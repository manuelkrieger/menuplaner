$(document).ready(function ($) {
  $('.show-modal').each(function () {
    var modal = '#' + $(this).data('modal');
    var value = $(this).data('value');
    var hrefValue = $(this).attr('href');
    $(this).click(function () {
      $(modal + ' .value').text(value);
      $(modal).modal('show');
      $(modal + ' .btn-warning').click(function () {
        $(modal).modal('hide');
        document.location = hrefValue;
      });
      return false;
    });
  });
});
