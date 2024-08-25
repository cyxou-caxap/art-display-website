<?php
session_start();
// Подключение к базе данных
require_once('../function/users.php');
require_once('Z:/home/art-display/www/function/db.php');
//var_dump($_SESSION);

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/userPage.css">
    <?php require_once "Z:/home/art-display/www/blocks/head.php";?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Обработчик события click для всех кнопок
            $(".buttons button").click(function(){
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

            $(".buttons button.statistic").click(function(){
                var action = $(this).attr("class");
                $.ajax({
                    url: "process.php",
                    method: "POST",
                    data: { action: action, user_id: <?=$user_id;?> },
                    success: function(response){
                        var data = JSON.parse(response);

                        // Получаем массивы для дат и количества постов из данных
                        var dates = data.labels;
                        var counts = data.posts;

                        // Создаем элемент canvas для отображения графика
                        var canvas = $('<canvas>').attr('id', 'postChart').attr('width', '625').attr('height', '625');

                        // Добавляем созданный canvas внутрь div с классом content
                        $('.content').html(canvas);

                        // Получаем контекст canvas для отображения графика
                        var ctx = document.getElementById('postChart').getContext('2d');

                        // Создаем новый график с использованием Chart.js
                        var postChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: dates, // Даты постов
                                datasets: [{
                                    label: 'Количество постов',
                                    data: counts, // Количество постов
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });

// Добавляем кнопку для сохранения статистики
                        var saveButton = $('<button class="saveButton">').text('Сохранить статистику').addClass('save-statistic-button');
                        $('.content').append(saveButton);

                        // Обработчик события click для кнопки сохранения статистики
                        $('.save-statistic-button').click(function(){
                            // Используем html2canvas для создания скриншота элемента с классом content
                            html2canvas($('.content')[0]).then(function(canvas) {
                                // Получаем данные изображения в формате base64
                                var imageData = canvas.toDataURL('image/png');

                                // Отправляем изображение на сервер для сохранения
                                $.ajax({
                                    url: "save_image.php",
                                    method: "POST",
                                    data: { image: imageData },
                                    success: function(response){
                                        console.log("Статистика успешно сохранена в виде изображения!");
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Произошла ошибка при сохранении статистики как изображения: " + error);
                                    }
                                });
                            });
                        });

                    }
                });
            });
        });

            $(document).ready(function(){
        $(".subscription").click(function(){
            var action = $(this).attr("class");
            $.ajax({
                url: "process.php",
                method: "POST",
                data: { action: action, user_id: <?=$user_id;?>, follower: <?=$_SESSION['id'];?> },
                success: function(response){
                    $(".content").html(response);
                    location.reload();
                }
            });
        });
        });
        $(document).ready(function(){
            $(".unsubscription").click(function(){
                var action = $(this).attr("class");
                $.ajax({
                    url: "process.php",
                    method: "POST",
                    data: { action: action, user_id: <?=$user_id;?>, follower: <?=$_SESSION['id'];?> },
                    success: function(response){
                        $(".content").html(response);
                        location.reload();
                    }
                });
            });
        });

    </script>

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
    if (!$flag){
        ?>
    <button onclick="window.location.href='edit_cover.php?us_id=<?=$user_id;?>'" class="edit-cover">Изменить обложку</button>
    <?php
    };
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
        if (!$flag){
            ?>
            <button onclick="window.location.href='edit_avatar.php?us_id=<?=$user_id;?>'" class="edit-avatar">
                <img src="../img/pen.png" alt="Изменить аватар">
            </button>
            <?php
        };
        ?>
    </div>

    <h2><?php echo $user['login']; ?></h2>

    <div class="information">
        <h4>Просмотры: <br><?php echo $viewsSum; ?></h4>
        <h4>Посты: <br><?php echo $postSum; ?></h4>
        <h4>Подписчики: <br><?php echo $subSum; ?></h4>
    </div>
    <?php
    if (!$flag){
    ?>
    <button onclick="window.location.href='redact.php?us_id=<?=$user_id;?>'" class="edit-profile" type="submit">Редактировать профиль</button>
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

<div class="buttons">
    <button class="portfolio">Портфолио</button>
    <?php
    if (!$flag){
        ?>
    <button onclick="window.location.href='index.php?us_id=<?=$user_id;?>'" class="create_post">Управление постами</button>
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

<div class="content">
    <canvas id="postChart" style="width=625px; height=625px"></canvas>

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

