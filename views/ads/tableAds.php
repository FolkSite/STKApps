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
                // сокращает количество страниц в нумерации, чтобы не ломать верстку, если их слишком много
                /*
                $quantityPage = 10;
                var_dump('');
                var_dump($data['pagination']['thisPage'] > 5);
                var_dump(($data['pagination']['thisPage'] + 3) > $data['pagination']['quantityPage']);
                var_dump(($data['pagination']['thisPage'] + 3));
                if (($data['pagination']['thisPage'] + 3) < $data['pagination']['quantityPage']) {
                    if ($data['pagination']['thisPage'] > 3) {
                        $paginationStart = $data['pagination']['thisPage'] - 3;
                        $paginationEnd = $data['pagination']['thisPage'] + 3;
                    }
                } else {
                    $paginationStart = 0;
                    $paginationEnd = $data['pagination']['quantityPage'];
                }
                */
                
                $thisPage = $data['pagination']['thisPage'];
                $quantityPage = $data['pagination']['quantityPage'];
                
                $maxPagesNumbersOnPage = 10;
                
                $leftOfThisPage = ($maxPagesNumbersOnPage/2) + 1;
                $rightOfThisPage = $maxPagesNumbersOnPage/2;
                
                if (($thisPage - $leftOfThisPage) > 0 && ($thisPage + $rightOfThisPage) < $quantityPage) {
                    
                    $paginationStart = $thisPage - $leftOfThisPage;
                    $paginationEnd = $thisPage + $rightOfThisPage;
                    
                } elseif (($thisPage - $leftOfThisPage) > 0 && ($thisPage + $rightOfThisPage) >= $quantityPage) {
                    
                    $paginationStart = ($thisPage - $leftOfThisPage) + $quantityPage - ($thisPage + $rightOfThisPage);
                    $paginationEnd = $quantityPage;
                    
                } elseif (($thisPage - $leftOfThisPage) <= 0 && ($thisPage + $rightOfThisPage) <= $quantityPage){
                    
                    var_dump('last elseif');
                    $paginationStart = 0;
                    $paginationEnd = $maxPagesNumbersOnPage;
                    
                }
                

                var_dump($data['pagination']['thisPage']);
                var_dump($data['pagination']['quantityPage']);
                var_dump($paginationStart);
                var_dump($paginationEnd);

                for ($index = $paginationStart; $index < $paginationEnd; $index++) {
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