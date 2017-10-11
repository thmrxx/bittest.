<?php
/**
 * @var string $content
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo \core\App::$instance->config['title']; ?></title>
    <link href="js/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><?php echo \core\App::$instance->config['projectName']; ?></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
                <?php if (!\core\App::$instance->user->isGuest()) { ?>
                    <li><a href="<?php echo \core\Route::createUrl('site/logout'); ?>">Logout (<?php echo \core\App::$instance->user->model->username; ?>)</a></li>
                <?php } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container" style="padding-top: 50px;">
    <?php echo $content; ?>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/web/bootstrap-3.3.7/js/bootstrap.min.js"></script>
</body>
</html>