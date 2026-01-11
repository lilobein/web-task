<?php
require_once 'header.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}
?>
<section id="auth">
    <div class="auth-container">
        <h2 style="text-align: center; color: #6b5b95; margin-bottom: 30px;">Вход в аккаунт</h2>
        
        <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form id="login-form" class="auth-form" method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Имя пользователя или Email</label>
                <input type="text" id="username" name="username" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">
                <i class="fas fa-sign-in-alt"></i> Войти
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p>Нет аккаунта? <a href="register.php" style="color: #8a7fcc; text-decoration: none; font-weight: 600;">Зарегистрироваться</a></p>
        </div>
    </div>
</section>
<?php
require_once 'footer.php';
?>