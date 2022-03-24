
<?php

spl_autoload_register(function ($class) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'core'. DIRECTORY_SEPARATOR . $class . '.php';
});

require_once 'app/utils/routes.php';


?>