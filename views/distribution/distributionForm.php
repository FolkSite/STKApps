<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}

if (isset($data['successful'])) {
    require __DIR__ . '/../successful/successfulList.php';
}
?>
<div class="row">
    <form id="form_ad" class="form-horizontal" action="addAd" method="post">

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Дата</label>
            <div class="col-md-5">
                <p class="form-control-static" id="updatedTime"></p>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Порядковый номер</label>
            <div class="col-md-5">
                <input type="text" value="" name="number" class=" form-control"
                       id="max_number" readonly >
            </div>
        </div>

        <div class="form-group" id="avito_id_div">
            <label for="" class="col-md-3 control-label">ID на авито</label>
            <div class="col-md-5">
                <input type="text" value="" name="id_avito" class=" form-control"
                       id="id_for_send" readonly >
                <span class="help-block" id="help_block_for_id">Вставьте ссылку в поле ниже</span>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Ссылка</label>
            <div class="col-md-5">
                <input id="link" type="text" class="form-control" name="link" value="" placeholder="текст">
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Заголовок</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="header" value="" placeholder="текст">
            </div>
        </div>


        <div class="form-group">
            <label for="" class="col-md-3 control-label">Цена</label>
            <div class="col-md-5">
                <div class="radio">
                    <label>
                        <input type="radio" name="price" value="0" checked id="radio_price_text">
                        <input  name="price" type="number" class="form-control" name=""
                                placeholder="0" id="price_text">
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="price" value="negotiated_price" id="negotiated_price">Цена договорная
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group" id="organization_div">
            <label for="" class="col-md-3 control-label">Название фирмы</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="organization" value=""
                       placeholder="текст" id="organization_send">
                <span class="help-block" id="help_block_for_organization"></span>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Имя</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="name" value="" placeholder="текст">
            </div>
        </div>

        <div class="form-group" id="telephone_number_div">
            <label for="" class="col-md-3 control-label">Телефонный номер</label>
            <div class="col-md-5">
                <input class="form-control" name="telephone_number" value=""
                       placeholder="" id="telephone_number_send">
                <span class="help-block" id="help_block_for_telephone_number"></span>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Адрес</label>
            <div class="col-md-5">
                <select class="form-control" name="address">
                    <option value="Россия">По всей России</option>
                    <option value="Краснодарский край">Краснодарский край</option>
                    <option value="Краснодарский край, Краснодар">Краснодар</option>
                    <option value="" disabled>--РАЙОНЫ ГОРОДА--</option>
                    <option value="Краснодарский край, Краснодар р-он Западный">Западный</option>
                    <option value="Краснодарский край, Краснодар р-он Карасунский">Карасунский</option>
                    <option value="Краснодарский край, Краснодар р-он Прикубанский">Прикубанский</option>
                    <option value="Краснодарский край, Краснодар р-он Старокорсунская">Старокорсунская</option>
                    <option value="Краснодарский край, Краснодар р-он Центральный">Центральный</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Текст объявления</label>
            <div class="col-md-8">
                <textarea class="form-control" rows="5" name="text_ad"></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-md-3 control-label">Отправил письмо</label>
            <div class="col-md-5">

                <div class="radio">
                    <label>
                        <input type="radio" name="message" value="yes" checked> да
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="message" value="no"> нет
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-3 col-md-3">
                <button type="submit" class="btn btn-default">Добавить</button>
            </div>
        </div>
    </form>
</div>

<!-- библиотека для создания маски телефонного номера -->
<script src="<?php echo '/../js/jquery.maskedinput.js'; ?>" type="text/javascript"></script>

<script src="<?php echo '/../js/distributionForm.js'; ?>"></script>