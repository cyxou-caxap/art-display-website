<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
<?php require_once "blocks/head.php";?>
<style>
    .container {
        margin: 86px auto; /* Центрирование по горизонтали */
        
    }
    textarea {
 resize: none;
 width: 400px; /* Ширина */
    height: 200px; /* Высота */
    margin: 8px 0;
}
    </style>  
</head>

<body>
<header>
<div id="logo">
    <a href="/"title="Перейти на главную"><h1>Онлайн-галерея</h1></a>
    </div>
</header>
<div class="clear"><br></div>
<div class="container">
    <h2>Загрузка поста</h2>
    <form action="function/posts.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Название работы:</label>
            <input type="text" placeholder="работа" name="title" required>
        </div>
        <div class="form-group">
            <label for="discription">Описание работы:</label>
            <textarea name="discription" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Изображение:</label>
            <input type="file" name="image" accept=".png, .jpg, .jpeg">
        </div>
        <button type="submit">Опубликовать</button>
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
<div class="clear"><br></div>
<footer>
    <?php require_once "blocks/footer.php"?>
</footer>
</html>