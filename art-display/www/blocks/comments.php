<?php
include "Z:/home/art-display/www/function/commentaries.php";
?>

<style>
    .comment-form {
        width: 580px;
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .comment-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .comment-form textarea,
    .comment-form input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    .comment-form button {
        background-color: #ded0c1;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .comment-form button:hover {
        background-color: #f5e7d8;
        color: black;
    }

    .comment {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
    }

    .comment .user-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 5px;
    }

    .comment .user-login {
        font-weight: bold;
        margin-bottom: 5px;
        width: 100%;
    }

    .comment .created-date {
        color: #777;
        font-size: 0.8em;
    }

    .comment .comment-text {
        margin-bottom: 5px;
    }

    .comment .actions {
        display: flex;
        align-items: center;
        float: right;
    }

    .comment .actions img {
        width: 25px;
        height: 25px;
        cursor: pointer;
    }

    .comment .actions button {
        padding: 5px 5px;
        margin-left: 5px;
    }

</style>
<script>
    function toggleEditForm(commentId) {
        var editForm = document.getElementById('editForm' + commentId);
        if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
        } else {
            editForm.style.display = 'none';
        }
    }
</script>
    <?php
    if ($_SESSION) {
        $userLogin = $_SESSION['login'];
        $userId = $_SESSION['id'];
        ?>
<div class="comment-form">
        <form action="http://art-display/single.php?post=<?php echo $postId; ?>" method="post">
            <input type="hidden" name="postId" value="<?= $postId; ?>">
            <!---<label for="login">Имя пользователя:</label>--->
            <?php if (!empty($errorMessage)): ?>
                <div class="error">
                    <p><?= $errorMessage ?></p>
                </div>
            <?php elseif (!empty($successMessage)): ?>
                <div class="success">
                    <p><?= $successMessage ?></p>
                </div>
            <?php endif;?>
            <input type="hidden" id="login" name="login" value="<?= $userLogin; ?>">
            <input type="hidden" id="userId" name="userId" value="<?= $userId; ?>">

            <label for="comment">Комментарий:</label>
            <textarea id="comment" value="$comment" name="comment" rows="4" placeholder="Ваш комментарий" required></textarea>

            <button type="submit" name="goComment">Отправить</button>
        </form>
        <br>
        <?php
    }
    $sql = "SELECT * FROM `comments` WHERE post_id = '$postId' and status=1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        ?>
        <h3>Комментарии к посту</h3>
        <?php
        while ($row = $result->fetch_assoc()) { ?>
            <input type="hidden" name="comment_id_to_edit" value="<?= $row['id']; ?>">
            <div class="comment">
                <?php
                $user_id = $row['user_id'];
                $sql = "SELECT * FROM `registeruser` WHERE id = '$user_id'";
                $resultLog = $conn->query($sql);
                $resultLog = $resultLog->fetch_assoc();
                $userLog = $resultLog['login'];
                ?>
                <div class="user-info">
                    <div class="user-login">
                        <a href="http://art-display/userPage/userPage.php?userId=<?= $resultLog['id']; ?>"><?php echo $userLog; ?></a> </p>

                    </div>
                    <div class="created-date"><?= $row['created_date'] ?></div>
                </div>
                <div class="comment-text"><?= htmlspecialchars($row['comment']) ?></div>
                <?php
                if ($_SESSION) {
                    if (($_SESSION['id'] == $user_id) || $_SESSION['role'] == 0) {
                        ?>
                        <div id="editForm<?= $row['id'] ?>" style="display: none;">
                            <form action="http://art-display/single.php?post=<?php echo $postId; ?>" method="post">
                                <input type="hidden" name="comment_id_to_edit" value="<?= $row['id'] ?>">
                                <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                <textarea id="comment" name="comment" rows="4"
                                          required placeholder="Ваш изменённый комментарий"><?= $row['comment'] ?> </textarea>
                                <button type="submit" name="edit" value="true">Сохранить изменения</button>
                            </form>
                        </div>
                        <div class="actions">
                            <!-- Кнопка для запуска формы редактирования -->
                            <button onclick="toggleEditForm(<?= $row['id'] ?>)">
                                <img src="../img/path_to_edit_icon.png" alt="Редактировать">
                            </button>
                            <form action="http://art-display/single.php?post=<?= $postId; ?>" method="post">
                                <input type="hidden" name="comment_id_to_delete" value="<?= $row['id']; ?>">
                                <button type="submit" name="delete" value="true">
                                    <img src="../img/path_to_delete_icon.png" alt="Удалить">
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>


