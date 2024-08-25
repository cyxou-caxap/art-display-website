<?php
session_start();
if (!($_SESSION)) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("location: http://art-display/authorization.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../../img/favicon.ico" type="image/x-icon">
    <?php require_once "Z:/home/art-display/www/blocks/headAdmin.php"; ?>
    <link rel="stylesheet" href="../../css/admin.css">
    <style>
        .col-1,
        .col-2,
        .col-6 {
            word-wrap: break-word; /* Переносит слова при необходимости */
            overflow-wrap: break-word; /
        }

        .col-1 {
            width: 8%; /* Регулирует ширину первой колонки */
        }

        .col-2 {
            width: 20%; /* Регулирует ширину второй колонки */
        }

        .col-6 {
            width: 60%; /* Регулирует ширину шестой колонки */
        }
    </style>
</head>
<body>
<header>
    <?php
    $title = "Онлайн-галерея";
    require_once "Z:/home/art-display/www/blocks/adminHeader.php";
    ?>
</header>
<div class="clear"><br></div>
<?php
// Подключение к базе данных
require_once('Z:/home/art-display/www/function/db.php'); ?>

<div class="container">
    <?php include "../../blocks/sidebarAdmin.php" ?>
    <div class="posts col-10">
        <div class="button-row">
            <a href="create.php" class="col-2">Добавление</a>
            <a href="index.php" class="col-2">Управление</a>
        </div>
        <h2>Управление пользователями</h2>
        <div class="clear"><br></div>
        <div class="row title-table">
            <div class="id col-1">ID</div>
            <div class="title col-2">Логин</div>
            <div class="author col-1">Роль</div>
            <div class="red col-6">Управление</div>
        </div>

        <?php
        // Запрос к базе данных
        $sql = "SELECT * FROM registeruser ORDER BY id";
        $result = $conn->query($sql);

        // Обработка результатов запроса

        if ($result->num_rows > 0) {
        // Вывод данных
        while ($row = $result->fetch_assoc()) {
        ?>
        <div class="row post">
            <div class="id col-1"><?php echo $row['id']; ?></div>
            <div class="login col-2"><?php echo $row['login']; ?></div>
            <?php
            if($row['role']){
                ?>
                <div class="author col-1">User</div>
                <?php
            }
            else{
                ?>
                <div class="author col-1">Admin</div>
                <?php
            }
            ?>

            <div class="edit col-2"><a href="edit.php?id=<?php echo $row['id']; ?>" onclick="return confirmEdit()">edit</a></div>
            <div class="del col-2"><a href="edit.php?del_id=<?php echo $row['id']; ?>" onclick="return confirmDelete()">delete</a></div>
            <?php
            if($row['status']){
                ?>
                <div class="block col-2"><a href="edit.php?block_id=<?php echo $row['id']; ?>">block</a></div>
                <?php
            }
            else{
                ?>
                <div class="unblock col-2"><a href="edit.php?unblock_id=<?php echo $row['id']; ?>">unblock</a></div>
                <?php
            }
            ?>
        </div>
            <?php
        }
        } else {?>
            <div class="row post">
                <h2>Нет пользователей</h2>
            </div>
            <?php
        }

        // Закрытие подключения к базе данных
        $conn->close();
        ?>
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

<script>
    function confirmDelete() {
        // Показываем диалоговое окно с подтверждением
        var result = confirm("Вы уверены, что хотите удалить пользователя?");

        // Возвращаем результат диалогового окна
        return result;
    }

    function confirmEdit() {
        // Показываем диалоговое окно с подтверждением
        var result = confirm("Вы уверены, что хотите отредактировать пользователя?");

        // Возвращаем результат диалогового окна
        return result;
    }
</script>
<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php" ?>
</footer>
</html>
