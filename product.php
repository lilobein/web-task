<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    header('Location: shop.php');
    exit;
}

$product_id = intval($_GET['id']);
$product = getProductById($product_id);

if (!$product) {
    echo '<div class="container"><p style="text-align: center; padding: 50px;">Товар не найден</p></div>';
    require_once 'footer.php';
    exit;
}
?>
<section id="product-detail">
    <div class="product-detail">
        <div class="product-detail-image-container">
            <img src="assets/poducts/<?php echo $product['image_url']; ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="product-detail-image">
        </div>
        
        <div class="product-detail-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="product-meta">
                <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <span class="product-stock <?php echo $product['stock'] > 10 ? 'in-stock' : 'low-stock'; ?>">
                    <?php echo $product['stock'] > 10 ? 'В наличии' : 'Осталось мало'; ?>
                </span>
            </div>
            
            <div class="product-price" style="font-size: 2.5rem; margin: 20px 0;">
                <?php echo number_format($product['price'], 0, ',', ' '); ?> ₽
            </div>
            
            <div class="product-description-full">
                <h3>Описание</h3>
                <p><?php echo nl2br(htmlspecialchars($product['full_description'])); ?></p>
            </div>
            
            <div class="product-specs">
                <h3>Характеристики</h3>
                <div class="spec-item">
                    <span class="spec-label">Материал:</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['material']); ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Цвет:</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['color']); ?></span>
                </div>
                <?php if ($product['size']): ?>
                <div class="spec-item">
                    <span class="spec-label">Размер:</span>
                    <span class="spec-value"><?php echo htmlspecialchars($product['size']); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($product['weight']): ?>
                <div class="spec-item">
                    <span class="spec-label">Вес:</span>
                    <span class="spec-value"><?php echo $product['weight']; ?> г</span>
                </div>
                <?php endif; ?>
                <div class="spec-item">
                    <span class="spec-label">В наличии:</span>
                    <span class="spec-value"><?php echo $product['stock']; ?> шт.</span>
                </div>
            </div>
            
            <div class="product-actions" style="margin-top: 30px; display: flex; gap: 15px; align-items: center;">
                <?php if (isLoggedIn()): ?>
                <button class="wishlist-btn <?php echo isInWishlist($_SESSION['user_id'], $product['id']) ? 'active' : ''; ?>" 
                        data-product-id="<?php echo $product['id']; ?>" 
                        style="width: 50px; height: 50px; font-size: 1.2rem; border-radius: 50%; border: none; cursor: pointer; background-color: <?php echo isInWishlist($_SESSION['user_id'], $product['id']) ? '#ff6b6b' : '#f0eeff'; ?>; color: <?php echo isInWishlist($_SESSION['user_id'], $product['id']) ? 'white' : '#8a7fcc'; ?>;">
                    <i class="fas fa-heart"></i>
                </button>
                <?php endif; ?>
                

                <a href="shop.php?category=<?php echo $product['category_id']; ?>" 
                   class="btn btn-secondary" 
                   style="padding: 15px 30px; text-decoration: none;">
                   <i class="fas fa-arrow-left"></i> Назад в магазин
                </a>
                
                <?php if (isLoggedIn()): ?>
                <form method="POST" action="cart.php" style="display: inline;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary" style="padding: 15px 40px; border: none; cursor: pointer;">
                        <i class="fas fa-cart-plus"></i> Добавить в корзину
                    </button>
                </form>
                <?php else: ?>

                <a href="login.php" class="btn btn-primary" style="padding: 15px 40px; text-decoration: none;">
                    <i class="fas fa-cart-plus"></i> Войдите, чтобы добавить в корзину
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section id="related-products" style="margin-top: 60px;">
    <h2 class="section-title">Похожие товары</h2>
    <div class="products-grid">
        <?php
        $related_products = getProducts($product['category_id'], 4);
        $related_count = 0;
        foreach ($related_products as $related):
            if ($related['id'] == $product['id']) continue;
            if ($related_count >= 3) break;
            $related_count++;
        ?>
        <div class="product-card">
            <img src="assets/poducts/<?php echo $related['image_url']; ?>" 
                 alt="<?php echo htmlspecialchars($related['name']); ?>" 
                 class="product-image">
            <div class="product-info">
                <div class="product-category"><?php echo htmlspecialchars($related['category_name']); ?></div>
                <h3 class="product-name"><?php echo htmlspecialchars($related['name']); ?></h3>
                <p class="product-description"><?php echo substr(htmlspecialchars($related['description']), 0, 100); ?>...</p>
                <div class="product-price"><?php echo number_format($related['price'], 0, ',', ' '); ?> ₽</div>
                <div class="product-actions">
                    <a href="product.php?id=<?php echo $related['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Подробнее
                    </a>
                    
                    <?php if (isLoggedIn()): ?>

                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $related['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em;">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                    
    
                    <button class="wishlist-btn <?php echo isInWishlist($_SESSION['user_id'], $related['id']) ? 'active' : ''; ?>" 
                            data-product-id="<?php echo $related['id']; ?>"
                            style="width: 35px; height: 35px; border-radius: 50%; border: none; cursor: pointer; background-color: <?php echo isInWishlist($_SESSION['user_id'], $related['id']) ? '#ff6b6b' : '#f0eeff'; ?>; color: <?php echo isInWishlist($_SESSION['user_id'], $related['id']) ? 'white' : '#8a7fcc'; ?>;">
                        <i class="fas fa-heart"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if ($related_count == 0): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p style="color: #666; font-size: 1.1rem;">Нет похожих товаров</p>
            <a href="shop.php" class="btn btn-secondary" style="margin-top: 20px;">
                <i class="fas fa-store"></i> Перейти в магазин
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>

document.addEventListener('DOMContentLoaded', function() {

    const wishlistBtn = document.querySelector('.product-actions .wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isActive = this.classList.contains('active');

            let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
            
            if (isActive) {

                wishlist = wishlist.filter(id => id != productId);
                this.classList.remove('active');
                this.style.backgroundColor = '#f0eeff';
                this.style.color = '#8a7fcc';
                alert('Удалено из избранного');
            } else {

                if (!wishlist.includes(parseInt(productId))) {
                    wishlist.push(parseInt(productId));
                }
                this.classList.add('active');
                this.style.backgroundColor = '#ff6b6b';
                this.style.color = 'white';
                alert('Добавлено в избранное');
            }
            

            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        });
    }
    

    document.querySelectorAll('#related-products .wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isActive = this.classList.contains('active');
            
            let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
            
            if (isActive) {
                wishlist = wishlist.filter(id => id != productId);
                this.classList.remove('active');
                this.style.backgroundColor = '#f0eeff';
                this.style.color = '#8a7fcc';
                alert('Удалено из избранного');
            } else {
                if (!wishlist.includes(parseInt(productId))) {
                    wishlist.push(parseInt(productId));
                }
                this.classList.add('active');
                this.style.backgroundColor = '#ff6b6b';
                this.style.color = 'white';
                alert('Добавлено в избранное');
            }
            
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        });
    });
});
</script>

<?php
require_once 'footer.php';
?>