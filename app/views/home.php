<?php
require_once('app/views/templates/header.php');
?>

<div class="container mt-4">

    <a href="/login">Авторизироваться</a>
    <a href="/register">Регистрация</a>

    <?php
    if (isset($_COOKIE['user'])) :
    ?>
        <p>Привет <?= $_COOKIE['user'] ?>. Чтобы выйти нажмите <button id='btn-logout'>здесь</button></p>
    <?php
    endif;
    ?>
</div>

<?php
require('app/views/templates/footer.php');
?>