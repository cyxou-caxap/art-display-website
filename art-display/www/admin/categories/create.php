<?php
session_start();
require_once('../../function/categories.php');
if (!($_SESSION)) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("location: http://art-display/authorization.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../../img/favicon.ico" type="image/x-icon">
<style>
    form label:after {
        color: grey;
        content: ' *';
    }
</style>
<?php require_once "Z:/home/art-display/www/blocks/headAdmin.php";?>
<link rel="stylesheet" href="../../css/admin.css">

</head>
<body>
<header>
    <?php 
    $title = "Онлайн-галерея";
    require_once "Z:/home/art-display/www/blocks/adminHeader.php";
    ?>
</header>
    <div class="clear"><br></div>
        <?php
// Подключение к базе данных
require_once('Z:/home/art-display/www/function/db.php');?>

<div class="container">
<?php include "../../blocks/sidebarAdmin.php"?>
        <div class="posts col-10">
            <div class="button-row">
                <a href="create.php" class="col-2">Добавление</a>
                <a href="index.php" class="col-2">Управление</a>
            </div>
            <h2>Добавление категории</h2>
            <div class="clear"><br></div>
            
            <?php if (!empty($errorMessage)): ?>
        <div class="error">
            <p><?= $errorMessage ?></p>
        </div>
        <?php elseif (!empty($successMessage)): ?>
        <div class="success">
            <p><?= $successMessage ?></p>
        </div>
        <?php endif; ?>

            <div class="row add-post">
                <form method="post" enctype="multipart/form-data">
                    <div class="col">
                    <label for="content" class="form-label">Название категории</label>
                        <input name="name" type="text" class="form-control" placeholder="Название категории" aria-label="Название категории" required>
                    </div>

                    <div class="col">
                    <label for="content" class="form-label">Описание категории</label>
                        <textarea name="description" class="form-control" placeholder="Описание категории" aria-label="Описание категории" required><?=$description;?></textarea>
                    </div>

                    <button name="categories-create" class="button-row" type="submit">Добавить</button>
                </form>   
            </div>
        </div>
    </div>
</div>
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
    <?php require_once "Z:/home/art-display/www/blocks/footer.php"?>
</footer>
</html>
