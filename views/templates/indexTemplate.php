<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>STKApps</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <!-- стиль для  результата обновления цены -->
        <link href="<?php echo '/../css/listProducts.css'; ?>" rel="stylesheet"> 
    </head>
    <body>

        <div class="container">
            <br>
            <div class="row">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                            <ul class="nav navbar-nav">
                                <li class="dropdown <?php if ($data['thisPage'][0] === 'ads') echo 'active' ?>">
                                    <a href="/ads/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Объявления<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li class="<?php if ($content_view === '/ads/tableAds.php') echo 'active' ?>"><a href="/ads/">Список</a></li>
                                        <li class="<?php if ($content_view === '/ads/addAd.php') echo 'active' ?>"><a href="/ads/addad">Добавить</a></li>
                                    </ul>
                                </li>
                                <li class="<?php if ($data['thisPage'][0] === 'price') echo 'active' ?>"><a href="/price/">Цены</a></li>
                                <li class="<?php if ($data['thisPage'][0] === 'distribution') echo 'active' ?>">
                                    <a href="/distribution/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Рассылка<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li class="<?php if ($content_view === '/distribution/distributionForm.php') echo 'active' ?>"><a href="/distribution/">Добавить</a></li>
                                        <li class="<?php if ($content_view === '/distribution/uploadAds.php') echo 'active' ?>"><a href="/distribution/uploadAds">Обновить</a></li>
                                        <li class="<?php if ($content_view === '/distribution/downloadAds.php') echo 'active' ?>"><a href="/distribution/downloadAds">Скачать</a></li>
                                    </ul>
                                </li>
                            </ul>
                            
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="/auth/logout" title="Выйти"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
                            </ul>
                            
                            <?php if ($data['thisPage'][0] === 'ads'): ?>
                                <form class="navbar-form navbar-right" method="POST" action="search">
                                    <fieldset>
                                        <div class="form-group">
                                            <input name="searchQuery" type="text" class="form-control" placeholder="Поиск">
                                        </div>
                                        <button type="submit" class="btn btn-default">Найти</button>
                                    </fieldset>
                                </form>

                            <?php endif; ?>
                            
                            
                            
                        </div>
                    </div>
                </nav>
            </div>

            <?php require_once __DIR__ . '/../' . $content_view ?>

        </div>
    </body>
</html>