<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('db.php');

$errorMessage = '';
$successMessage = '';
$id = '';
$login = '';
$pass = '';
$repeatpass = '';
$email = '';
$role = '';

/*добавление пользователя*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-create'])) {
    //var_dump($_POST);
    //exit();
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $repeatpass = $_POST['repeatpass'];
    $email = $_POST['email'];
    if (isset($_POST['isAdmin'])) {
        $role = 0;
    } else {
        $role = 1;
    }
    $sql = "SELECT * FROM `registeruser` WHERE login = '$login'";
    $resultLog = $conn->query($sql);

    $sql = "SELECT * FROM `registeruser` WHERE email = '$email'";
    $resultEmail = $conn->query($sql);

    if ($resultLog->num_rows > 0) {
        while ($row = $resultLog->fetch_assoc()) {
            $errorMessage = "Пользователь " . $row['login'] . " уже зарегистрирован!";
        }
    } elseif ($resultEmail->num_rows > 0) {
        while ($row = $resultEmail->fetch_assoc()) {
            $errorMessage = "Пользователь c email: " . $row['email'] . " уже зарегистрирован!";
        }
    } elseif ($pass != $repeatpass) {
        $errorMessage = "Пароли не совпадают";
    } elseif ((mb_strlen($login, 'UTF8') > 25)) {
        $errorMessage = "Логин пользователя должен быть до 25-и символов!";
    } elseif ((mb_strlen($pass, 'UTF8') > 50)) {
        $errorMessage = "Пароль пользователя должен быть до 50-и символов!";
    } elseif ((mb_strlen($email, 'UTF8') > 50)) {
        $errorMessage = "Email пользователя должен быть до 50-и символов!";
    } else {
        $hashedPass = md5($pass);
        $sql = "INSERT INTO `registeruser` (role, login, pass, email) VALUES ('$role', '$login','$hashedPass','$email')";
        if ($conn->query($sql)) {
            $userId = $conn->insert_id;
            $sql = "SELECT * FROM `registeruser` WHERE id = '$userId'";
            $result = $conn->query($sql);

            if ($result) {
                $successMessage = "Пользователь успешно зарегистрирован!";
// Очистите поля после успешной регистрации
                $login = '';
                $email = '';

            } else {
                $errorMessage = "Ошибка: " . $conn->error;
            }
        }
    }
} else {
    $login = '';
    $email = '';
}


//редактирование пользователя
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['id']) || (isset($_GET['us_id'])))) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = $_GET['us_id'];
    }

    $sql = "SELECT * FROM `registeruser` WHERE id = '$id'";
    $result = $conn->query($sql);
    $post = $result->fetch_assoc();
    $login = $post['login'];
    $email = $post['email'];
    $role = $post['role'];
}

//редактирование пользователя

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-edit'])) {
    //var_dump($_POST);
    //exit();
    $id = $_POST['id'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    if (isset($_POST['isAdmin'])) {
        $role = 0;
    } else {
        $role = 1;
    }
    $sql = "SELECT * FROM `registeruser` WHERE login = '$login'";
    $resultLog = $conn->query($sql);

    $sql = "SELECT * FROM `registeruser` WHERE email = '$email'";
    $resultEmail = $conn->query($sql);
    $err = 0;
    if ($resultLog->num_rows > 0) {
        while ($row = $resultLog->fetch_assoc()) {
            if ($row['id'] != $id) {
                $errorMessage = "Пользователь " . $row['login'] . " уже зарегистрирован!";
                $err = 1;
            }
        }
    }
    if ($resultEmail->num_rows > 0) {
        while ($row = $resultEmail->fetch_assoc()) {
            if ($row['id'] != $id) {
                $errorMessage = "Пользователь c email: " . $row['email'] . " уже зарегистрирован!";
                $err = 1;
            }
            elseif ((mb_strlen($login,'UTF8')>25)){
                $errorMessage = "Логин пользователя должен быть до 25-и символов!";
                $err = 1;
            }
            elseif ((mb_strlen($pass,'UTF8')>50)){
                $errorMessage = "Пароль пользователя должен быть до 50-и символов!";
                $err = 1;
            }
            elseif ((mb_strlen($email,'UTF8')>50)){
                $errorMessage = "Email пользователя должен быть до 50-и символов!";
                $err = 1;
            }
        }
    }
    if (!$err) {
        if (!empty($pass)) {
            $hashedPass = md5($pass);
            if (isset($_POST['us_id'])) {
                $sql = "UPDATE `registeruser` SET login='$login', pass='$hashedPass', email='$email' WHERE id = '$id'";
            } else {
                $sql = "UPDATE `registeruser` SET login='$login', pass='$hashedPass', role='$role', email='$email' WHERE id = '$id'";
            }
        } else {
            if (isset($_POST['us_id'])) {
                $sql = "UPDATE `registeruser` SET login='$login', email='$email' WHERE id = '$id'";
            } else {
                $sql = "UPDATE `registeruser` SET login='$login', role='$role', email='$email' WHERE id = '$id'";
            }
        }
        $successMessage = "Данные пользователя обновлены!";
        if ($conn->query($sql) === TRUE && !(isset($_POST['us_id']))) {
            header("location: /admin/users/index.php");
        } elseif (isset($_POST['us_id'])) {
            $successMessage = "Данные пользователя обновлены!";
            $_SESSION['login'] = $login;
        } else {
            $errorMessage = "Ошибка при обновлении пользователя: " . $conn->error;
        }
    }
}


//удаление пользователя
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $sql = "DELETE FROM `registeruser` WHERE id = '$id'";

    if ($conn->query($sql)) {
        $sql = "UPDATE `posts` SET status=0 WHERE user_id='$id'";
        $conn->query($sql);
        // Запрос успешно выполнен
        header("location: http://art-display/admin/users/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса DELETE
        echo "Ошибка при удалении пользователя: " . $conn->error;
    }
}

//блокировка пользователя
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['block_id'])) {
    $id = $_GET['block_id'];
    $sql = "UPDATE `registeruser` SET status=0 WHERE id='$id'";
    $resultUni = $conn->query($sql);

    if ($conn->query($sql)) {
        // Запрос успешно выполнен
        header("location: http://art-display/admin/users/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса UPDATE
        echo "Ошибка при блокировке пользователя: " . $conn->error;
    }
}

//разблокировка пользователя
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['unblock_id'])) {
    $id = $_GET['unblock_id'];
    $sql = "UPDATE `registeruser` SET status=1 WHERE id='$id'";
    $resultUni = $conn->query($sql);

    if ($conn->query($sql)) {
        // Запрос успешно выполнен
        header("location: http://art-display/admin/users/index.php");
        exit();
    } else {
        // Ошибка выполнения запроса UPDATE
        echo "Ошибка при блокировке пользователя: " . $conn->error;
    }
}
?>