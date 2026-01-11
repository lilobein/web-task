<?php
require_once 'header.php';
?>

<section id="wishlist">
    <h1 class="section-title">Мой список желаний</h1>
    
    <div id="wishlist-content">
        <div style="text-align: center; padding: 50px;" id="loading">
            <i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: #8a7fcc;"></i>
            <p style="margin-top: 20px;">Загрузка избранного...</p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const container = document.getElementById('wishlist-content');
        
        if (wishlist.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 50px;">
                    <i class="fas fa-heart" style="font-size: 4rem; color: #d1c9ff; margin-bottom: 20px;"></i>
                    <h3 style="color: #6b5b95; margin-bottom: 15px;">Список желаний пуст</h3>
                    <p>Добавляйте понравившиеся товары в избранное, нажав на значок сердца</p>
                    <a href="shop.php" class="btn btn-primary" style="margin-top: 20px; padding: 15px 40px;">
                        <i class="fas fa-store"></i> Перейти в магазин
                    </a>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="products-grid">
                    ${wishlist.map(id => `
                        <div class="product-card">
                            <div style="height: 250px; background: linear-gradient(45deg, #8a7fcc, #d1c9ff); 
                                        display: flex; align-items: center; justify-content: center; border-radius: 20px 20px 0 0;">
                                <i class="fas fa-gem" style="font-size: 4rem; color: white;"></i>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">Избранный товар #${id}</h3>
                                <p class="product-description">Нажмите "Подробнее" для полной информации</p>
                                <div class="product-price">${(id * 1000).toLocaleString('ru-RU')} ₽</div>
                                <div class="product-actions">
                                    <a href="product.php?id=${id}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Подробнее
                                    </a>
                                    <button class="wishlist-btn active" 
                                            onclick="removeFromWishlist(${id}, this)">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }
    }, 500);
});

function removeFromWishlist(productId, button) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    wishlist = wishlist.filter(id => id != productId);
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    

    button.closest('.product-card').style.opacity = '0.5';
    setTimeout(() => {
        button.closest('.product-card').remove();
        

        if (wishlist.length === 0) {
            document.getElementById('wishlist-content').innerHTML = `
                <div style="text-align: center; padding: 50px;">
                    <i class="fas fa-heart" style="font-size: 4rem; color: #d1c9ff; margin-bottom: 20px;"></i>
                    <h3 style="color: #6b5b95; margin-bottom: 15px;">Список желаний пуст</h3>
 
                    <a href="shop.php" class="btn btn-primary" style="margin-top: 20px; padding: 15px 40px;">
                        <i class="fas fa-store"></i> Перейти в магазин
                    </a>
                </div>
            `;
        }
    }, 300);
    
    alert('Товар удален из избранного!');
}
</script>

<?php
require_once 'footer.php';
?>