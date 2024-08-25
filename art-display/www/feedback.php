<?php session_start();
require_once('function/email.php');
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
            margin: 20px auto; /* Центрирование по горизонтали */
        }

        .container textarea {
            width: 100%;
            height: 300px;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<header>
    <?php
    $title = "Онлайн-галерея";
    require_once "blocks/header.php";
    ?>
</header>
<div class="clear"><br></div>
<main>
    <div class="container">
        <h2>Обратная связь</h2>
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
            <div class="form-message">
                <div>
                    <label for="username">Имя</label>
                    <input type='text' name='username' required placeholder="Ваше имя"></div>
                <div>
                    <label>Почта для связи</label>
                    <input type='email' name='useremail' required placeholder="Ваша почта"></div>
                <div>
                    <label>Сообщение</label>
                    <br>
                    <textarea name='question' required placeholder="Ваши вопросы"></textarea>
                </div>
                <div style="display: flex; justify-content: center;">
                    <button type="submit" name="send_mes">Отправить сообщение</button>
                </div>
            </div>
        </form>
    </div>
</main>
<div class="clear"><br></div>
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
        window.onresize = function() {
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
<footer>
    <?php require_once "blocks/footer.php"?>
</footer>
</html>

