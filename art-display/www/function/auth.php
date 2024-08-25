<?php
require_once('db.php');

$login=htmlspecialchars(trim(($_POST['login'])));
$pass=$_POST['pass'];

if (empty($login)||empty($pass)){
    echo"Заполните все поля";
} else{
    $sql="SELECT * FROM `registeruser` WHERE login = '$login' AND pass = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows>0){
        while($row=$result->fetch_assoc()){
            echo"Добро пожаловать, ". $row['login'];
        }
    }
    else{
        echo "Такого пользователя не существует";
    }
}
?>