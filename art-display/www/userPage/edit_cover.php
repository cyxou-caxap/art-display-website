<?php
session_start();
// Подключение к базе данных
require_once('../function/cover.php');
require_once('Z:/home/art-display/www/function/db.php');
//var_dump($_SESSION);

$flag=false;
$fl=false;
// Проверяем, авторизован ли пользователь
if (!($_SESSION)) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("location: http://art-display/authorization.php");
    exit();
}

if (isset($_GET['us_id'])){
    $user_id = $_GET['us_id'];
}
// Получаем информацию о пользователе из базы данных
$sql = "SELECT * FROM registeruser WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/userPage.css">
</head>
<body>
<div class="choice">
    <form  method="post" enctype="multipart/form-data">
    <h2>Выберите файл для предварительного просмотра</h2>
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
    <input name="img" type="file" class="form-cover" id="inputGroupFile02" accept=".png, .jpg, .jpeg" onchange="previewImage(this)">
    <?php
    $imgPreview=$user['cover'];
    //var_dump($post['img']);
    $show_img_avatar = !empty($user['avatar']) ? base64_encode($user['avatar']) : '../img/avatar.jpg';
    $show_img = !empty($user['cover']) ? base64_encode($user['cover']) : '../img/обои2.jpg';
    if ($show_img=='../img/обои2.jpg'){
        $fl=1;
    }
    ?>
    <input value = "<?php $imgPreview?>" name="imgPreview" type="hidden">
</div>

<div class="cover-photo" style="height: 185.4px">
    <!-- Перемещаем элемент imgPreview сюда -->
    <?php
    if ($fl){
        ?>
        <img class="img_cover" id="imgPreview" src="<?php echo $show_img; ?>" alt="" >
    <?php
    } else{
        ?>
        <img class="img_cover" id="imgPreview" src="data:image/jpeg;base64, <?php echo $show_img ?>" alt="" >
        <?php
    }
    ?>
</div>

            <script>
                // Функция для предварительного просмотра изображения при выборе файла
                function previewImage(input) {
                    var preview = document.getElementById('imgPreview');
                    var file = input.files[0].size;
                    var reader = new FileReader();

                    reader.onloadend = function () {
                        preview.src = reader.result;
                    };

                    if (file) {
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        preview.src = "";

                    }
                }

                // Привязываем функцию к событию изменения значения в input
                document.getElementById('inputGroupFile02').addEventListener('change', function() {
                    previewImage(this);
                });
            </script>
<?php
if (!$flag){
    ?>
    <button name="edit-cover" class="edit-cover" style="top: 270px" type="submit">Сохранить обложку</button>
    <?php
};
?>
</form>
<div class="user-info" style="height: 370.812px">

        <!-- Перемещаем элемент imgPreview сюда -->
        <?php
        if ($fl){
            ?>
            <img  id="imgPreview_avatar" src="<?php echo $show_img_avatar; ?>" alt="" >
            <?php
        } else{
            ?>
            <img id="imgPreview_avatar" src="data:image/jpeg;base64, <?php echo $show_img_avatar ?>" alt="" >
            <?php
        }
        ?>
    <h2><?php echo $user['login']; ?></h2>

    <div class="information">
        <h4>Просмотры: <br></h4>
        <h4>Посты: <br></h4>
        <h4>Подписчики: <br></h4>
    </div>
    <?php
    if (!$flag){
        ?>
        <button class="edit-profile" type="submit">Редактировать профиль</button>
        <?php
    }
    else{
        $subscriber=$_SESSION['id'];
        $sql = "SELECT * FROM followers WHERE subscriber='$subscriber' AND user_id='$user_id'";
        $resultSub=$conn->query($sql);
        if ($resultSub->num_rows>0) {
            ?>
            <button class="unsubscription">Отписаться</button>
            <?php
        } else {
            ?>
            <button class="subscription">Подписаться</button>
            <?php
        }
    };
    ?>
    <h4 class="date">ДАТА РЕГИСТРАЦИИ: <?php echo $user['date']; ?></h4>
</div>

<div class="buttons" style="top:330px">
    <button class="portfolio">Портфолио</button>
    <?php
    if (!$flag){
        ?>
        <button class="create_post">Управление постами</button>
        <?php
    };
    ?>
    <button class="subscriptions">Подписки</button>
    <button class="followers">Подписчики</button>
    <?php
    if (!$flag){
        ?>
        <button class="statistic">Ваша статистика</button>
        <?php
    };
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
<div class="clear"><br></div>
<div class="clear"><br></div>
<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php"?>
</footer>
</html>

