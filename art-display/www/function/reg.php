<?php
require_once('db.php');

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars($_POST['pass']);
    $repeatpass = $_POST['repeatpass'];
    $email = $_POST['email'];

    if (empty($login) || empty($pass) || empty($repeatpass) || empty($email)) {
        $errorMessage = "Заполните все поля";
    } else {
        if ($pass != $repeatpass) {
            $errorMessage = "Пароли не совпадают";
        } elseif ((mb_strlen($pass,'UTF8')>25)){
            $errorMessage = "Логин пользователя должен быть до 25-и символов!";
    }
        elseif ((mb_strlen($login,'UTF8')>255)){
            $errorMessage = "Логин пользователя должен быть до 25-и символов!";
        }
        else {
            $sql = "INSERT INTO `registeruser` (login, pass, email) VALUES ('$login','$pass','$email')";
            if ($conn->query($sql)) {
                $errorMessage = "Пользователь зарегистрирован!";
            } else {
                $errorMessage = "Ошибка: " . $conn->error;
            }
        }
    }
}
?>

