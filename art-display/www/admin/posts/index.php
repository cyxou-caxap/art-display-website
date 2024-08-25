<?php
session_start();
if (!($_SESSION)) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("location: http://art-display/authorization.php");
    exit();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../../img/favicon.ico" type="image/x-icon">
<?php require_once "Z:/home/art-display/www/blocks/headAdmin.php";?>
<link rel="stylesheet" href="../../css/admin.css">
<style>
    .col-1,
    .col-2,
    .col-6 {
        word-wrap: break-word;
        overflow-wrap: break-word; /
    }

    .col-1 {
        width: 8%;
    }

    .col-2 {
        width: 20%;
    }

    .col-6 {
        width: 60%;
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
require_once('Z:/home/art-display/www/function/db.php');?>

<div class="container">
<?php include "../../blocks/sidebarAdmin.php"?>
        <div class="posts col-10">
            <div class="button-row">
                <a href="create.php" class="col-2">Добавление</a>
                <a href="index.php" class="col-2">Управление</a>
            </div>
            <h2>Управление постами</h2>
            <div class="clear"><br></div>
            <div class="row title-table">
                <div class="id col-1">ID</div>
                <div class="title col-2">Название</div>
                <div class="author col-2">Автор</div>
                <div class="red col-6">Управление</div>
            </div>
            <?php
            // Запрос к базе данных
            $sql = "SELECT * FROM posts ORDER BY id";
            $result = $conn->query($sql);

            // Обработка результатов запроса

            if ($result->num_rows > 0) {
            // Вывод данных
            while ($row = $result->fetch_assoc()) {
            ?>
            <div class="row post">
                <div class="id col-1"><?php echo $row['id']; ?></div>
                <div class="title col-2">
                        <?php
                        echo mb_substr($row['title'], 0, 20, 'UTF-8');
                        $title_length = mb_strlen($row['title'], 'UTF-8');
                        if ($title_length > 20) {
                            echo '...';
                        }
                        ?>
                </div>

                <?php
                $idUser=$row['user_id'];
                $sql = "SELECT login FROM `registeruser` WHERE id = '$idUser'";
                $resultName = $conn->query($sql);
                $resultName = $resultName->fetch_assoc()
                ?>


                <div class="author col-2">
                    <?php
                    echo mb_substr($resultName['login'], 0, 20, 'UTF-8');
                    $title_length = mb_strlen($resultName['login'], 'UTF-8');
                    if ($title_length > 20) {
                        echo '...';
                    }
                    ?></div>

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
                    <h2>Нет постов</h2>
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
<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php"?>
</footer>
</html>
