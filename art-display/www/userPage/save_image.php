<?php
// save_image.php

// Получаем данные изображения из POST запроса
$imageData = $_POST['image'];

// Генерируем уникальное имя файла
$filename = 'statistic_' . time() . '.png';

// Сохраняем изображение на сервере
file_put_contents('../images/' . $filename, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)));

// Возвращаем имя сохраненного файла
echo $filename;
?>
