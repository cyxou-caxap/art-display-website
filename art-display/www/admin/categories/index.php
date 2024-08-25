<?php
session_start();
require_once('../../function/db.php');
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
        .col-4,
        .col-5 {
            word-wrap: break-word;
            overflow-wrap: break-word;
        /
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
        <h2>Управление категориями</h2>
        <div class="clear"><br></div>
        <div class="row title-table">
            <div class="id col-1">ID</div>
            <div class="title col-5">Название</div>
            <div class="title col-5">Описание</div>
            <div class="red col-4">Управление</div>
        </div>

        <?php
        // Запрос к базе данных
        $sql = "SELECT * FROM categories ORDER BY id";
        $result = $conn->query($sql);

        // Обработка результатов запроса

        if ($result->num_rows > 0) {
            // Вывод данных
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="row post">
                    <div class="id col-1"><?php echo $row['id']; ?></div>
                    <div class="title col-5">
                        <a href="../../category/category.php?id=<?= $row['id'] ?>">
                            <?php
                            echo mb_substr($row['name'], 0, 20, 'UTF-8');
                            $title_length = mb_strlen($row['name'], 'UTF-8');
                            if ($title_length > 20) {
                                echo '...';
                            }
                            ?>
                        </a>
                    </div>
                    <div class="title col-5">
                        <?php
                        echo mb_substr($row['description'], 0, 20, 'UTF-8');
                        $title_length = mb_strlen($row['description'], 'UTF-8');
                        if ($title_length > 20) {
                            echo '...';
                        }
                        ?>
                    </div>
                    <?php
                    if (!($row['id'] == 18)) {
                        ?>
                        <div class="edit col-2"><a href="edit.php?id=<?php echo $row['id']; ?>"
                                                   onclick="return confirmEdit()">edit</a>
                        </div>
                        <div class="del col-2"><a href="edit.php?del_id=<?php echo $row['id']; ?>"
                                                  onclick="return confirmDelete()">delete</a>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php
            }
        } else { ?>
            <div class="row post">
                <h2>Нет категорий</h2>
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
        window.onresize = function () {
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
        var result = confirm("Вы уверены, что хотите удалить категорию?");

        // Возвращаем результат диалогового окна
        return result;
    }

    function confirmEdit() {
        // Показываем диалоговое окно с подтверждением
        var result = confirm("Вы уверены, что хотите отредактировать категорию?");

        // Возвращаем результат диалогового окна
        return result;
    }
</script>

<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php" ?>
</footer>
</html>
