<?php
    if (isset($error)) {
        require __DIR__ . '/../errors/errorsList.php';
    }
?>
<div class="row">
    <form class="form-horizontal" action="savead" method="POST">
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">Заголовок</label>
            <div class="col-md-10">
                <input name="name" type="text" class="form-control" id="name" placeholder="Email" value="<?php echo htmlspecialchars($data['adInfo']['name'], ENT_QUOTES) ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="sku" class="col-md-2 control-label">Артикулы</label>
            <div class="col-md-10">
                <input name="sku" type="text" class="form-control" id="sku" placeholder="" value="<?php echo htmlspecialchars($data['adInfo']['sku'], ENT_QUOTES) ?>">
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
                <textarea name="description" rows="30" class="form-control" id="description"><?php echo $data['adInfo']['description'] ?></textarea>
            </div>
        </div>
        <!-- скрытое поле передает id объявления в БД -->
        <input class="hidden" name="id" value="<?php echo htmlspecialchars($data['adInfo']['id'], ENT_QUOTES) ?>">
        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-default">Сохранить</button>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo '/../js/editAd.js'; ?>"></script>
