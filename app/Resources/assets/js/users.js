$(document).ready(function ($) {
    $('.user-disable').each(function () {
        var name = $(this).data('name');
        var hrefValue = $(this).attr('href');
        $(this).click(function () {
            $('#disable-modal .modal-title').text('Benutzer wirklich deaktivieren?');
            $('#disable-modal .modal-body p').text('Wollen Sie den Benutzer "' + name + '" wirklich deaktivieren?');
            $('#disable-modal').modal('show');
            $('#disable-modal .btn-warning').click(function () {
                $('#disable-modal').modal('hide');
                document.location = hrefValue;
            });
            return false;
        });
    });
});
