<?php
require_once('db.php');
//var_dump($_GET);
if ($_GET) {
    $postId = $_GET['post'];
}
$comment = '';
$login = '';
$errorMessage = '';
$successMessage = '';
$status = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goComment'])) {
//var_dump($_SESSION);
//exit();
    $userId = $_POST['userId'];
    $login = $_POST['login'];
    $comment = trim($_POST['comment']);
    if (!(empty($comment))) {
        if (mb_strlen($comment, 'UTF8') < 3) {
            $errorMessage = "Комментарий должен быть длиннее трёх символов!";
        } else {
            $sql = "INSERT INTO `comments` (post_id, user_id, comment) VALUES ('$postId','$userId', '$comment')";
            if ($conn->query($sql)) {
                $successMessage = "Комментарий добавлен!";
            } else {
                $errorMessage = "Ошибка: " . $conn->error;
            }
        }
    } else {
        $errorMessage = "Комментарий не должен быть пуст";
    }
} else {
    $userId = '';
    $login = '';
    $comment = '';
}


/*редактирование*/
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM `comments` WHERE id = '$id'";
    $result = $conn->query($sql);
    $post = $result->fetch_assoc();
    $id = $post['id'];
    $post_id = $post['post_id'];
    $user_id = $post['user_id'];
    $comment = trim($post['comment']);
    $img = $post['img'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['comment_id_to_edit'];
    $comment = trim($_POST['comment']);
    $post_id = $_POST['post_id'];
    $url = "http://art-display/single.php?post=" . $post_id;
    if (!(isset($comment))) {
        if (mb_strlen($comment, 'UTF8') < 3) {
            $errorMessage = "Комментарий должен быть длиннее трёх символов!";
        } else {
            $sql = "UPDATE `comments` SET comment='$comment' WHERE id='$id'";
            $result = $conn->query($sql);
        }
    } else {
        $errorMessage = "Комментарий не должен быть пуст";
    }
}

/**/
//удаление
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $idToDelete = $_POST['comment_id_to_delete'];
        // Проверка на наличие комментария с таким ID и его удаление из базы данных
        $sql = "DELETE FROM `comments` WHERE id='$idToDelete'";
        $result = $conn->query($sql);
    }
}
?>