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
    <?php require_once "Z:/home/art-display/www/blocks/headAdmin.php";?>
    <link rel="stylesheet" href="../../css/admin.css">
    <style>
        form label:after {
            color: grey;
            content: ' *';
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
        <h2>Обновление поста</h2>
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
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <input value = "<?=$id?>" name="id" type="hidden">
                <div class="col">
                    <label class="form-label">Название поста</label>
                      <input value = "<?=$name?>" name="title" type="text" class="form-control" placeholder="Title"
                       aria-label="Название поста" required>
                </div>
        <label class="form-label">Описание поста</label>
        <div class="editor-container">

            <textarea name="content" class="form-control" rows="6" id="editor"><?=$content?></textarea>

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
            <input name="img" type="file" class="form-control" id="inputGroupFile02" accept=".png, .jpg, .jpeg" onchange="validateFile()">

            <?php
            //var_dump($post);
if (isset($post['img'])){
            $imgPreview=$post['img'];
            //var_dump($post['img']);
            $show_img = base64_encode($post['img']);
            ?>
            <input value = "<?php $imgPreview?>" name="imgPreview" type="hidden">
            <div class="article">
                <img id="imgPreview" src="data:image/jpeg;base64, <?php echo $show_img ?>" alt="">
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
                    <?php
                        }
?>
                </script>
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

                while ($row = $result->fetch_assoc()) {
                    if ($row['name'] === $nameCategory) {

                    continue;

                    }?>
                    <option><?php echo $row['name']; ?></option>
                    <?php
                }
            }
            ?>
            <option selected><?php echo $nameCategory;?></option>

        </select>
        <div class="clear"><br></div>
        <div class="clear"><br></div>
                <button name="posts-edit" class="button-row" type="submit">Обновить</button>
            </form>
        </div>
    </div>
</div>
</div>
<div class="clear"><br></div>

<script>
    function validateFile() {
        var fileInput = document.getElementById('inputGroupFile02');
        var fileSize = fileInput.files[0].size; // размер файла в байтах
        var maxSize = 2048 * 1024; // максимальный размер в байтах (2048 Кб)

        if (fileSize > maxSize) {
            fileInput.value = '';
            alert('Выбранный файл слишком большой. Максимальный размер: 2048 Кб.');
            return 0;
        } else {
            // Если файл удовлетворяет условиям, обновляем prevImagePreview
            prevImagePreview = document.getElementById('imgPreview').src;
        }
    }


    // Привязываем функцию к событию изменения значения в input
    document.getElementById('inputGroupFile02').addEventListener('change', function() {
        validateFile();
    });
</script>



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
    <?php require_once "Z:/home/art-display/www/blocks/footer.php"?>
</footer>
</html>