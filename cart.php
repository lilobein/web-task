<?php
require_once 'header.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Упрощенная логика с сессией вместо БД
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Обработка действий с корзиной
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (isset($_POST['product_id'], $_POST['quantity'])) {
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];
                    
                    if (isset($_SESSION['cart'][$product_id])) {
                        $_SESSION['cart'][$product_id] += $quantity;
                    } else {
                        $_SESSION['cart'][$product_id] = $quantity;
                    }
                }
                break;
                
            case 'update':
                if (isset($_POST['product_id'], $_POST['quantity'])) {
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];
                    
                    if ($quantity <= 0) {
                        unset($_SESSION['cart'][$product_id]);
                    } else {
                        $_SESSION['cart'][$product_id] = $quantity;
                    }
                }
                break;
                
            case 'remove':
                if (isset($_POST['product_id'])) {
                    unset($_SESSION['cart'][$_POST['product_id']]);
                }
                break;
                
            case 'clear':
                $_SESSION['cart'] = [];
                break;
        }
        
        header('Location: cart.php');
        exit;
    }
}

// Получаем информацию о товарах в корзине
$cart_items = [];
$cart_total = 0;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = getProductById($product_id);
    if ($product) {
        $product['quantity'] = $quantity;
        $product['product_id'] = $product_id;
        $cart_items[] = $product;
        $cart_total += $product['price'] * $quantity;
    }
}
?>

<section id="cart">
    <h1 class="section-title">Корзина покупок</h1>
    
    <?php if (empty($cart_items)): ?>
    <div style="text-align: center; padding: 50px;">
        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #d1c9ff; margin-bottom: 20px;"></i>
        <h3 style="color: #6b5b95; margin-bottom: 15px;">Корзина пуста</h3>
        <p>Добавьте товары из каталога в корзину</p>
        <a href="shop.php" class="btn btn-primary" style="margin-top: 20px; padding: 15px 40px;">
            <i class="fas fa-store"></i> Перейти в магазин
        </a>
    </div>
    <?php else: ?>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(138, 127, 204, 0.15);">
            <thead>
                <tr style="background: linear-gradient(45deg, #8a7fcc, #d1c9ff); color: white;">
                    <th style="padding: 20px; text-align: left;">Товар</th>
                    <th style="padding: 20px; text-align: center;">Цена</th>
                    <th style="padding: 20px; text-align: center;">Количество</th>
                    <th style="padding: 20px; text-align: center;">Итого</th>
                    <th style="padding: 20px; text-align: center;">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr style="border-bottom: 1px solid #f0eeff;">
                    <td style="padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <img src="assets/poducts/<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
                            <div>
                                <h4 style="margin: 0; color: #4a4453;"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <?php if ($item['stock'] < $item['quantity']): ?>
                                <p style="color: #ff6b6b; margin: 5px 0 0 0; font-size: 0.9em;">
                                    <i class="fas fa-exclamation-triangle"></i> Недостаточно на складе
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    
                    <td style="padding: 20px; text-align: center; color: #6b5b95; font-weight: 600;">
                        <?php echo number_format($item['price'], 0, ',', ' '); ?> ₽
                    </td>
                    
                    <td style="padding: 20px; text-align: center;">
                        <form method="POST" action="" style="display: inline-block;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <div style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                                <button type="button" onclick="this.parentNode.querySelector('input').stepDown(); this.parentNode.parentNode.submit();"
                                        style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #8a7fcc; background: white; color: #8a7fcc; font-weight: bold; cursor: pointer;">-</button>
                                
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="<?php echo min($item['stock'], 99); ?>"
                                       style="width: 60px; text-align: center; padding: 8px; border: 2px solid #d1c9ff; border-radius: 10px; font-size: 1em;">
                                
                                <button type="button" onclick="this.parentNode.querySelector('input').stepUp(); this.parentNode.parentNode.submit();"
                                        style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #8a7fcc; background: white; color: #8a7fcc; font-weight: bold; cursor: pointer;">+</button>
                            </div>
                        </form>
                    </td>
                    
                    <td style="padding: 20px; text-align: center; color: #8a7fcc; font-weight: 700; font-size: 1.2em;">
                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> ₽
                    </td>
                    
                    <td style="padding: 20px; text-align: center;">
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" style="background: #ff6b6b; color: white; border: none; border-radius: 10px; padding: 10px 20px; cursor: pointer; font-weight: 600;">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(138, 127, 204, 0.15);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #6b5b95; margin-bottom: 10px;">Итого к оплате:</h3>
                <p style="color: #666; font-size: 0.9em;">Всего товаров: <?php 
                    $total_items = 0;
                    foreach ($cart_items as $item) {
                        $total_items += $item['quantity'];
                    }
                    echo $total_items;
                ?> шт.</p>
            </div>
            
            <div style="text-align: right;">
                <div style="font-size: 2.5rem; font-weight: 700; color: #8a7fcc; margin-bottom: 10px;">
                    <?php echo number_format($cart_total, 0, ',', ' '); ?> ₽
                </div>
                
                <div style="display: flex; gap: 20px; margin-top: 20px;">
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" style="padding: 15px 30px; background: #f8f9fa; color: #666; border: 2px solid #ddd; border-radius: 50px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-trash"></i> Очистить корзину
                        </button>
                    </form>
                    
                    <button onclick="checkout()" style="padding: 15px 40px; background: linear-gradient(45deg, #8a7fcc, #d1c9ff); color: white; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; font-size: 1.1em;">
                        <i class="fas fa-credit-card"></i> Оформить заказ
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 40px; display: flex; gap: 20px;">
        <a href="shop.php" style="display: inline-block; padding: 15px 30px; background: #f0eeff; color: #8a7fcc; text-decoration: none; border-radius: 50px; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Продолжить покупки
        </a>
    </div>
    
    <?php endif; ?>
</section>

<script>
function checkout() {
    const total = <?php echo $cart_total; ?>;
    
    if (confirm(`Оформить заказ на сумму ${total.toLocaleString('ru-RU')} ₽?`)) {
        alert('Заказ успешно оформлен! Мы свяжемся с вами для подтверждения.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>

<?php
require_once 'footer.php';
?>