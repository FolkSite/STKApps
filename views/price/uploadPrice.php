<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="row">
    <p>Для обновления цен на сайте, загрузите прайс, сохраненный MS Excel в формате "CSV (разделители - запятые)"</p>   
    <form enctype="multipart/form-data" action="uploadPrice" method="POST">
        <div class="form-group">
            <label for="uploadedFile">Выберите файл</label>
            <input type="file" name="uploadedFile">
        </div>
        <button type="submit" class="btn btn-default uploadBtn">Отправить</button>
    </form>
</div>

<script src="<?php echo '/../js/uploadPrice.js'; ?>"></script>