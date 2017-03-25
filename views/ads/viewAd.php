<div class="row">
    <button type="button" class="btn btn-default" id="copyAdName">Скопировать заголовок</button>
    <a href="editad?id=<?php echo htmlspecialchars($data['adInfo']['id'], ENT_QUOTES) ?>">
        <button type="button" class="btn btn-default pull-right" id="copyAdName">Редактировать</button>
    </a>
</div>
<div class="row">
    <p><strong><span id="adName"><?php echo htmlspecialchars($data['adInfo']['name'], ENT_QUOTES) ?></span></strong></p>
</div>

<div class="row">
    <button type="button" class="btn btn-default" id="copyAdDescription">Скопировать описание</button>
</div>
<div class="row">
    <p>
        <span id="adDescription">
            <?php
            foreach ($data['adInfo']['products'] as $product) {
                echo htmlspecialchars($product, ENT_QUOTES) . "<br>";
            }
            // убрал htmlspecialchars, что является уязвимостью для XSS атак
            // не придумал ничего лучше, надо отобразить хтмл код из описания как текст
            // для вставки на авито
            echo nl2br($data['adInfo']['description']);
            ?>
        </span>

    </p>
</div>

<!-- Функция копирования -->    
<script src="<?php echo '/../js/copyAd.js'; ?>"></script>
