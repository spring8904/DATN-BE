function handleSearchFilter(dataHandle) {
    $.ajax({
        type: 'GET',
        url: routeUrlFilter,
        data: dataHandle,
        success: function (response) {
            $('#item_List').html(response.html);
        }
    });
}