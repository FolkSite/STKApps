<div class="row">
    <?php
        if (isset($error)) {
            require __DIR__ . '/../errors/errorsList.php';
        }
    ?>
    <table class="table table-bordered">
        <?php
        if (!empty($data['adsTable'])) {
            foreach ($data['adsTable'] as $adsTable) {
                echo '
                        <tr>
                            <td>
                                <a href="getad?id=' . htmlspecialchars($adsTable['id'], ENT_QUOTES) . '">' . htmlspecialchars($name = $adsTable['name'], ENT_QUOTES) . '</a>
                            </td>
                            <td>' . htmlspecialchars($date = $adsTable['date'], ENT_QUOTES) . '</td>
                            <td>
                                <a href="editad?id=' . htmlspecialchars($adsTable['id'], ENT_QUOTES) . '">Изменить</a>
                            </td>
                            <td>
                                <a href="delad?id=' . htmlspecialchars($adsTable['id'], ENT_QUOTES) . '">Удалить</a>
                            </td>
                        </tr>
                    ';
            }
        }
        ?>
    </table>
</div>
<?php if (isset($data['pagination']['quantityPage']) && $data['pagination']['quantityPage'] > 1): ?>
    <div class="row text-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($data['pagination']['thisPage'] == 1): ?>
                    <li class="disabled">
                        <span aria-hidden="true">&laquo;</span>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo ($data['pagination']['link']); ?><?php echo ($data['pagination']['thisPage'] - 1); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php
                    for ($index = 0; $index < $data['pagination']['quantityPage']; $index++) {
                        // прибавляю единицу, потому что в БД отсчет с 0, а на фронте с 1
                        $numPage = $index + 1;

                        if ($numPage == $data['pagination']['thisPage']) {
                            echo '<li class="active"><a href="' . $data['pagination']['link'] . $numPage . '">' . $numPage . '</a></li>';
                        } else {
                            echo '<li><a href="' . $data['pagination']['link'] . $numPage . '">' . $numPage . '</a></li>';
                        }
                    }
                ?>
                <?php if ($data['pagination']['thisPage'] == $data['pagination']['quantityPage']): ?>
                    <li class="disabled">
                        <span aria-hidden="true">&raquo;</span>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo ($data['pagination']['link']); ?><?php echo ($data['pagination']['thisPage'] + 1); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
<?php endif; ?>