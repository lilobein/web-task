<?php
require_once 'header.php';

$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
$products = getProducts($category_id);
$categories = getCategories();
?>
<section id="shop-header">
    <h1 class="section-title">Магазин украшений</h1>
    <p style="text-align: center; margin-bottom: 30px; font-size: 1.1rem;">Найдите свое идеальное украшение</p>
    
    <div class="view-toggle">
        <button class="toggle-btn active" data-view="grid">
            <i class="fas fa-th-large"></i> Сетка
        </button>
        <button class="toggle-btn" data-view="table">
            <i class="fas fa-table"></i> Таблица
        </button>
    </div>
</section>

<section id="categories" style="margin-bottom: 40px;">
    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 15px;">
        <a href="shop.php" 
           class="btn <?php echo !$category_id ? 'btn-primary' : 'btn-secondary'; ?>" 
           style="padding: 10px 25px;">
           Все товары
        </a>
        <?php foreach ($categories as $cat): ?>
        <a href="shop.php?category=<?php echo $cat['id']; ?>" 
           class="btn <?php echo $category_id == $cat['id'] ? 'btn-primary' : 'btn-secondary'; ?>" 
           style="padding: 10px 25px;">
           <?php echo htmlspecialchars($cat['name']); ?>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<section id="products-grid-view">
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="assets/poducts/<?php echo $product['image_url']; ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="product-image">
            <div class="product-info">
                <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="product-price"><?php echo number_format($product['price'], 0, ',', ' '); ?> ₽</div>
                <div class="product-stock <?php echo $product['stock'] > 10 ? 'in-stock' : 'low-stock'; ?>">
                    <?php echo $product['stock'] > 10 ? 'В наличии' : 'Мало осталось'; ?>
                </div>
                <div class="product-actions">
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Подробнее
                    </a>
                    
                    <?php if (isLoggedIn()): ?>
                    <!-- Кнопка "В корзину" -->
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-cart-plus"></i> В корзину
                        </button>
                    </form>
                    
                    <!-- Кнопка избранного -->
                    <button class="wishlist-btn <?php echo isInWishlist($_SESSION['user_id'], $product['id']) ? 'active' : ''; ?>" 
                            data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="products-table-view" style="display: none;">
    <table class="products-table">
        <thead>
            <tr>
                <th>Фото</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Наличие</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td>
                    <img src="assets/poducts/<?php echo $product['image_url']; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="table-image">
                </td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td style="font-weight: bold; color: #6b5b95;">
                    <?php echo number_format($product['price'], 0, ',', ' '); ?> ₽
                </td>
                <td>
                    <span class="product-stock <?php echo $product['stock'] > 10 ? 'in-stock' : 'low-stock'; ?>" 
                          style="display: inline-block;">
                        <?php echo $product['stock']; ?> шт.
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="padding: 8px 15px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        <?php if (isLoggedIn()): ?>
                        <!-- Кнопка "В корзину" для табличного вида -->
                        <form method="POST" action="cart.php" style="display: inline;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em;">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                        
                        <!-- Кнопка избранного для табличного вида -->
                        <button class="wishlist-btn <?php echo isInWishlist($_SESSION['user_id'], $product['id']) ? 'active' : ''; ?>" 
                                data-product-id="<?php echo $product['id']; ?>"
                                style="width: 35px; height: 35px;">
                            <i class="fas fa-heart"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    const gridView = document.getElementById('products-grid-view');
    const tableView = document.getElementById('products-table-view');
    
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            toggleButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const view = this.dataset.view;
            if (view === 'table') {
                gridView.style.display = 'none';
                tableView.style.display = 'block';
            } else {
                gridView.style.display = 'block';
                tableView.style.display = 'none';
            }
        });
    });
});
</script>

<?php
require_once 'footer.php';
?>