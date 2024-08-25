<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <?php require_once "blocks/head.php"; ?>
    <style>
        #posts {
            word-wrap: break-word; /* Переносит слова при необходимости */
            overflow-wrap: break-word;
        }
        h3{
            margin-left: 20px;
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
<h2 style="margin-left:10px; ">Результаты поиска по запросу: "<?=htmlspecialchars(trim($_POST['search']))?>"</h2>
<div id="posts">
    <?php
    // Подключение к базе данных
    require_once('function/db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
        $text = trim($_POST['search']);
        if (!empty($text)){
            $sql = "SELECT * FROM posts AS p WHERE p.status = 1 
                        AND (p.title LIKE '%$text%' OR p.content LIKE '%$text%')";
            $resultPost = $conn->query($sql);
        }
        else{
            ?>
            <div class="clear"><br></div>
            <h3>строка поиска пуста</h3>
            <?php
            return;
        }

        // Обработка результатов запроса
        ?>
        <div class="clear"><br></div>
        <h2>Посты:</h2>
        <?php
        if ($resultPost->num_rows > 0) {
            // Вывод данных
            while ($row = $resultPost->fetch_assoc()) {
                if ($row['status'] != 0) {
                    $show_img = base64_encode($row['img']);
                    ?>
                    <div class="article">
                        <div class="like-icon"></div>
                        <div>
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
                                $idUser = $row['user_id'];
                                $sql = "SELECT login FROM `registeruser` WHERE id = '$idUser'";
                                $resultName = $conn->query($sql);
                                $resultName = $resultName->fetch_assoc()
                                ?>
                                <?php echo $resultName['login']; ?> </p>
                        </div>
                    </div>
                    <?php
                }
            }
        } else { ?>
            <div class="clear"><br></div>
            <h3>нет результатов</h3>
            <?php
        }


        $sql = "SELECT * FROM categories WHERE (name LIKE '%$text%' OR description LIKE '%$text%')";
        $resultCategories = $conn->query($sql);

        ?>
        <div class="clear"><br></div>
        <h2>Категории:</h2>
        <?php
        if ($resultCategories->num_rows > 0) {
            // Вывод данных
            while ($row = $resultCategories->fetch_assoc()) {
                ?>
                <div class="article">
                    <div class="title col-5">
                        <a href="category/category.php?id=<?= $row['id'] ?>">
                            <?php
                            echo mb_substr($row['name'], 0, 29, 'UTF-8');
                            $title_length = mb_strlen($row['name'], 'UTF-8');
                            if ($title_length > 29) {
                                echo '...';
                            }
                            ?>
                        </a>
                    </div>
                    <div class="title col-5">
                        <?php
                        echo mb_substr($row['description'], 0, 29, 'UTF-8');
                        $title_length = mb_strlen($row['description'], 'UTF-8');
                        if ($title_length > 29) {
                            echo '...';
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        } else { ?>
            <div class="clear"><br></div>
            <h3>нет результатов</h3>
            <?php
        }

        $sql = "SELECT * FROM registeruser WHERE (login LIKE '%$text%')";
        $resultUsers = $conn->query($sql);

        ?>
        <div class="clear"><br></div>
        <h2>Пользователи:</h2>
        <?php
        if ($resultUsers->num_rows > 0) {
            // Вывод данных
            while ($row = $resultUsers->fetch_assoc()) {
                ?>
                <div class="article">
                    <div>
                        <a href="http://art-display/userPage/userPage.php?userId=<?php echo $row['id']; ?>"><?php
                            echo $row['login']; ?> </a><br>
                    </div>
                </div>
                <?php
            }
        } else { ?>
            <div class="clear"><br></div>
            <h3>нет результатов</h3>
            <?php
        }
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
