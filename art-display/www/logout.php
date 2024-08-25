<?php
session_start();

unset ($_SESSION['id']);
unset ($_SESSION['login']);
unset ($_SESSION['role']);
unset($_SESSION['login_attempts']);
unset($_SESSION['block_time']);

header("location: index.php");
?>