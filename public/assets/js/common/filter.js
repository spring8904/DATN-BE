function filterData(attribute) {
    var filterData = {};
    $(attribute).each(function () {
        var key = $(this).attr('name');
        var value = $(this).val();
        filterData[key] = value;
    });
    
    return filterData;
}

$(document).on('click', '#applyFilter', function (event) {
    event.preventDefault();

    var dataFilter = filterData('[data-filter]');

    handleSearchFilter(dataFilter);
});

$(document).on('click', '#applyAdvancedFilter', function (event) {
    event.preventDefault();

    var advancedFilter = filterData('[data-advanced-filter]');
    handleSearchFilter(advancedFilter);
});