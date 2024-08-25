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
<div class="article">
    <h2>Избранное</h2>
</div>
<?php
if (!$_SESSION){
    ?>
<div class="article">
    <br>
    <h2>Данная страница доступна только для авторизованных пользователей</h2>
</div>
    <?php
}else{
?>
<div id="posts">
    <?php
    // Подключение к базе данных
    require_once('function/db.php');
    $userId = $_SESSION['id'];
    $sql = "SELECT post_id FROM visits WHERE user_id = '$userId' and `like`=1";
    $resultPostId = $conn->query($sql);
    if ($resultPostId->num_rows > 0) {
        while ($rowId = $resultPostId->fetch_assoc()) {
        $postId = $rowId['post_id'];
        $sql = "SELECT * FROM posts WHERE id = '$postId'";
        $resultPost = $conn->query($sql);
        while ($rowPost = $resultPost->fetch_assoc()) {
            if ($rowPost['status'] != 0) {
                $show_img = base64_encode($rowPost['img']);
                ?>
                <div class="article">
                    <div class="like-icon"></div>
                    <div>
                        <img src="data:image/jpeg;base64,<?php echo $show_img ?>" alt="">
                        <a href="http://art-display/single.php?post=<?php echo $rowPost['id']; ?>">
                            <?php
                            echo mb_substr($rowPost['title'], 0, 29, 'UTF-8');
                            $title_length = mb_strlen($rowPost['title'], 'UTF-8');
                            if ($title_length > 29) {
                                echo '...';
                            }
                            ?>
                        </a>
                        <p><?php
                            $idUser = $rowPost['user_id'];
                            $sql = "SELECT * FROM `registeruser` WHERE id = '$idUser'";
                            $resultName = $conn->query($sql);
                            $resultName = $resultName->fetch_assoc()
                            ?>
                            <a href="http://art-display/userPage/userPage.php?userId=<?= $resultName['id']; ?>"><?php echo $resultName['login']; ?></a> </p>

                    </div>
                </div>
                <?php
            }
        }
    }} else {
        ?> <h2>Нет понравившихся записей</h2> <?php
    }
    // Закрытие подключения к базе данных
    $conn->close();
    }
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
