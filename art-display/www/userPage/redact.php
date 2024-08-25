<?php
session_start();
// Подключение к базе данных
require_once('../function/users.php');
require_once('Z:/home/art-display/www/function/db.php');

$flag=false;
// Проверяем, авторизован ли пользователь
if (!($_SESSION)) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("location: http://art-display/authorization.php");
    exit();
}

if (isset($_GET['userId']) && !($_GET['userId']==$_SESSION['id'])){
    $user_id = $_GET['userId'];
    $flag=true;
} else{
    $user_id = $_SESSION['id'];
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
    <?php require_once "Z:/home/art-display/www/blocks/head.php";?>
</head>
<body>
<header>
    <?php
    $title = "Онлайн-галерея";
    require_once "Z:/home/art-display/www/blocks/header.php";
    ?>
</header>

<div class="container">
    <div class="posts col-10" style="margin: auto">
        <h2>Обновление пользователя</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="error">
                <p><?= $errorMessage ?></p>
            </div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="success">
                <p><?= $successMessage ?></p>
            </div>
        <?php endif;
        ?>

        <div class="row add-post">
            <form method="post" enctype="multipart/form-data">
                <input value = "<?=$user['id']?>" name="id" type="hidden">
                <input value = "<?=$user['id']?>" name="us_id" type="hidden">
                <div class="col">
                    <label for="content" class="form-label">Имя пользователя:  *</label>
                    <input type="text" class="form-control" value="<?=$user['login']?>" placeholder="Логин" name="login" required>
                </div>
                <div class="col">
                    <label for="content" class="form-label">Новый пароль:</label>
                    <input type="password" class="form-control" placeholder="Пароль" name="pass">
                </div>
                <div class="col">
                    <label for="content" class="form-label">Электронная почта: *</label>
                    <input type="email" class="form-control" value="<?=$user['email']?>" placeholder="Email" name="email" required>
                </div>

                <div class="clear"><br></div>
                <button name="user-edit" class="button-row" type="submit" class="btn-add">Обновить</button>
            </form>
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
    <?php require_once "Z:/home/art-display/www/blocks/footer.php"?>
</footer>
</html>

