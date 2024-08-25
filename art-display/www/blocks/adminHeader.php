<div id="logo">
    <a href="/" title="Перейти на главную"><h1><?= $title ?></h1></a>
</div>
<div class="search">
    <form action="/search.php" method="post">
        <input name="search" type="text" placeholder="Поиск по сайту"
               style="border: 1px solid #ccc; padding: 8px; border-radius: 20px; font-size: 16px;" required>
        <button type="submit"
                style="background-color: #f9f9f9; border: 1px solid #ccc; padding: 8px 16px; border-radius: 20px; font-size: 16px; cursor: pointer;">
            Найти
        </button>
    </form>
</div>
<div id="regAuth" onmouseover="showAdminDropdown()" onmouseout="hideAdminDropdown()">
    <li style="list-style-type: none;">
        <div class="admin-panel">
            <a href="../../userPage/userPage.php?userId=<?= $_SESSION['id']; ?>" class="admin-link">
                <?php
                echo mb_substr($_SESSION['login'], 0, 15, 'UTF-8');
                $title_length = mb_strlen($_SESSION['login'], 'UTF-8');
                if ($title_length > 16) {
                    echo '...';
                }
                ?>
            </a>
            <ul class="admin-dropdown">
                <li><a href="http://art-display/admin/posts/index.php">Админ панель</a></li>
                <li><a href="/../../logout.php">Выход</a></li>
            </ul>
        </div>
    </li>
</div>

<script>
    function showAdminDropdown() {
        var adminDropdown = document.querySelector("#regAuth .admin-dropdown");
        if (adminDropdown) {
            adminDropdown.style.display = "block";
        }
    }

    function hideAdminDropdown() {
        var adminDropdown = document.querySelector("#regAuth .admin-dropdown");
        if (adminDropdown) {
            adminDropdown.style.display = "none";
        }
    }
</script>
