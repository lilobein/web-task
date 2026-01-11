<?php
require_once 'functions.php';
$user = getUserInfo();

$cart_count = 0;
if (isLoggedIn()) {
    $cart_items = getUserCart($_SESSION['user_id']);
    $cart_count = array_sum(array_column($cart_items, 'quantity'));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lu Jewelry - Украшения ручной работы</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">

            <nav class="navbar">
                <a href="index.php" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <span class="logo-text">Lu Jewelry</span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Главная</a></li>
                    <li><a href="shop.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">Магазин</a></li>
                    <li><a href="about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">О нас</a></li>
                    <li><a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Контакты</a></li>
                    
                    <?php if (isLoggedIn()): ?>
                    <li><a href="wishlist.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'wishlist.php' ? 'active' : ''; ?>">Избранное</a></li>
                    <li><a href="cart.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">Корзина</a></li>
                    <li><a href="feedback.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">Обратная связь</a></li>
                    <?php endif; ?>
                </ul>
                
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <a href="cart.php" class="cart-icon" style="position: relative; margin-right: 15px; text-decoration: none; color: #6b5b95; font-size: 1.3rem;">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cart_count > 0): ?>
                            <span style="
                                position: absolute;
                                top: -5px;
                                right: -8px;
                                background: #ff6b6b;
                                color: white;
                                border-radius: 50%;
                                width: 18px;
                                height: 18px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 0.7rem;
                                font-weight: bold;
                            ">
                                <?php echo $cart_count; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        
                        <div class="user-menu">
                            <span class="user-greeting">Привет, <?php echo htmlspecialchars($user['username']); ?>!</span>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="auth-btn login-btn">Войти</a>
                        <a href="register.php" class="auth-btn register-btn">Регистрация</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">