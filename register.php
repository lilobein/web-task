<?php
require_once 'header.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($username) < 3) {
        $error = 'Имя пользователя должно быть не менее 3 символов';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный email';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } else {
        $db = Database::getInstance()->getConnection();
        

        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetchColumn() > 0) {
            $error = 'Пользователь с таким именем или email уже существует';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success = 'Регистрация успешна! Теперь вы можете войти.';
            } else {
                $error = 'Ошибка при регистрации. Попробуйте позже.';
            }
        }
    }
}
?>
<section id="auth">
    <div class="auth-container">
        <h2 style="text-align: center; color: #6b5b95; margin-bottom: 30px;">Регистрация</h2>
        
        <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form id="register-form" class="auth-form" method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Имя пользователя</label>
                <input type="text" id="username" name="username" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Подтверждение пароля</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">
                <i class="fas fa-user-plus"></i> Зарегистрироваться
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p>Уже есть аккаунт? <a href="login.php" style="color: #8a7fcc; text-decoration: none; font-weight: 600;">Войти</a></p>
        </div>
    </div>
</section>
<?php
require_once 'footer.php';
?>