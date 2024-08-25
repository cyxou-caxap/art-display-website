<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <?php require_once "blocks/head.php"; ?>
    <style>
        .like-icon {
            background: url(img/like1.png) no-repeat; /* Замените путь на ваше изображение лайка */
        }

        .liked {
            /* Добавьте стили для иконки, когда она "лайкнута" */
            background: url(img/like2.png) no-repeat;
        }

        .like-icon,
        .liked{
            position: absolute;
            border: white;
            right: 15px;
            width: 50px;
            height: 50px;
            background-size: cover;
        }

        .post {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            max-width: 600px;
            word-wrap: break-word; /* Переносит слова при необходимости */
            overflow-wrap: break-word;
            position: relative; /* Добавлено для позиционирования иконки */
            text-align: justify; /* Выравнивание текста по ширине */
            line-height: 1.5; /* Интервал между строками */
        }


        .content p {
            text-indent: 1.5em;
        }

        .post h2 {
            text-align: center;
            margin-top: 0;
        }

        .post .image img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .author-info {
            display: flex;
            align-items: center;
            margin-left: 20px;
            text-indent: 0;
            font-size: small;
        }

        .author-info p {
            margin-right: 15px;
            text-indent: 0;
        }

        .author-info img {
            width: 25px;
            height: 25px;
            margin-right: 5px;
            margin-top: 3px;
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
<div class="post">
    <?php
    // Подключение к базе данных
    require_once('function/db.php');

    // Получение ID поста из URL
    if (isset($_GET['post']) && !empty($_GET['post'])) {
        $post_id = $_GET['post'];

        // Запрос к базе данных
        $sql = "SELECT * FROM posts WHERE id = '$post_id' AND status = 1";
        $result = $conn->query($sql);

        // Обработка результатов запроса
        if ($result->num_rows > 0) {
            // Вывод данных
            $row = $result->fetch_assoc();
            if ($_SESSION) {
                $userId = $_SESSION['id'];
                // Увеличение счетчика посещений
                $sql = "SELECT * FROM visits WHERE post_id = '$post_id' AND user_id = '$userId'";
                $result = $conn->query($sql);
                if (!($result->num_rows > 0)) {
                    $sql = "INSERT INTO `visits` (post_id,user_id) VALUES ('$post_id','$userId')";
                    $result = $conn->query($sql);
                }
            }

            $show_img = base64_encode($row['img']);
            ?>
            <div class="image">
                <img src="data:image/jpeg;base64,<?php echo $show_img ?>" alt="">
            </div>
            <h2><?php echo $row['title']; ?></h2>
            <div class="author-info">
                <img src="img/author-icon.png" alt="Author Icon">
                <?php
                    $idUser = $row['user_id'];
                    $sqlAuthor = "SELECT * FROM `registeruser` WHERE id = '$idUser'";
                    $resultAuthor = $conn->query($sqlAuthor);
                    $resultAuthor = $resultAuthor->fetch_assoc();
                    ?>
                    <a href="http://art-display/userPage/userPage.php?userId=<?= $resultAuthor['id']; ?>"><?php echo $resultAuthor['login']; ?></a>
          </p>
                <img src="img/date-icon.png" alt="Date Icon">
                <p><?php echo $row['created_date']; ?></p>
            </div>
            <br>
            <div class="content">
            <p><?php echo nl2br($row['content']); ?></p>
            </div>
            <div class="clear"><br></div>

            <?php if ($_SESSION) :
                $user_id = $_SESSION['id'];

                // Запрос к базе данных для получения значения like
                $sqlLike = "SELECT `like` FROM visits WHERE post_id = '$post_id' AND user_id = '$user_id'";
                $resultLike = $conn->query($sqlLike);
                $likeValue = 0; // По умолчанию, если запись не найдена

                if ($resultLike->num_rows > 0) {
                    $likeData = $resultLike->fetch_assoc();
                    $likeValue = $likeData['like'];
                }
                ?>

                <form id="likeForm" action="addToFavorites.php" method="POST">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                    <!-- Добавлено условие для определения класса иконки в зависимости от значения like -->
                    <?php if ($likeValue == 1) {?>
                    <button name="addLike" type="submit" class="liked" title="Убрать из избранного"></button>
                <?php
            }else{?>
                        <button name="addLike" type="submit" class="like-icon" title="Добавить в избранное"></button>
                  <?php  }
                ?>
                </form>
            <?php endif; ?>
            <p><strong>Категория:</strong> <?php
                $categoryId = $row['category_id'];
                $sql = "SELECT * FROM categories where id='$categoryId'";
                $result = $conn->query($sql);
                $categoryName = $result->fetch_assoc();
                ?>
<a href="http://art-display/category/category.php?id=<?= $categoryName['id']; ?>"><?php echo $categoryName['name']; ?></a> </p>

            <div class="clear"><br></div>
            <p><strong>Просмотры:</strong> <?php
                $sql = "SELECT * FROM visits
                WHERE post_id = '$post_id'";
                $result = $conn->query($sql);
                $visitsNum = $result->num_rows;
                $sql = "UPDATE `posts` SET visits='$visitsNum' WHERE id='$post_id'";
                $result = $conn->query($sql);
                echo $visitsNum;
                ?>
            </p>
            <div class="clear"><br></div>

            <?php
            require_once "blocks/comments.php";

        } else {
            echo "<p>Нет данных о посте..</p>";
        }
    } else {
        echo "<p>Неверный ID поста.</p>";
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
