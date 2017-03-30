<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="row">
    <p>Файл успешно загружен!</p>   
    <form action="updatePrice" method="POST">
        <button type="submit" class="btn btn-default uploadBtn" data-loading-text="Выполнение...">Обновить цены</button>
    </form>
</div>

<script src="<?php echo $data['publicDir'] . 'js/uploadPrice.js'; ?>"></script>