<?php
 $servername="localhost";
 $username="root";
 $password="";
 $dbname="art-display";
 $charset='utf8';

 $conn=mysqli_connect($servername,$username,$password,$dbname);
 if(!$conn){
    die("Connection Fialed". mysqli_connect_error());
 }
 else if (!$conn->set_charset($charset)){
    "Ошибка установки кодировки";
 }
 else {
   "База данных подключена";
}
?>