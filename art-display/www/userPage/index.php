<?php
session_start();
// Подключение к базе данных
require_once('Z:/home/art-display/www/function/db.php');
require_once('../function/post.php');

// Получаем информацию о пользователе из базы данных
if (isset($_GET['id'])){
    $user_id = $_GET['id'];
}

if (isset($_GET['us_id'])){
    $user_id = $_GET['us_id'];
}
$sql = "SELECT * FROM registeruser WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$sql = "SELECT * FROM posts WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$postSum = $result->num_rows;
$viewsSum=0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $postId = $row['id'];
        $sql = "SELECT * FROM visits WHERE post_id = '$postId'";
        $resultVis = $conn->query($sql);
        while ($vis = $resultVis->fetch_assoc()) {
            $viewsSum += 1;
        }
    }
}
$sql = "SELECT * FROM followers WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$subSum = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function(){
            $(".buttons button").click(function(){
                <?php
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $user_id = $_GET['id'];
                }
                ?>
                var action = $(this).attr("class");
                $.ajax({
                    url: "process.php",
                    method: "POST",
                    data: { action: action, user_id: <?=$user_id;?> },
                    success: function(response){
                        $(".content").html(response);
                    }
                });
            });
        });
    </script>
    <link rel="stylesheet" href="../css/userPage.css">
    <?php require_once "Z:/home/art-display/www/blocks/head.php";?>

</head>
<body>
<header>
    <?php
    $title = "Онлайн-галерея";
    require_once "Z:/home/art-display/www/blocks/header.php";
    ?>
</header>

<div class="cover-photo">
    <?php
    $show_img = base64_encode($user['cover']);
    if($show_img){
        ?>
        <img class="img_cover" src="data:image/jpeg;base64,<?php echo $show_img ?>" alt="обложка">
        <?php
    } else{
        ?>
        <img class="img_cover" src="../img/обои2.jpg" alt="обложка">
        <?php
    }

    ?>
</div>


<div class="user-info">
    <div class="avatar-photo">
        <?php
        $show_img = base64_encode($user['avatar']);
        if($show_img){
            ?>
            <img  src="data:image/jpeg;base64,<?php echo $show_img ?>" alt="аватар">
            <?php
        } else{
            ?>
            <img  src="../img/avatar.jpg" alt="аватар">
            <?php
        }
        ?>
    </div>
    <h2><?php echo $user['login']; ?></h2>

    <div class="information">
        <h4>Просмотры: <br><?php echo $viewsSum; ?></h4>
        <h4>Посты: <br><?php echo $postSum; ?></h4>
        <h4>Подписчики: <br><?php echo $subSum; ?></h4>
    </div>

    <h4 class="date">ДАТА РЕГИСТРАЦИИ: <?php echo $user['date']; ?></h4>
</div>

<div class="buttons">
    <button onclick="window.location.href='http://art-display/userPage/userPage.php?userId=<?=$user_id;?>'" class="create_post">Вернуться в профиль</button>
<button onclick="window.location.href='http://art-display/userPage/index.php?us_id=<?= $user_id; ?>'"
        class="create_post">Посты
</button>
<button onclick="window.location.href='http://art-display/userPage/indexCat.php?us_id=<?= $user_id; ?>'"
        class="create_post">Категории
</button>
</div>

<div class="content">
    <div class="button-row">
        <a href="create.php?id=<?=$user_id;?>" class="col-2">Добавление</a>
        <a href="index.php?id=<?=$user_id;?>" class="col-2">Управление</a>
    </div>
    <h2>Управление постами</h2>
    <div class="clear"><br></div>
    <div class="row title-table">
        <div class="id col-1">ID</div>
        <div class="title col-2">Название</div>
        <div class="author col-1">Автор</div>
        <div class="red col-4">Управление</div>
    </div>
    <?php
    // Запрос к базе данных
    $sql = "SELECT * FROM posts  where user_id='$user_id'ORDER BY id";
    $result = $conn->query($sql);

    // Обработка результатов запроса

    if ($result->num_rows > 0) {
        // Вывод данных
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="row post">
                <div class="id col-1"><?php echo $row['id']; ?></div>
                <div class="title col-2">
                    <a href="http://art-display/single.php?post=<?php echo $row['id']; ?>">
                        <?php
                        echo mb_substr($row['title'], 0, 20, 'UTF-8');
                        $title_length = mb_strlen($row['title'], 'UTF-8');
                        if ($title_length > 20) {
                            echo '...';
                        }
                        ?>
                    </a>

                </div>

                <?php
                $idUser=$row['user_id'];
                $sql = "SELECT login FROM `registeruser` WHERE id = '$idUser'";
                $resultName = $conn->query($sql);
                $resultName = $resultName->fetch_assoc()
                ?>
                <div class="author col-1"><?php echo $resultName['login']; ?></div>

                <div class="edit col-3"><a href="edit.php?id=<?php echo $row['id']; ?>&us_id=<?=$user_id; ?>" onclick="return confirmEdit()">edit</a></div>
                <div class="del col-3"><a href="edit.php?del_id=<?php echo $row['id']?>&us_id=<?=$user_id; ?>" onclick="return confirmDelete()">delete</a></div>
            </div>
            <?php
        }
    } else {?>
        <div class="row post">
            <h2>Нет постов</h2>
        </div>
        <?php
    }

    // Закрытие подключения к базе данных
    $conn->close();
    ?>
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

<script>
    function confirmDelete() {
        // Показываем диалоговое окно с подтверждением
        var result = confirm("Вы уверены, что хотите удалить пост?");

        // Возвращаем результат диалогового окна
        return result;
    }

    function confirmEdit() {
        // Показываем диалоговое окно с подтверждением
        var result = confirm("Вы уверены, что хотите отредактировать пост?");

        // Возвращаем результат диалогового окна
        return result;
    }
</script>
<div class="clear"><br></div>
<div class="clear"><br></div>
<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php"?>
</footer>
</html>

