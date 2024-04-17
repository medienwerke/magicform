jQuery(document).ready(function($) {
    $('.mf-expand-btn').on('click', function(e) {
        e.preventDefault();
        if($(this).html() == 'Expand') {
            $(this).html('Close');
            $(this).parent().parent().next('tr').find('.mf-admin-message-wrapper').addClass('mf-expanded');
        } else {
            $(this).html('Expand');
            $(this).parent().parent().next('tr').find('.mf-admin-message-wrapper').removeClass('mf-expanded');
        }
    });
});