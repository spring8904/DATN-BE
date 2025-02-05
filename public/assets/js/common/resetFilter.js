function resetFilters() {
    // Lấy form cần reset
    let form = document.getElementById("filterForm");
    
    // Reset toàn bộ form về giá trị mặc định
    form.reset();

    // Cập nhật lại giao diện (nếu có các input hiển thị giá trị)
    document.getElementById("amountMin").textContent = document.getElementById("amountMinRange").min;
}
