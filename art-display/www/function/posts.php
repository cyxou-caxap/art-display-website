<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('db.php');

$name = isset($_POST['title']) ? $_POST['title'] : '';
$dis = isset($_POST['discription']) ? $_POST['discription'] : '';

if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
    $img = addslashes(file_get_contents($_FILES['image']['tmp_name']));

    if (empty($name) || empty($dis) || empty($img)) {
        echo "Заполните все поля";
    } else {
        $sql = "INSERT INTO `post` (title, discription, image) VALUES ('$name', '$dis', '$img')";
        if ($conn->query($sql)) {
            echo "Пост добавлен успешно!";

        } else {
            echo "Ошибка: " . $conn->error;
        }
    }
} else {
    echo "Изображение не было загружено.";
    echo 'Error code: ' . (isset($_FILES['image']['error']) ? $_FILES['image']['error'] : 'unknown');
}

    $query=$conn->query("SELECT * FROM post ORDER BY id DESC");//показ записей от новых к старым
    while($row=$query->fetch_assoc()){
        $show_img=base64_encode($row['image']);?>
        <img src = "data:image/jpeg;base64, <?php echo $show_img ?>" alt="">
    <?php }
?> 
