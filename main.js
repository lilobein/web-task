class Slider {
    constructor() {
        this.slides = document.querySelectorAll('.slide');
        this.buttons = document.querySelectorAll('.slider-btn');
        this.currentSlide = 0;
        this.interval = null;
        this.init();
    }
    
    init() {
        if (this.slides.length === 0) return;
        
        this.startAutoSlide();
        this.setupControls();
    }
    
    startAutoSlide() {
        this.interval = setInterval(() => {
            this.nextSlide();
        }, 5000);
    }
    
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateSlider();
    }
    
    goToSlide(index) {
        this.currentSlide = index;
        this.updateSlider();
        this.resetAutoSlide();
    }
    
    updateSlider() {
        const offset = -this.currentSlide * 100;
        document.querySelector('.slider').style.transform = `translateX(${offset}%)`;
        
        this.buttons.forEach((btn, index) => {
            btn.classList.toggle('active', index === this.currentSlide);
        });
    }
    
    setupControls() {
        this.buttons.forEach((btn, index) => {
            btn.addEventListener('click', () => this.goToSlide(index));
        });
    }
    
    resetAutoSlide() {
        clearInterval(this.interval);
        this.startAutoSlide();
    }
}

class ViewToggle {
    constructor() {
        this.toggleButtons = document.querySelectorAll('.toggle-btn');
        this.tableView = document.getElementById('products-table-view');
        this.gridView = document.getElementById('products-grid-view');
        this.init();
    }
    
    init() {
        if (!this.toggleButtons.length) return;
        
        this.toggleButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleView(e.target));
        });
        
        const savedView = localStorage.getItem('productsView') || 'grid';
        this.setView(savedView);
    }
    
    toggleView(button) {
        const view = button.dataset.view;
        this.setView(view);
    }
    
    setView(view) {
        this.toggleButtons.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });
        
        if (view === 'table') {
            this.tableView.style.display = 'block';
            this.gridView.style.display = 'none';
        } else {
            this.tableView.style.display = 'none';
            this.gridView.style.display = 'block';
        }
        
        localStorage.setItem('productsView', view);
    }
}


class WishlistManager {
    constructor() {
        this.buttons = document.querySelectorAll('.wishlist-btn');
        this.init();
    }
    
    init() {
        this.buttons.forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleWishlist(e.target));
        });
    }
    
    toggleWishlist(button) {
        const productId = button.dataset.productId;
        const isActive = button.classList.contains('active');
        
        console.log('Toggling wishlist:', { productId, isActive });
        
        fetch('api/wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: isActive ? 'remove' : 'add',
                product_id: productId
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
     
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    console.error('Expected JSON but got:', text);
                    throw new Error('Server returned non-JSON response');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                button.classList.toggle('active');
                this.showNotification(
                    isActive ? 'Удалено из избранного' : 'Добавлено в избранное',
                    isActive ? 'info' : 'success'
                );
            } else {
                this.showNotification(data.message || 'Ошибка', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            this.showNotification('Ошибка соединения: ' + error.message, 'error');
        });
    }
        
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
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
            animation: slideIn 0.3s ease;
        `;
        
        if (type === 'success') {
            notification.style.backgroundColor = '#4caf50';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#f44336';
        } else {
            notification.style.backgroundColor = '#2196f3';
        }
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}


class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        if (!this.form) return;
        
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', (e) => this.validate(e));
    }
    
    validate(e) {
        const inputs = this.form.querySelectorAll('[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showError(input, 'Это поле обязательно для заполнения');
                isValid = false;
            } else if (input.type === 'email') {
                if (!this.isValidEmail(input.value)) {
                    this.showError(input, 'Введите корректный email');
                    isValid = false;
                }
            } else if (input.type === 'password') {
                if (input.value.length < 6) {
                    this.showError(input, 'Пароль должен быть не менее 6 символов');
                    isValid = false;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    }
    
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            color: #f44336;
            font-size: 0.9rem;
            margin-top: 5px;
        `;
        
        const parent = input.parentElement;
        parent.appendChild(errorDiv);
        
        input.style.borderColor = '#f44336';
        
        setTimeout(() => {
            errorDiv.remove();
            input.style.borderColor = '';
        }, 5000);
    }
}


document.addEventListener('DOMContentLoaded', function() {
    new Slider();
    
    new ViewToggle();
    

    new WishlistManager();
    

    new FormValidator('login-form');
    new FormValidator('register-form');
    new FormValidator('feedback-form');

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});


function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.product-card, .section-title').forEach(el => {
        observer.observe(el);
    });
}


window.addEventListener('load', initScrollAnimations);