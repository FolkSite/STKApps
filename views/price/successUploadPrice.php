<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="row">
    <p>Файл успешно загружен!</p>   
    <form action="updatePrice" method="POST">
        <button type="submit" class="btn btn-default">Обновить цены</button>
    </form>
</div>