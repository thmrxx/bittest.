<?php
/**
 * @var \application\models\UserModel $user
 * @var float $value
 * @var string $error
 * @var bool $pay
 */
?>

<h1>Pay <?php echo $value; ?> $</h1>

<?php if ($pay) { ?>
    <form action="<?php echo \core\Route::createUrl('site/confirmPay'); ?>" method="POST">
        <div class="alert alert-warning">
            Confirm transaction: <?php echo $value; ?> $
        </div>
        <input type="hidden" name="pay-hash" value="<?php echo $pay; ?>"/>
        <button name="action" class="btn btn-lg btn-primary btn-block">Pay!</button>
    </form>
<?php } ?>

<?php if ($error) { ?>
    <div class="alert alert-warning" role="alert">
        <?php echo $error; ?>
    </div>
<?php } ?>
