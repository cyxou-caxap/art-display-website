<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
<?php require_once "../blocks/head.php";?>
<style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        p {
            color: #555;
        }

    </style>
</head>
<body>
<header>
    <?php 
    $title = "Онлайн-галерея";
    require_once "../blocks/header.php";
    ?>
</header>
    <div class="clear"><br></div>
<div class="article">
    <h2>Категории</h2>
</div>
<div id="posts">

        <?php
// Подключение к базе данных
require_once('../function/db.php');

// Запрос к базе данных
$sql = "SELECT * FROM categories ORDER BY name ASC";
$result = $conn->query($sql);

// Обработка результатов запроса

if ($result->num_rows > 0) {
    // Вывод данных
    while ($row = $result->fetch_assoc()) {
?>
    <div class="article">
        <div>
        <a href="category.php?id=<?=$row['id']?>">
            <?php
            echo mb_substr($row['name'], 0, 29, 'UTF-8');
            $title_length = mb_strlen($row['name'], 'UTF-8');
            if ($title_length > 29) {
                echo '...';
            }
            ?>
        </a>
            <p>
                <?php
                echo mb_substr($row['description'], 0, 29, 'UTF-8');
                $title_length = mb_strlen($row['description'], 'UTF-8');
                if ($title_length > 29) {
                    echo '...';
                }
                ?>
                </p>
        </div>
        </div>
<?php
    }
} else {?>
 <div class="article">
            <h2>Нет категорий</h2>
        </div>
    <?php
}

// Закрытие подключения к базе данных
$conn->close();
?>


    <div class="clear"><br></div>
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
    <?php require_once "../blocks/footer.php"?>
</footer>
</html>
