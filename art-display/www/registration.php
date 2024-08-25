<?php
session_start();
require_once('function/db.php');

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars($_POST['pass']);
    $repeatpass = htmlspecialchars($_POST['repeatpass']);
    $email = htmlspecialchars(trim($_POST['email']));
    $role = 1;

    $sql = "SELECT * FROM `registeruser` WHERE login = '$login'";
    $resultLog = $conn->query($sql);

    $sql = "SELECT * FROM `registeruser` WHERE email = '$email'";
    $resultEmail = $conn->query($sql);
    if ($resultLog->num_rows > 0) {
        while ($row = $resultLog->fetch_assoc()) {
            $errorMessage = "Пользователь " . $row['login'] . " уже зарегистрирован!";
        }
    } elseif ($resultEmail->num_rows > 0) {
        while ($row = $resultEmail->fetch_assoc()) {
            $errorMessage = "Пользователь c email: " . $row['email'] . " уже зарегистрирован!";
        }
    } elseif ($pass != $repeatpass) {
        $errorMessage = "Пароли не совпадают";
    } elseif ((mb_strlen($login, 'UTF8') > 25)) {
        $errorMessage = "Логин пользователя должен быть до 25-и символов!";
    } elseif ((mb_strlen($pass, 'UTF8') > 50)) {
        $errorMessage = "Пароль пользователя должен быть до 50-и символов!";
    } elseif ((mb_strlen($email, 'UTF8') > 50)) {
        $errorMessage = "Email пользователя должен быть до 50-и символов!";
    } elseif(empty($login)) {
        $errorMessage = "Заполните корректно поле Логин!";
    }
    else {
        $hashedPass = md5($pass);
        $sql = "INSERT INTO `registeruser` (role, login, pass, email) VALUES ('$role', '$login','$hashedPass','$email')";
        if ($conn->query($sql)) {
            $userId = $conn->insert_id;
            $sql = "SELECT * FROM `registeruser` WHERE id = '$userId'";
            $result = $conn->query($sql);

            if ($result) {
                // Проверяем, есть ли данные
                if ($result->num_rows > 0) {
                    // Получаем данные пользователя
                    $userData = $result->fetch_assoc();

                    $_SESSION['id'] = $userData ['id'];
                    $_SESSION['login'] = $userData ['login'];
                    $_SESSION['role'] = $userData ['role'];
                    if ($_SESSION['role'] === "0") {
                        header("location: http://art-display/admin/posts/index.php");
                    }
                    header("location: http://art-display/index.php");


                    // echo "User ID: " . $_SESSION['id'] . "<br>";
                    // echo "Login: " . $_SESSION['login'] . "<br>";
                    // echo "Role: " . $_SESSION['role'] . "<br>";
                    // Вывод остальных полей...
                }
                $successMessage = "Пользователь успешно зарегистрирован!";
                // Очистите поля после успешной регистрации
                $login = '';
                $email = '';

            } else {
                $errorMessage = "Ошибка: " . $conn->error;
            }
        }
    }
} else {
    $login = '';
    $email = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <?php require_once "blocks/head.php"; ?>
    <style>
        form label:after {
            color: grey;
            content: ' *';
        }
    </style>
</head>

<body>
<header>
    <div id="logo">
        <a href="http://art-display/" title="Перейти на главную"><h1>Онлайн-галерея</h1></a>
    </div>
</header>
<div class="clear"><br></div>
<div class="container">
    <h2>Регистрация</h2>
    <br>
    <?php if (!empty($errorMessage)): ?>
        <div class="error">
            <p><?= $errorMessage ?></p>
        </div>
    <?php elseif (!empty($successMessage)): ?>
        <div class="success">
            <p><?= $successMessage ?></p>
        </div>
    <?php endif; ?>
    <form method="post">
        <label for="username">Имя пользователя:</label>
        <input type="text" value="<?= $login ?>" placeholder="Логин" name="login" required>
        <label for="username">Пароль:</label>
        <input type="password" placeholder="Пароль" name="pass" required>
        <label for="username">Повтор ввода:</label>
        <input type="password" placeholder="Повторите пароль" name="repeatpass" required>
        <label for="username">Электронная почта:</label>
        <input type="email" value="<?= $email ?>" placeholder="Email" name="email" required>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <br>
    <div class="switch-form">
        <p>У вас уже есть учетная запись?
            <a href="https://art-display/authorization.php">Войти</a>
        </p>
    </div>
</div>
</body>

<script>
    function handleZoom() {
        const size = [1519, 738];

        // Получаем сохраненное значение масштаба из локального хранилища браузера
        const savedZoom = localStorage.getItem('zoom');

        // Если значение масштаба было сохранено, устанавливаем его, иначе используем начальное значение
        const initialZoom = savedZoom ? parseFloat(savedZoom) : 1;
        document.body.style.zoom = initialZoom;

        // Обновляем значение масштаба при изменении размеров окна
        window.onresize = function () {
            const newZoom = document.documentElement.clientWidth / size[0];
            document.body.style.zoom = newZoom;

            // Сохраняем текущее значение масштаба в локальное хранилище браузера
            localStorage.setItem('zoom', newZoom.toString());
        };
    }

    // Вызываем функцию при загрузке страницы
    window.onload = handleZoom;

    // Вызываем функцию при обновлении страницы
    window.onpageshow = handleZoom;
</script>
<div class="clear"><br></div>
<footer>
    <?php require_once "blocks/footer.php" ?>
</footer>
</html>


