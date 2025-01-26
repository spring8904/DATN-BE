$(document).on('click', "#restoreSelected", function (event) {
    event.preventDefault();

    var selectedItems = [];
    $('input[name="itemID"]:checked').each(function () {
        selectedItems.push($(this).val());
    });

    if (selectedItems.length == 0) {
        Swal.fire({
            title: 'Chọn ít nhất 1 lựa chọn để khôi phục',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    let restoreUrl = routeRestoreUrl.replace(':itemID',
        selectedItems.join(','));

    Swal.fire({
        title: "Bạn có muốn khôi phục ?",
        text: "Bạn sẽ khôi phục dữ liệu đã xoá!!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Đồng ý!!",
        cancelButtonText: "Huỷ!!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "PUT",
                url: restoreUrl,
                success: function (data) {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Thao tác thành công!',
                            text: data.message,
                            icon: 'success'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else if (data.status === 'error') {
                        Swal.fire({
                            title: "Thao tác thất bại!",
                            text: data.message,
                            icon: 'error'
                        });
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                    Swal.fire({
                        title: "Thao tác thất bại!",
                        text: data.responseJSON.message,
                        icon: 'error'
                    });
                }
            });
        }
    });
});