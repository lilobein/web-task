<?php
require_once 'header.php';
?>
<section id="hero">
    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="/assets/images/slide1.jpg" alt="Коллекция украшений">
                <div class="slide-content">
                    <h3>Новая коллекция "Лунный свет"</h3>
                    <p>Уникальные украшения из серебра с натуральными камнями</p>
                </div>
            </div>
            <div class="slide">
                <img src="/assets/images/slide2.jpg" alt="Серьги ручной работы">
                <div class="slide-content">
                    <h3>Серьги ручной работы</h3>
                    <p>Каждое украшение создается индивидуально</p>
                </div>
            </div>
            <div class="slide">
                <img src="/assets/images/slide3.jpg" alt="Скидки и акции">
                <div class="slide-content">
                    <h3>Скидка 20% на первую покупку</h3>
                    <p>Зарегистрируйтесь и получите специальное предложение</p>
                </div>
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-btn active"></button>
            <button class="slider-btn"></button>
            <button class="slider-btn"></button>
        </div>
    </div>
</section>

<section id="about">
    <h2 class="section-title">О нашем магазине</h2>
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <p style="font-size: 1.2rem; line-height: 1.8; margin-bottom: 30px;">
            Добро пожаловать в мир изящества и красоты! Lu Jewelry — это магазин украшений ручной работы, 
            где каждая деталь создается с любовью и вниманием к качеству.
        </p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 40px;">
            <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(138, 127, 204, 0.1);">
                <i class="fas fa-gem" style="font-size: 3rem; color: #8a7fcc; margin-bottom: 20px;"></i>
                <h3 style="color: #6b5b95; margin-bottom: 15px;">Качество</h3>
                <p>Используем только сертифицированные материалы: серебро 925 пробы, позолоту, натуральные камни</p>
            </div>
            <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(138, 127, 204, 0.1);">
                <i class="fas fa-hand-sparkles" style="font-size: 3rem; color: #8a7fcc; margin-bottom: 20px;"></i>
                <h3 style="color: #6b5b95; margin-bottom: 15px;">Ручная работа</h3>
                <p>Каждое украшение создается мастерами вручную, что гарантирует уникальность</p>
            </div>
            <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(138, 127, 204, 0.1);">
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #8a7fcc; margin-bottom: 20px;"></i>
                <h3 style="color: #6b5b95; margin-bottom: 15px;">Доставка</h3>
                <p>Быстрая доставка по всей России. Примерка перед покупкой</p>
            </div>
        </div>
    </div>
</section>


<section id="contact-preview" style="margin-top: 80px; text-align: center;">
    <h2 class="section-title">Есть вопросы?</h2>
    <p style="font-size: 1.2rem; margin-bottom: 30px;">Наша команда всегда готова помочь вам с выбором</p>
    <a href="contact.php" class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1rem;">
        <i class="fas fa-envelope"></i> Связаться с нами
    </a>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function getWishlist() {
        const wishlistJson = localStorage.getItem('wishlist');
        if (!wishlistJson) return [];
        
        try {
            return JSON.parse(wishlistJson);
        } catch (e) {
            console.error('Error parsing wishlist:', e);
            return [];
        }
    }
    

    function saveWishlist(wishlist) {
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        
        document.cookie = `wishlist=${JSON.stringify(wishlist)}; path=/; max-age=31536000`;
    }
    
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId);
            const isActive = this.classList.contains('active');
            
            let wishlist = getWishlist();
            
            if (isActive) {
                wishlist = wishlist.filter(id => id !== productId);
                this.classList.remove('active');
                this.style.backgroundColor = '#f0eeff';
                this.style.color = '#8a7fcc';
            } else {
                if (!wishlist.includes(productId)) {
                    wishlist.push(productId);
                }
                this.classList.add('active');
                this.style.backgroundColor = '#ff6b6b';
                this.style.color = 'white';
            }
            

            saveWishlist(wishlist);
            console.log('Wishlist updated:', wishlist);
        });
    });
    

    function showNotification(message, type) {

        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            background-color: ${type === 'success' ? '#4caf50' : '#2196f3'};
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        

        if (!document.querySelector('#notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(notification);
        

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    function updateWishlistButtons() {
        const wishlist = getWishlist();
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            const productId = parseInt(btn.dataset.productId);
            if (wishlist.includes(productId)) {
                btn.classList.add('active');
                btn.style.backgroundColor = '#ff6b6b';
                btn.style.color = 'white';
            } else {
                btn.classList.remove('active');
                btn.style.backgroundColor = '#f0eeff';
                btn.style.color = '#8a7fcc';
            }
        });
    }
    

    updateWishlistButtons();
    

    window.addEventListener('focus', updateWishlistButtons);
    

    document.querySelectorAll('form[action="cart.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Добавить товар в корзину?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<?php
require_once 'footer.php';
?>