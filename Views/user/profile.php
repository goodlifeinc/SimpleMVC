<h1>Hello, <?= $model->user->getUsername(); ?></h1>

<?php if($model->error) : ?>
    <h2><?= $model->error; ?></h2>
<?php elseif ($model->success) : ?>
    <h2><?= $model->success; ?></h2>
<?php endif; ?>

<form action="" method="post">
    <input type="text" name="username" value="<?= $model->user->getUsername(); ?>" />
    <input type="password" placeholder="Password.." name="password" />
    <input type="password" placeholder="Confirm Password.." name="confirm" />
    <input type="submit" value="edit" name="edit" />
</form>