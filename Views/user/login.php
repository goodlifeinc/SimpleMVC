<?php if($model) : ?>
    <?= $model->error ? $model->error : ''; ?>
<?php endif; ?>
<form action="" method="post">
    <input type="text" placeholder="Username.." name="username" />
    <input type="password" placeholder="Password.." name="password" />
    <input type="submit" value="login" />
</form>
