<?php
session_start();
// Подключение к базе данных
require_once('Z:/home/art-display/www/function/db.php');
require_once('../function/categories.php');

// Получаем информацию о пользователе из базы данных
//var_dump($_GET);
//exit();

if (isset($_GET['id'])){
    $user_id = $_GET['id'];
}

if (isset($id)){
    $user_id = $id;
}
$sql = "SELECT * FROM registeruser WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$sql = "SELECT * FROM posts WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$postSum = $result->num_rows;
$viewsSum = 0;
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
$flag=false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        form label:after {
            color: grey;
            content: ' *';
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
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
    <?php require_once "Z:/home/art-display/www/blocks/head.php"; ?>
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
        <a href="createCat.php?id=<?= $user_id; ?>" class="col-2">Добавление</a>
        <a href="indexCat.php?id=<?= $user_id; ?>" class="col-2">Просмотр</a>
    </div>
    <div class="created">
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
                <input name="id" type="hidden" value="<?=$user_id;?>">
                <div class="col">
                    <label for="content" class="form-label">Название категории</label>
                    <input name="name" type="text" class="form-control" placeholder="Название категории" aria-label="Название категории" required>
                </div>

                <div class="col">
                    <label for="content" class="form-label">Описание категории</label>
                    <textarea name="description" class="form-control" placeholder="Описание категории" aria-label="Описание категории" required></textarea>
                </div>
<br>
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
<div class="clear"><br></div>
<div class="clear"><br></div>
<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php" ?>
</footer>
</html>

