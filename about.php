<?php
require_once 'header.php';
?>

<section id="about-page">
    <h1 class="section-title">О нашем магазине</h1>
    
    <div class="about-content">
        <div class="about-section">
            <h2 style="color: #6b5b95; margin-bottom: 20px;">Lu Jewelry</h2>
            <p style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 20px;">
                Добро пожаловать в мир изящества и красоты! <strong>Lu Jewelry</strong> — это магазин украшений ручной работы, 
                где каждая деталь создается с любовью и вниманием к качеству.
            </p>
            
            <p style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 20px;">
                С 2010 года мы создаем уникальные украшения, которые подчеркивают индивидуальность 
                и делают каждый момент особенным. Наша миссия — дарить красоту и уверенность каждой женщине.
            </p>
        </div>
        
        <div class="about-section" style="margin-top: 40px;">
            <h3 style="color: #6b5b95; margin-bottom: 15px;">Наши ценности</h3>
            
            <div style="display: grid; gap: 20px; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-top: 20px;">
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(138, 127, 204, 0.1);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; background: #8a7fcc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h4 style="margin: 0; color: #4a4453;">Качество</h4>
                    </div>
                    <p style="margin: 0; color: #666;">Используем только сертифицированные материалы: серебро 925 пробы, позолоту, натуральные камни</p>
                </div>
                
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(138, 127, 204, 0.1);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; background: #8a7fcc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-hand-sparkles"></i>
                        </div>
                        <h4 style="margin: 0; color: #4a4453;">Ручная работа</h4>
                    </div>
                    <p style="margin: 0; color: #666;">Каждое украшение создается мастерами вручную, что гарантирует уникальность и внимание к деталям</p>
                </div>
                
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(138, 127, 204, 0.1);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; background: #8a7fcc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 style="margin: 0; color: #4a4453;">Экологичность</h4>
                    </div>
                    <p style="margin: 0; color: #666;">Заботимся о природе: используем перерабатываемые материалы и экологичную упаковку</p>
                </div>
            </div>
        </div>
        
        <div class="about-section" style="margin-top: 40px;">
            <h3 style="color: #6b5b95; margin-bottom: 15px;">Почему выбирают нас?</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <span style="position: absolute; left: 0; color: #8a7fcc;">✓</span>
                    <strong>Бесплатная примерка</strong> перед покупкой
                </li>
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <span style="position: absolute; left: 0; color: #8a7fcc;">✓</span>
                    <strong>Гарантия 2 года</strong> на все изделия
                </li>
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <span style="position: absolute; left: 0; color: #8a7fcc;">✓</span>
                    <strong>Быстрая доставка</strong> по всей России (1-3 дня)
                </li>
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <span style="position: absolute; left: 0; color: #8a7fcc;">✓</span>
                    <strong>Индивидуальный подход</strong> к каждому клиенту
                </li>
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <span style="position: absolute; left: 0; color: #8a7fcc;">✓</span>
                    <strong>Сертификаты качества</strong> на все материалы
                </li>
            </ul>
        </div>
        
        <div class="about-section" style="margin-top: 40px; background: #f9f7fe; padding: 30px; border-radius: 15px;">
            <h3 style="color: #6b5b95; margin-bottom: 15px;">Наша команда</h3>
            <p style="margin-bottom: 15px;">
                Наша команда — это опытные ювелиры, дизайнеры и консультанты, которые любят свое дело. 
                Мы постоянно совершенствуем навыки, следим за трендами и создаем украшения, которые будут актуальны всегда.
            </p>
            <p>
                <strong>Основательница:</strong> Диана Фефелова<br>
                <strong>Опыт работы:</strong> Более 12 лет<br>
                <strong>Образование:</strong> Московский политехнический университет
            </p>
        </div>
        
        <div style="margin-top: 40px; text-align: center;">
            <a href="shop.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">
                <i class="fas fa-store"></i> Перейти в магазин
            </a>
            <a href="contact.php" class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1rem; margin-left: 15px;">
                <i class="fas fa-envelope"></i> Связаться с нами
            </a>
        </div>
    </div>
</section>

<?php
require_once 'footer.php';
?>