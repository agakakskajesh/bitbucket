<?php

require_once('app/models/User.php');

class UserController
{
    private $jsonArray;

    public function __construct()
    {
        $json = file_get_contents('resources/db.json');
        $this->jsonArray = json_decode($json, true);
    }

    function login($data)
    {
        $this->validateRequestFromAjax();

        $login = htmlspecialchars($data['login']);
        $password = htmlspecialchars($data['password']);

        $isLogin = false;

        $errors = [];

        if (empty($login)) {
            $errors[] = ['login_error_message' => 'Заполните поле логина'];
        }

        if (empty($password)) {
            $errors[] = ['password_error_message' => 'Заполните поле пароля'];
        }

        if (count($errors) > 0) {
            echo json_encode($errors);
            die();
        }

        foreach ($this->jsonArray as $user) {
            if ($user['login'] === $login && $user['password'] === md5($password . 'solid')) {
                session_start();
                $_SESSION['user'] = $login;
                setcookie('user', $user['login'], time() + 3600, "/");
                $isLogin = true;
                break;
            }
        }

        if ($isLogin == true) {
            echo json_encode(['path' => '/']);
        } else {
            echo json_encode(['authorization_failed_message' => 'Неверный логин или пароль']);
        }
    }

    function logout()
    {
        $this->validateRequestFromAjax();
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        setcookie('user', $_COOKIE['user'], time() - 3600, "/");
        echo json_encode(['path' => '/']);
    }

    function register($data)
    {

        $this->validateRequestFromAjax();
        $login = htmlspecialchars($data['login']);
        $email = htmlspecialchars($data['email']);
        $name = htmlspecialchars($data['name']);
        $password = htmlspecialchars($data['password']);
        $dup_password = htmlspecialchars($data['password_duplicate']);

        $errors = [];

        if (!preg_match('/^[A-Za-z]{6,}$/', $login) || empty($login)) {
            $errors[] = ['register_login_message' => 'Логин должен состоять более чем из 6 символов'];
        }

        if (!preg_match('/^[A-Za-z]{2,}$/', $name) || empty($name)) {
            $errors[] = ['register_name_message' => 'Имя должно состоять более чем из 2 символов'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
            $errors[] = ['register_email_message' => 'Неверная форма электронной почты'];
        }

        if (!preg_match('/^\S*(?=\S{6,})(?=\S*[A-Za-z])(?=\S*[\d])\S*$/', $password) || empty($password)) {
            $errors[] = ['register_password_message' => 'Пароль должен состоять из более чем 6 символов и должен содержать хотя бы 1 букву и цифру'];
        }

        if ($password !== $dup_password) {
            $errors[] = ['register_password_message' => 'Пароли не совпадают'];
        }

        if ($this->findByLogin($data) != null) {
            $errors[] = ['register_exists_message' => 'Пользователь с таким логином уже существует'];
        }

        if ($this->findByEmail($data) != null) {
            $errors[] = ['register_email_exists_message' => 'Пользователь с такой почтой уже существует'];
        }

        if (count($errors) > 0) {
            echo json_encode($errors);
            die();
        }

        $encryptedPassword = md5($password . 'solid');
        $user = new User($name, $login, $email, $encryptedPassword);
        $this->jsonArray[] = $user->getData();

        file_put_contents('resources/db.json', json_encode($this->jsonArray));
        echo json_encode(['path' => '/login']);
    }

    function update($data)
    {
        $this->validateRequestFromAjax();

        $login = htmlspecialchars($data['login']);
        $email = htmlspecialchars($data['email']);
        $name = htmlspecialchars($data['name']);
        $password = htmlspecialchars($data['password']);  

        $errors = [];

        if (!preg_match('/^[A-Za-z]{2,}$/', $name) || empty($name)) {
            $errors[] = ['register_name_message' => 'Имя должно состоять более чем из 2 символов'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
            $errors[] = ['register_email_message' => 'Неверная форма электронной почты'];
        }

        if (!preg_match('/^\S*(?=\S{6,})(?=\S*[A-Za-z])(?=\S*[\d])\S*$/', $password) || empty($password)) {
            $errors[] = ['register_password_message' => 'Парооль должен состоять из более чем 6 символов и должен содержать хотя бы 1 букву и цифру'];
        }

        if ($this->findByLogin($data) != null) {
            $errors[] = ['register_exists_message' => 'Пользователь с таким логином уже существует'];
        }

        if ($this->findByEmail($data) != null) {
            $errors[] = ['register_email_exists_message' => 'Пользователь с такой почтой уже существует'];
        }

        if (count($errors) > 0) {
            echo json_encode($errors);
            die();
        }

        $encryptedPassword = md5($password . 'solid');
        $user = new User($name, $login, $email, $encryptedPassword);

        foreach ($this->jsonArray as &$item) {
            if ($user['login'] === $login) {
                $item = $user->getData();
            }
        }

        file_put_contents('resources/db.json', json_encode($this->jsonArray));
        echo json_encode(['path' => '/']);
    }

    function delete($data)
    {
        $this->validateRequestFromAjax();

        $login = htmlspecialchars(trim($data['login']));

        if ($this->findByLogin($data) == null) {
            json_encode(['register_exists_message' => 'Пользователь с таким логином уже существует']);
            die();
        }

        foreach ($this->jsonArray as &$user) {
            if ($user['login'] === $login) {
                unset($user);
            }
        }
        file_put_contents('resources/db.json', json_encode($this->jsonArray));
        echo json_encode(['path' => '/']);
    }

    function findByLogin($data)
    {
        $this->validateRequestFromAjax();

        $login = htmlspecialchars($data['login']);
        if (count($this->jsonArray) > 0) {
            foreach ($this->jsonArray as $item) {
                if (strtolower($item['login']) === strtolower($login)) {
                    return json_encode(new User($item['name'], $item['login'], $item['email'], $item['password']));
                }
            }
        }
        return null;
    }

    function findByEmail($data)
    {
        $this->validateRequestFromAjax();

        $email = htmlspecialchars($data['email']);
        if (count($this->jsonArray) > 0) {
            foreach ($this->jsonArray as $item) {
                if ($item['email'] === $email) {
                    return json_encode(new User($item['name'], $item['login'], $item['email'], $item['password']));
                }
            }
        }
        return null;
    }

    private function validateRequestFromAjax()
    {
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            Router::redirectToNotAjaxRequest();
            exit;
        }
    }
}
