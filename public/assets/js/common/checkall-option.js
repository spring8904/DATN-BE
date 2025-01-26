$(document).on('change', '#checkAll', function () {
    const isChecked = $(this).prop('checked');
    $('input[name="itemID"]').prop('checked', isChecked);
});