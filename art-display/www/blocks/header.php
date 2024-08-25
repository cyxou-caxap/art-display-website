<div id="logo">
    <a href="/" title="Перейти на главную"><h1><?= $title ?></h1></a>
</div>
<div id="menuHead">
    <nav>
        <ul>
            <a href="../favorites.php">Избранное</a> |
            <a href="../index.php?best_posts=1">Топ-10 публикаций</a> |
            <a href="../../category/index-categories.php">Категории</a>
            <a href="#"></a>
        </ul>
    </nav>
</div>
<div class="search">
    <form action="/search.php" method="post" onsubmit="return validateSearch()">
        <input id="searchInput" name="search" type="text" placeholder="Поиск по сайту"
               style="border: 1px solid #ccc; padding: 8px; border-radius: 20px; font-size: 16px;" required>
        <button type="submit"
                style="background-color: #f9f9f9; border: 1px solid #ccc; padding: 8px 16px; border-radius: 20px; font-size: 16px; cursor: pointer;">
            Найти
        </button>
    </form>
</div>

<div id="regAuth" onmouseover="showDropdown()" onmouseout="hideDropdown()">
    <div class="user-panel">
        <?php if (isset($_SESSION['id'])) : ?>
            <div class="user-link-container">
                <a href="../userPage/userPage.php?userId=<?= $_SESSION['id']; ?>" class="user-link">
                    <?php
                    echo mb_substr($_SESSION['login'], 0, 15, 'UTF-8');
                    $title_length = mb_strlen($_SESSION['login'], 'UTF-8');
                    if ($title_length > 16) {
                        echo '...';
                    }
                    ?>
                </a>
                <ul class="user-dropdown" id="userDropdown" onmouseover="showDropdown()" onmouseout="hideDropdown()">
                    <?php if ($_SESSION['role'] === "0") : ?>
                        <li><a href="../admin/posts/index.php">Админ панель</a></li>
                    <?php endif; ?>
                    <li><a href="../logout.php">Выход</a></li>
                </ul>
            </div>
        <?php else : ?>
            <a href="https://art-display/authorization.php">Вход</a> |
            <a href="https://art-display/registration.php">Регистрация</a>
        <?php endif; ?>

    </div>

    <script>
        function showDropdown() {
            var dropdown = document.getElementById("userDropdown");
            if (dropdown) {
                dropdown.style.display = "block";
            }
        }

        function hideDropdown() {
            var dropdown = document.getElementById("userDropdown");
            if (dropdown) {
                dropdown.style.display = "none";
            }
        }
    </script>

    <script>
        function validateSearch() {
            var searchInput = document.getElementById('searchInput').value;
            if (searchInput.trim() === '') {
                return false; // Если строка содержит только пробелы, предотвращаем отправку формы
            }
            return true; // В противном случае разрешаем отправку формы
        }
    </script>
</div>

</div>