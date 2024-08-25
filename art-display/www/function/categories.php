<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('db.php');

$errorMessage = '';
$successMessage = '';
$id = '';
$name = '';
$description = '';

//добавление категории
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categories-create'])) {
//var_dump($_POST);
//exit();
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    if (!(empty($name)) && !(empty($description))) {
        $sql = "SELECT * FROM `categories` WHERE name = '$name'";
        $resultUni = $conn->query($sql);

        if ((mb_strlen($name, 'UTF8') < 3) || (mb_strlen($name, 'UTF8') > 60)) {
            $errorMessage = "Название категории должно быть от 3-х до 60-и символов!";
        } elseif ($resultUni->num_rows > 0) {
            while ($row = $resultUni->fetch_assoc()) {
                $errorMessage = "Категория c названием: " . $row['name'] . " уже существует!";
            }
        } elseif ((mb_strlen($description, 'UTF8') < 3)) {
            $errorMessage = "Описание категории должно быть от 3-х символов!";
        } else {
            $sql = "INSERT INTO `categories` (name, description) VALUES ('$name', '$description')";
            $resultUni = $conn->query($sql);
            $successMessage = "Категория добавлена!";
            // Очистите поля после успешной регистрации
            $name = '';
            $description = '';
        }
    } else {
        $errorMessage = "Название и описание не должны быть пусты!";
    }
} else {
    $name = '';
    $description = '';
}

//редактирование категории
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM `categories` WHERE id = '$id'";
    $result = $conn->query($sql);
    $category = $result->fetch_assoc();
    //var_dump($category);
    $id = $category['id'];
    $name = $category['name'];
    $description = $category['description'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categories-edit'])) {

    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));

    $sql = "SELECT * FROM `categories` WHERE name = '$name'";
    $resultUni = $conn->query($sql);
    if (empty($name) || empty($description)) {
        $errorMessage = "Название и описание не должны быть пусты!";
    } elseif ((mb_strlen($name, 'UTF8') < 3) || (mb_strlen($name, 'UTF8') > 60)) {
        $errorMessage = "Название категории должно быть от 3-х до 60-и символов!";
    } elseif ((mb_strlen($description, 'UTF8') < 3)) {
        $errorMessage = "Описание категории должно быть от 3-х символов!";
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $sql = "UPDATE `categories` SET name='$name',description='$description' WHERE id='$id'";
        $result = $conn->query($sql);
        $successMessage = "Категория обновлена!";
        // Очистите поля после успешной регистрации
        $name = '';
        $description = '';
    }
}


//удаление категории
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $sql = "DELETE FROM `categories` WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result) {
        $sql = "SELECT * FROM `posts` WHERE category_id = '$id'";
        $result = $conn->query($sql);
        if ($result) {
            while ($category = $result->fetch_assoc()) {
                //var_dump($category);
                $id_cat = $category['id'];
                $sql = "UPDATE `posts` SET category_id=18 WHERE category_id='$id'";
                $resultUpd = $conn->query($sql);

            }
        } // Запрос успешно выполнен

        header("location: http://art-display/admin/categories/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса DELETE
        echo "Ошибка при удалении категории: " . $conn->error;
    }
}

?>

