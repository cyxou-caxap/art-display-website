<?php
$to = "grishkina03@inbox.ru"; // Почтовый ящик, на который будет отправлено сообщение
$subject = "Вопрос от посетителя сайта"; // Тема сообщения
$headers = "Content-type: text/html; charset=utf-8 \r\n"; // Заголовок сообщения

$errorMessage = '';
$successMessage = '';
// Проверяем метод запроса POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_mes'])) {
    // Проверяем, были ли переданные параметры формы, и что они не пустые
    if (isset($_POST["username"]) && isset($_POST["useremail"]) && isset($_POST["question"])) {
        // Присваиваем значения переменным
        $name = htmlspecialchars(trim(strip_tags($_POST["username"])));
        $email = htmlspecialchars(trim(strip_tags($_POST["useremail"])));
        $question = htmlspecialchars(trim(strip_tags($_POST["question"])));

        // Формируем сообщение
        $message = "<html>";
        $message .= "<body>";
        $message .= "Email: " . $email;
        $message .= "<br />";
        $message .= "Имя: " . $name;
        $message .= "<br />";
        $message .= "Вопрос: " . $question;
        $message .= "</body>";
        $message .= "</html>";

        // Посылаем письмо
        if (mail($to, $subject, $message, $headers)) {
            $successMessage="Письмо успешно отправлено";
        } else {
            $errorMessage="Ошибка при отправке письма";
        }
    } else {
        $errorMessage="Не все параметры формы были переданы или они пустые";
    }
} else {

}
?>
