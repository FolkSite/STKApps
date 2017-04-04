<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="row">
    <form class="form-horizontal" action="createad" method="POST">
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">Заголовок</label>
            <div class="col-md-10">
                <input name="name" type="text" class="form-control" id="name" placeholder="" value="<?php echo @$_POST['name'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="sku" class="col-md-2 control-label">Артикулы</label>
            <div class="col-md-10">
                <input name="sku" type="text" class="form-control" id="sku" placeholder="" value="<?php echo @$_POST['sku'] ?>">
                <span id="helpBlock" class="help-block">Разделяйте артикулы пробелом</span>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Оформление</label>
            <div class="col-md-10">
                <button type="button" class="btn btn-default" id="btnBold">
                    <span class="glyphicon glyphicon-bold" aria-hidden="true"></span>
                </button>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-md-2 control-label">Объявление</label>
            <div class="col-md-10">
                <textarea name="description" rows="30" class="form-control" id="description"><?php
                    if (!empty($_POST)) {
                        echo @$_POST['description'];
                    } else {
                        // небезопасно, но иначе ломается верстка объявления
                        echo $data['description'];
                    }     
                    ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-default">Создать</button>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo $data['publicDir'] . 'js/editAd.js'; ?>"></script>
