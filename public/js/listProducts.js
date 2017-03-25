window.onload = function () {
    $(document).on('change', '.checkProductsStatus', function () {
        //var myDiv2Para;
        //класс товара с которым работает выбранный флажок 
        productClass = $(this).attr("data-product");
        if ($(this).prop("checked")) {
            $(productClass).show();
            // Удаляем и восстанавливаем параграф #myDiv2
            //myDiv2Para.prependTo('#productsList');

        } else {
            $(productClass).hide();
            //myDiv2Para = $(productClass).detach();
        }
    });
}