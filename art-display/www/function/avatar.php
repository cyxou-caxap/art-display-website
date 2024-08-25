<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('db.php');
$errorMessage = '';
$successMessage = '';
$img='';
$err=0;

if (empty($_FILES['img']['size'])) {
    $errorMessage= "Выберите изображение, которое весит меньше 2048 Кб";
    $err=1;
} else {
    if (isset($_FILES['img']) && !empty($_FILES['img']['name'])) {
        $img = addslashes(file_get_contents($_FILES['img']['tmp_name']));}
    else {
        $errorMessage= "Аватар не был загружен";
        echo 'Error code:';
        $err=1;
    }}
if (!($err)){
    $id=$_GET['us_id'];
//var_dump($id);
    $sql = "UPDATE `registeruser` SET avatar='$img' WHERE id='$id'";
    $result = $conn->query($sql);
    $successMessage= "Аватар был загружен.";
    header("location: http://art-display/userPage/userPage.php?userId=$id");
//var_dump($row);
}


?>
