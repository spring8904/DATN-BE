function filterData() {
    var filterData = {};
    $('[data-filter]').each(function () {
        var key = $(this).attr('name');
        var value = $(this).val();
        if (value !== null && value !== '') {
            filterData[key] = value;
        }
    });
    
    return filterData;
}

function searchData() {
    var searchData = {};
    var dataSearch = $('[data-search]').first();

    var key = dataSearch.attr('name');
    var value = dataSearch.val();
    searchData[key] = value;

    return searchData;
}

$(document).on('click', '#applyFilter', function (event) {
    event.preventDefault();

    var dataFilter = filterData();
    
    handleSearchFilter(dataFilter);
});


$(document).on('click', '#search-full', function (event) {
    event.preventDefault();
    var dataSearch = searchData();
    handleSearchFilter(dataSearch);
});

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