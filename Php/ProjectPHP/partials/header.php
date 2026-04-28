<header>
    <a href="index.php" class="logo-wrap" style="text-decoration: none; cursor: pointer;">
        <img src="assets/img/Logo.svg" class="logo-svg" alt="Везет">
    </a>
    
    <form method="GET" action="index.php" style="grid-column: 3; display: contents;">
        <input type="text" name="search" class="search" placeholder="Искать в Везет" value="<?= escape($_GET['search'] ?? '') ?>">
    </form>
    
    <div class="log-container">
        <?php if (isLoggedIn()): ?>
            <button class="log-button" onclick="window.location.href='settings.php'">
                <img src="assets/img/login-user-photo.svg" class="log-in-photo" alt="">
                <?= escape($_SESSION['user_name'] ?? 'Пользователь') ?>
            </button>
        <?php else: ?>
            <button class="log-button" onclick="window.location.href='login.php'">
                <img src="assets/img/login-user-photo.svg" class="log-in-photo" alt="">
                Войти
            </button>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <button class="help-button" onclick="window.location.href='admin.php'" title="Админ-панель">
                <img src="assets/img/help.svg" alt="Админ">
            </button>
        <?php else: ?>
            <button class="help-button">
                <img src="assets/img/help.svg" alt="Помощь">
            </button>
        <?php endif; ?>
    </div>
</header>
