<?php
require('app/views/templates/header.php');
?>

<div class="container mt-4">
    <div class="col">

        <?php
        if (isset($_COOKIE['user'])):
        ?>
        <p>Привет <?= $_COOKIE['user'] ?>. Ты уже авторизован, чтобы выйти нажми <button id='btn-logout'>здесь</button></p>
        <?php
        else :
        ?>
        <h1>Форма авторизации</h1>
        <form method="post" id="form_login">
            <label name='lbl-authorization-fail'></label>
            <label for="login" name='lbl-login-error'></label>
            <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин"><br>
            <label for="password" name='lbl-password-error'></label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль"><br>
            <button class="btn btn-success" type="button" id='btn-login'>Авторизироваться</button>
        </form>
    </div>
        <?php
        endif;
        ?>
</div>

<?php
require('app/views/templates/footer.php');
?>