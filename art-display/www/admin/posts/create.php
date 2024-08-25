<?php
session_start();
require_once('../../function/post.php');
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
        form label:after {
            color: grey;
            content: ' *';
        }
        .ck {
            height: 600px;
        }
        /* Стили для элементов списка в CKEditor */
        .ck-content ol, .ck-content ul {
            margin-block-start: 0;
            margin-block-end: 0;
            padding-inline-start: 20px;
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
        <h2>Добавление поста</h2>
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
            <form action="create.php" method="post" enctype="multipart/form-data">
                <div class="col">
                    <label class="form-label">Название поста</label>
                    <input name="title" type="text" class="form-control" placeholder="Title"
                           aria-label="Название поста" required>
                </div>
                <label class="form-label">Описание поста</label>
                <div class="editor-container">

                    <textarea name="content" class="form-control" rows="6" id="editor"></textarea>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const script = document.createElement('script');
                            script.src = 'https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js';
                            script.onload = function () {
                                ClassicEditor
                                    .create(document.querySelector('#editor'), {
                                        toolbar: {
                                            items: [
                                                'undo', 'redo',
                                                '|', 'heading',
                                                '|', 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                                                '|', 'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                                                '|', 'link', 'codeBlock',
                                                '|', 'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                                            ],
                                            shouldNotGroupWhenFull: false
                                        }
                                    })
                                    .then(editor => {
                                        console.log('Редактор был инициализирован', editor);
                                    })
                                    .catch(error => {
                                        console.error(error);
                                    });

                            };
                            document.head.appendChild(script);
                        });
                    </script>
                </div>
                <div class="input-group col">
                    <label for="inputGroupFile02">Выберите изображение:</label>
                    <input name="img" type="file" class="form-control" id="inputGroupFile02" required accept=".png, .jpg, .jpeg">
                </div>
                <label for="myDropdown">Выберите категорию:</label><br>
                <select name="category" id="myDropdown" required>
                    <?php
                    // Запрос к базе данных
                    $sql = "SELECT * FROM categories ORDER BY name ASC";
                    $result = $conn->query($sql);

                    // Обработка результатов запроса

                    if ($result->num_rows > 0) {
                    // Вывод данных
                    while ($row = $result->fetch_assoc()) {?>
                     <option><?php echo $row['name']; ?></option>
                        <?php
                    }
                    }
                    ?>

                </select>
                <div class="clear"><br></div>
                <div class="clear"><br></div>
                <button name="add_post" class="btn-add" type="submit">Добавить</button>

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
<footer>
    <?php require_once "Z:/home/art-display/www/blocks/footer.php" ?>
</footer>
</html>
