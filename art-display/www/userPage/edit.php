<?php
session_start();
//var_dump($_SESSION);
$user_id=$_SESSION['id'];
// Подключение к базе данных
require_once('Z:/home/art-display/www/function/db.php');
require_once('../function/post.php');

// Получаем информацию о пользователе из базы данных
//var_dump($_GET);
//exit();

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
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
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
    <button onclick="window.location.href='http://art-display/userPage/userPage.php?userId=<?= $user_id; ?>'"
            class="create_post">Вернуться в профиль
    </button>
    <button onclick="window.location.href='http://art-display/userPage/index.php?id=<?= $user_id; ?>'"
            class="create_post">Посты
    </button>
    <button onclick="window.location.href='http://art-display/userPage/indexCat.php?id=<?= $user_id; ?>'"
            class="create_post">Категории
    </button>
</div>

<div class="content">
    <div class="button-row">
        <a href="create.php?id=<?= $user_id; ?>" class="col-2">Добавление</a>
        <a href="index.php?id=<?= $user_id; ?>" class="col-2">Управление</a>
    </div>
    <div class="created">
        <h2>Обновление поста</h2>

        <?php
        if (!empty($errorMessage)): ?>
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
                <input name="us_id" type="hidden" value="<?=$user_id;?>">
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

