<?php
require('app/views/templates/header.php');
?>

<div class="container mt-4">
    <div class="col">
        <h1>Форма регистрации</h1>
        <form id='register_form' method="post">
            <label name='lbl-registration-fail'></label>
            <label for="login" name='lbl-login-error'></label>
            <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин"><br>
            <label for="name" name='lbl-name-error'></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя"><br>
            <label for="email" name='lbl-email-error'></label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Введите почту"><br>
            <label for="password" name='lbl-password-error'></label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль"><br>
            <input type="password" class="form-control" name="password_duplicate" id="password_duplicate" placeholder="Повторите пароль"><br>
            <button class="btn btn-success" type="button" id='btn-reg'>Зарегистрироваться</button>
        </form>
    </div>
</div>

<?php
require('app/views/templates/footer.php');
?>