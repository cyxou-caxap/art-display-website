<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('function/db.php');

//добавление категории
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addLike'])) {
    $post_id=$_POST['post_id'];
$userId=$_POST['user_id'];
    $sql = "SELECT * FROM visits WHERE post_id = '$post_id' AND user_id = '$userId'";
    $result = $conn->query($sql);
    $result = $result->fetch_assoc();
    if ($result['like']==0){
        $sql = "UPDATE visits SET `like`=1
                WHERE post_id = '$post_id' AND user_id = '$userId'";
        $result = $conn->query($sql);
        header('Location: http://art-display/single.php?post=' . $post_id);
        exit();
    }
    else{
        $sql = "UPDATE visits SET `like`=0
                WHERE post_id = '$post_id' AND user_id = '$userId'";
        $result = $conn->query($sql);
        header('Location: http://art-display/single.php?post=' . $post_id);
        exit();
    }
}
