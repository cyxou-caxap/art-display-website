<?php session_start(); ?>

<!DOCTYPE html>
<html lang="ru">

    <head>
        <link rel="icon" href="img/favicon.ico" type="image/x-icon">
        <?php require_once "blocks/head.php"; ?>
        <style>
            #posts
            {
                word-wrap: break-word; /* Переносит слова при необходимости */
                overflow-wrap: break-word;
            }
        </style>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    </head>

<body>
<header>
    <?php
    $title = "Онлайн-галерея";
    require_once "blocks/header.php";
    ?>
</header>
<div class="clear"><br></div>
<?php
if (isset($_GET['best_posts'])) {
// Если нажата ссылка "Лучшее за неделю"
?>
    <div class="article">
        <h2>Топ-10 публикаций</h2>
    </div>
<?php
} else {
?>
    <div class="article">
        <h2>Недавние опубликованные работы</h2>
    </div>
<?php
}
?>
<div id="posts">
    <?php
    // Подключение к базе данных
    require_once('function/db.php');

    if (isset($_GET['best_posts'])) {
        // Если нажата ссылка "Лучшее за неделю"
        $sql = "SELECT * FROM posts WHERE status = 1 ORDER BY visits DESC LIMIT 10";
    } else {
        // Если не нажата, вывод обычных постов
        $sql = "SELECT * FROM posts WHERE status = 1 ORDER BY id DESC";
    }

    // Запрос к базе данных
    $result = $conn->query($sql);

    // Обработка результатов запроса

    if ($result->num_rows > 0) {
        // Вывод данных
        while ($row = $result->fetch_assoc()) {
            if ($row['status'] != 0) {
                $show_img = base64_encode($row['img']);
                ?>
                <div class="article">
                    <div class="like-icon"></div>
                        <img src="data:image/jpeg;base64,<?php echo $show_img ?>" alt="">
                        <a href="http://art-display/single.php?post=<?php echo $row['id']; ?>">
                            <?php
                            echo mb_substr($row['title'], 0, 29, 'UTF-8');
                            $title_length = mb_strlen($row['title'], 'UTF-8');
                            if ($title_length > 29) {
                                echo '...';
                            }
                            ?>
                        </a>

                        <p><?php
                        $idUser=$row['user_id'];
                            $sql = "SELECT * FROM `registeruser` WHERE id = '$idUser'";
                            $resultName = $conn->query($sql);
                            $resultName = $resultName->fetch_assoc()

                            ?>
                            <a href="http://art-display/userPage/userPage.php?userId=<?= $resultName['id']; ?>"><?php echo $resultName['login']; ?></a> </p>
                </div>
                <?php
            }
        }
    } else { ?>
        <div class="article">
            <h2>Нет опубликованных работ</h2>
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
    <?php require_once "blocks/footer.php" ?>
</footer>
</html>
