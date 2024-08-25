<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('db.php');

$errorMessage = '';
$successMessage = '';
$id='';
$title='';
$content='';
$category='';
$img='';

//добавление поста

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {

    $title = htmlspecialchars(trim($_POST['title']));
    $content = trim($_POST['content']);
    $category=$_POST['category'];
    if (isset($_POST['id'])){
        $id=$_POST['id'];
    }

    if (empty($title) || empty($content) || empty($category) || empty($_FILES['img']['size'])) {
        $errorMessage= "Заполните все поля, изображение должно весить меньше 2048 Кб";
    } else {
    if (isset($_FILES['img']) && !empty($_FILES['img']['name'])) {
    $img = addslashes(file_get_contents($_FILES['img']['tmp_name']));}
else {
    $errorMessage= "Изображение не было загружено.";
    echo 'Error code:';
}}
    $sql="SELECT * FROM `categories` WHERE name = '$category'";
    $resultUni = $conn->query($sql);
    $row=$resultUni->fetch_assoc();
    //var_dump($row);
    //exit();
    $idCategory=$row['id'];
    $userId=$_SESSION['id'];

    if ((mb_strlen($title,'UTF8')<3)||(mb_strlen($title,'UTF8')>255)){
        $errorMessage = "Название поста должно быть от 3-х до 255-и символов!";
    }
    else {
        $sql = "INSERT INTO `posts` (title,content,user_id,img,category_id) VALUES ('$title','$content', '$userId', '$img','$idCategory')";
        if ($conn->query($sql)) {
            $postId = $conn->insert_id;
            $sql = "SELECT * FROM `posts` WHERE id = '$postId'";
            $result = $conn->query($sql);
            if ($result) {
                $successMessage = "Пост добавлен!";

                $title='';
                $content='';
                $category='';
                $img='';

            } else {
                $errorMessage = "Ошибка: " . $conn->error;
            }
        }
    }}else{
    $title='';
    $content='';
    $category='';
    $img='';
}

//редактирование Поста
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id=$_GET['id'];
    $sql="SELECT * FROM `posts` WHERE id = '$id'";
    $result = $conn->query($sql);
    $post = $result->fetch_assoc();
    $id=$post['id'];
    $name=$post['title'];
    $content=$post['content'];
    $category=$post['category_id'];
    $img=$post['img'];

    $sql="SELECT name FROM `categories` WHERE id = '$category'";
    $resultUni = $conn->query($sql);
    $row=$resultUni->fetch_assoc();
    $nameCategory=$row['name'];
}

// ...

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['posts-edit'])) {
    $id = $_POST['id'];
    $name = htmlspecialchars(trim($_POST['title']));
    $content = trim($_POST['content']);
    $category = $_POST['category'];
   // var_dump($_POST);


    // Получение ID категории
    $sql = "SELECT id FROM `categories` WHERE name = '$category'";
    $resultUni = $conn->query($sql);
    $row = $resultUni->fetch_assoc();
    $idCategory = $row['id'];
    if (empty($name) || empty($content)){
        $errorMessage = "Название поста и его описание не должны быть пусты!";
    }
    elseif (empty($_FILES['img']['size']) && !empty($_FILES['img']['name'])){
        $errorMessage= "Изображение должно весить меньше 2048 Кб";
        $post['img']=$_POST['imgPreview'];
    }
    elseif ((mb_strlen($name,'UTF8')<3)||(mb_strlen($name,'UTF8')>255)){
        $errorMessage = "Название поста должно быть от 3-х до 255-и символов!";
    } else {
        // Проверка наличия нового изображения
        if (!empty($_FILES['img']['name'])) {
            $img = addslashes(file_get_contents($_FILES['img']['tmp_name']));

            // Обновление с изображением
            $sql = "UPDATE `posts` SET title='$name', content='$content', category_id=$idCategory, img='$img' WHERE id='$id'";
        } else {
            // Обновление без изображения
            $sql = "UPDATE `posts` SET title='$name', content='$content', category_id=$idCategory WHERE id='$id'";
        }

        // Выполнение запроса
        if ($conn->query($sql) === TRUE) {
            if (isset($_POST['us_id'])){
            $id=$_POST['us_id'];
            header("location: http://art-display/userPage/index.php?us_id=$id");
            exit();
        }
            header("location: /admin/posts/index.php");
        } else {
            $errorMessage = "Ошибка при обновлении поста: " . $conn->error;
        }
    }
}



//удаление поста
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $sql = "DELETE FROM `posts` WHERE id = '$id'";

    if ($conn->query($sql)) {
        if (isset($_GET['us_id'])){
            $id=$_GET['us_id'];
            header("location: http://art-display/userPage/index.php?us_id=$id");
            exit();
        }
        // Запрос успешно выполнен
        header("location: http://art-display/admin/posts/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса DELETE
        echo "Ошибка при удалении поста: " . $conn->error;
    }
}

//блокировка поста
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['block_id'])) {
    $id = $_GET['block_id'];
    $sql = "UPDATE `posts` SET status=0 WHERE id='$id'";
    $resultUni = $conn->query($sql);

    if ($conn->query($sql)) {
        // Запрос успешно выполнен
        header("location: http://art-display/admin/posts/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса UPDATE
        echo "Ошибка при блокировке поста: " . $conn->error;
    }
}

//разблокировка поста
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['unblock_id'])) {
    $id = $_GET['unblock_id'];
    $sql = "UPDATE `posts` SET status=1 WHERE id='$id'";
    $resultUni = $conn->query($sql);

    if ($conn->query($sql)) {
        // Запрос успешно выполнен
        header("location: http://art-display/admin/posts/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса UPDATE
        echo "Ошибка при блокировке поста: " . $conn->error;
    }
}
?>