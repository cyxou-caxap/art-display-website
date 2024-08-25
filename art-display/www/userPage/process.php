<?php
// Подключение к базе данных
require_once('Z:/home/art-display/www/function/db.php');
//var_dump($_POST);
//exit();
if(isset($_POST["action"])) {
    $action = $_POST["action"];
$user_id=$_POST["user_id"];
    // В зависимости от действия выполняем соответствующий запрос или операцию
    switch($action) {
        case "portfolio": ?>
            <link rel="stylesheet" href="../css/userPage.css">
            <div id="posts">
            <?php
            $sql = "SELECT * FROM posts WHERE status = 1 and user_id='$user_id' ORDER BY id DESC";

// Запрос к базе данных
$result = $conn->query($sql);

// Обработка результатов запроса

if ($result->num_rows > 0) {
    // Вывод данных
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] != 0) {
            $show_img = base64_encode($row['img']);
            ?>
            <div class="article" style="width: 28%">
                <div class="like-icon"></div>
                <div>
                    <img style="height: 280px;width: 280px" src="data:image/jpeg;base64,<?php echo $show_img ?>" alt="">
                    <a href="http://art-display/single.php?post=<?php echo $row['id']; ?>"><?php
                        echo substr($row['title'], 0, 27);
                        if (strlen($row['title'])>27) {
                            echo '...';
                        } ?> </a>
                    <p><?php
                        $idUser=$row['user_id'];
                        $sql = "SELECT login FROM `registeruser` WHERE id = '$idUser'";
                        $resultName = $conn->query($sql);
                        $resultName = $resultName->fetch_assoc()
                        ?>
                        <?php echo $resultName['login']; ?> </p>
                </div>
            </div>
            <?php
        }
    }
} else { ?>
        <h2 style="margin: 30px 40px 10px 10px">Нет опубликованных работ</h2>
    <?php
}

// Закрытие подключения к базе данных
$conn->close(); ?>
    <div class="clear"><br></div>
</div>
<?php
            break;
        case "unsubscription":

            $subscriber_id = $_POST['follower'];
            $user_id = $_POST['user_id'];
            $sql = "DELETE from `followers` where  subscriber='$subscriber_id' AND user_id='$user_id'";
            $conn->query($sql);
            break;
        case "subscriptions":
            $user_id = $_POST['user_id'];
            $sql = "SELECT * from `followers` where  subscriber='$user_id'";
$result = $conn->query($sql);

// Обработка результатов запроса
            ?>
<div class="subscriber">
        <?php
if ($result->num_rows > 0) {
// Вывод данных
while ($row = $result->fetch_assoc()) {
    //var_dump($row);
    //exit();
    $id=$row['user_id'];
    $sql = "select * from `registeruser` where  id='$id'";
    $resLog=$conn->query($sql);
    $resLog=$resLog->fetch_assoc();
    $log=$resLog['login'];
    ?>
    <li><a href="http://art-display/userPage/userPage.php?userId=<?php echo $row['user_id']; ?>"><?php echo $log; ?></a></li>
<?php
}} else { ?>
    <h2 style="margin: 30px 40px 10px 10px">Нет подписок</h2>
    <?php
}

// Закрытие подключения к базе данных
            $conn->close(); ?>
            <div class="clear"><br></div>
            </div>
            <?php
            break;

        case "followers":
            $user_id = $_POST['user_id'];
            $sql = "SELECT * from `followers` where  user_id='$user_id'";
            $result = $conn->query($sql);

// Обработка результатов запроса
            ?>
<div class="follower">
    <?php
            if ($result->num_rows > 0) {
// Вывод данных
                while ($row = $result->fetch_assoc()) {
                    //var_dump($row);
                    //exit();
                    $id=$row['subscriber'];
                    $sql = "select * from `registeruser` where  id='$id'";
                    $resLog=$conn->query($sql);
                    $resLog=$resLog->fetch_assoc();
                    $log=$resLog['login'];
                    ?>
                    <li> <a href="http://art-display/userPage/userPage.php?userId=<?php echo $row['subscriber']; ?>"><?php echo $log; ?></a> </li>
                    <?php
                }} else { ?>
                <h2 style="margin: 30px 40px 10px 10px">Нет подписчиков</h2>
                <?php
            }
// Закрытие подключения к базе данных
            $conn->close(); ?>
            <div class="clear"><br></div>
            </div>
            <?php
            break;
        case "statistic":
            $user_id = $_POST['user_id'];
            $sql = "SELECT DATE(created_date) AS date, COUNT(*) AS post_count FROM posts WHERE user_id='$user_id' GROUP BY DATE(created_date)";
            $result = $conn->query($sql);
            $data = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data['labels'][] = $row['date'];
                    $data['posts'][] = $row['post_count'];
                }
            }
            echo json_encode($data);
            break;
        case "subscription":
                $subscriber_id = $_POST['follower'];
                $user_id = $_POST['user_id'];
                $sql = "INSERT INTO followers (subscriber, user_id) VALUES ('$subscriber_id', '$user_id')";
                $conn->query($sql);

            break;
        default:

    }
}
?>
