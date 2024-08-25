<?php
session_start();
require_once('../function/db.php');

// Проверяем, передан ли параметр "id"
$categoryId = isset($_GET['id']) ? $_GET['id'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="../css/style.css">
    <?php require_once "../blocks/head.php";?>
    <style>
        #posts
        {
            word-wrap: break-word; /* Переносит слова при необходимости */
            overflow-wrap: break-word;
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
    <div id="posts">
    <?php
    // Подключение к базе данных
    require_once('../function/db.php');

    // Запрос к базе данных
    $categorySql = "SELECT * FROM categories WHERE id = '$categoryId'";
    $categoryResult = $conn->query($categorySql);

    // Обработка результатов запроса
    if ($categoryResult->num_rows > 0) {
        // Вывод данных о категории
        $category = $categoryResult->fetch_assoc();
        ?>
        <div></div>
        <div>
            <h2><?php echo $category['name']; ?></h2>
            <p class="desc" style="width: 98%;"><?php echo $category['description']; ?></p>
            <div class="clear"><br></div>
            <div id="posts">

                <?php
                // Запрос к базе данных для получения постов в выбранной категории
                $postsSql = "SELECT * FROM posts WHERE category_id = '$categoryId'";
                $postsResult = $conn->query($postsSql);

                // Обработка результатов запроса
                if ($postsResult->num_rows > 0 ) {
                    // Вывод данных о постах
                    while ($post = $postsResult->fetch_assoc()) {
                if($post['status']!=0){
                        $show_img = base64_encode($post['img']);
                        ?>
                        <div class="article">
                            <img src="data:image/jpeg;base64, <?php echo $show_img ?>" alt="">
                            <a href="http://art-display/single.php?post=<?php echo $post['id']; ?>">
                                <?php
                                echo mb_substr($post['title'], 0, 29, 'UTF-8');
                                $title_length = mb_strlen($post['title'], 'UTF-8');
                                if ($title_length > 29) {
                                    echo '...';
                                }
                                ?>
                            </a>
                            <p><?php
                            $idUser=$post['user_id'];
                            $sql = "SELECT * FROM `registeruser` WHERE id = '$idUser'";
                            $resultName = $conn->query($sql);
                            $resultName = $resultName->fetch_assoc()

                            ?>
                            <a href="http://art-display/userPage/userPage.php?userId=<?= $resultName['id']; ?>"><?php echo $resultName['login']; ?></a> </p>
                        </div>
                        <?php
                    }
                    }
                } else {
                    echo "<p>Нет постов в этой категории.</p>";
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="article">
            <h2>Категория не найдена</h2>
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
