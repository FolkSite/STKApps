<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>

<div class="row">
    <div class="col-md-8" id="productsList">
        <?php
        if (!empty($data['resultUpdate'])) {
            foreach ($data['resultUpdate'] as $product) {
                echo '
                    <div class="' . $product['class'] . '">
                        <p>' . $product['text'] . '</p>
                        <p class="productDetail">' . $product['detail'] . '</p>
                        <br>
                    </div> 
                ';
            }
        }
        ?>
    </div>
    <div class="col-md-4">
        <div class="checkbox">
            <label>
                <input class="checkProductsStatus" type="checkbox" value="" checked data-product=".priceChanged">
                Цена обновлена
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input class="checkProductsStatus" type="checkbox" value="" checked data-product=".pricePrevious">
                Цена без изменений 
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input class="checkProductsStatus" type="checkbox" value="" checked data-product=".notFound">
                Товар не найден
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input class="checkProductsStatus" type="checkbox" value="" data-product=".productDetail">
                Отладка
            </label>
        </div>
    </div>
</div>

<script src="<?php echo '/../js/listProducts.js'; ?>"></script>