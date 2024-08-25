<?php
session_start();
require_once('function/db.php');

$errorMessage = '';
$successMessage = '';

// Проверяем, был ли пользователь заблокирован
if (isset($_SESSION['block_time']) && $_SESSION['block_time'] > time()) {
    $remaining_time = ceil(($_SESSION['block_time'] - time()) / 60); // Оставшееся время в минутах
    $errorMessage = "Попробуйте войти через {$remaining_time} мин.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars($_POST['pass']);
    $hashedPass = md5($pass);

    // Получаем текущее количество неверных попыток из сессии
    $attempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;

    $sql = "SELECT * FROM `registeruser` WHERE email = '$email'";
    $resultLog = $conn->query($sql);

    $sql = "SELECT * FROM `registeruser` WHERE email = '$email' AND pass = '$hashedPass'";
    $resultPass = $conn->query($sql);

    if ($resultPass->num_rows > 0) {
        // Сбрасываем счетчик неверных попыток при успешном входе
        unset($_SESSION['login_attempts']);
        while ($row = $resultPass->fetch_assoc()) {
            if ($resultPass) {
                $userId = $row['id'];
                $sql = "SELECT * FROM `registeruser` WHERE id = '$userId'";
                $result = $conn->query($sql);

                if ($result) {
                    //var_dump($result);
// Проверяем, есть ли данные
                    if ($result->num_rows > 0) {
                        // Получаем данные пользователя
                        $userData = $result->fetch_assoc();
                        if (!($userData ['status'] === '0')) {
                            $_SESSION['id'] = $userData ['id'];
                            $_SESSION['login'] = $userData ['login'];
                            $_SESSION['role'] = $userData ['role'];
                            if ($_SESSION['role'] === '0') {
                                header("location: http://art-display/admin/posts/index.php");
                            } else {
                                header("location: http://art-display/index.php");
                            }
                        } else {
                            $errorMessage = "Пользователь заблокирован!";
                        }
                        // echo "User ID: " . $_SESSION['id'] . "<br>";
                        // echo "Login: " . $_SESSION['login'] . "<br>";
                        // echo "Role: " . $_SESSION['role'] . "<br>";
                        // Вывод остальных полей...
                    }
                }
            }
        }
    } elseif ($resultLog->num_rows > 0) {
        // Увеличиваем счетчик неверных попыток
        $_SESSION['login_attempts'] = ++$attempts;

        if ($attempts >= 5) {
            // Устанавливаем время блокировки на 5 минут
            $_SESSION['block_time'] = time() + 300; // 300 секунд = 5 минут
            $errorMessage = "Попробуйте войти через 5 минут";
        }
        else{
            $errorMessage = "Неверный пароль!";
        }
    } else {
        $errorMessage = "Такого пользователя не существует";
    }
} else {
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
        .container {
            margin: 76px auto; /* Центрирование по горизонтали */
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
    <h2>Вход</h2>
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
        <label for="username">Email пользователя:</label>
        <input value="<?= $email ?>" type="text" placeholder="Email" name="email" required>
        <label for="username">Пароль:</label>
        <input type="password" placeholder="Пароль" name="pass" required>
        <button type="submit">Войти</button>
        <br>
        <div class="switch-form">
            <p>Новый пользователь?
                <a href="https://art-display/registration.php">Создать учетную запись</a>
            </p>
        </div>
    </form>
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