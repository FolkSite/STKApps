window.onload = function () {
    $(document).on('change', '.checkProductsStatus', function () {
        //класс товара с которым работает выбранный флажок 
        productClass = $(this).attr("data-product");
        if ($(this).prop("checked")) {
            $(productClass).show()
        } else {
            $(productClass).hide()
        }
    });
}