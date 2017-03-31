<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="row">
    <p class="bg-warning">Перед загрузкой, обязательно ознакомьтесь с инструкцией, как правильно сохранить файл в требуемый форма!</p>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Windows
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <p>Сохраните прайс в MS Excel как "CSV (разделители - запятые)"</p>   
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Linux/Ubuntu
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <p>Сохраните файл в формате CSV</p>
                    <p>
                    <a href="<?php echo $data['publicDir'] . 'img/save.png'; ?>" target="_blank">
                        <img src="<?php echo $data['publicDir'] . 'img/save.png'; ?>" class="img-responsive img-thumbnail" alt="Responsive image">
                    </a>
                    </p>
                    <p>Подтвердите "Использовать формат Текст CSV</p>
                    <p>
                    <a href="<?php echo $data['publicDir'] . 'img/save.png'; ?>" target="_blank">
                        <img src="<?php echo $data['publicDir'] . 'img/format.png'; ?>" class="img-responsive img-thumbnail" alt="Responsive image">
                    </a>
                    </p>
                    <p>В следующем окне восроизведите все настройки точно как на иллюстрации</p>
                    <p>
                    <a href="<?php echo $data['publicDir'] . 'img/save.png'; ?>" target="_blank">
                        <img src="<?php echo $data['publicDir'] . 'img/export.png'; ?>" class="img-responsive img-thumbnail" alt="Responsive image">
                    </a> 
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <form enctype="multipart/form-data" action="uploadPrice" method="POST">
        <div class="form-group">
            <label for="uploadedFile">Выберите файл</label>
            <input type="file" name="uploadedFile">
        </div>
        <button type="submit" class="btn btn-default uploadBtn">Отправить</button>
    </form>
</div>

<script src="<?php echo $data['publicDir'] . 'js/uploadPrice.js'; ?>"></script>