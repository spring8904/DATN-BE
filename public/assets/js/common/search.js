function searchData() {
    var searchData = {};
    var dataSearch = $('[data-search]').first();

    var key = dataSearch.attr('name');
    var value = dataSearch.val();
    searchData[key] = value;

    return searchData;
}

$(document).on('click', '#search-full', function (event) {
    event.preventDefault();
    var dataSearch = searchData();
    handleSearchFilter(dataSearch);
});