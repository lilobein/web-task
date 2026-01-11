<?php
require_once 'database.php';

session_start();



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!class_exists('Database')) {
    require_once __DIR__ . 'database.php';
}



function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function getUserInfo() {
    if (!isLoggedIn()) return null;
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}


function getCategories() {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}


function getProducts($category_id = null, $limit = null) {
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT p.*, c.name as category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = true";
    
    $params = [];
    
    if ($category_id) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


function getProductById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                         LEFT JOIN categories c ON p.category_id = c.id 
                         WHERE p.id = ? AND p.is_active = true");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


function addToWishlist($user_id, $product_id) {
    $db = Database::getInstance()->getConnection();
    try {
        $stmt = $db->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $product_id]);
    } catch(PDOException $e) {
        return false;
    }
}


function removeFromWishlist($user_id, $product_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$user_id, $product_id]);
}


function getUserWishlist($user_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT p.* FROM products p 
                         JOIN wishlist w ON p.id = w.product_id 
                         WHERE w.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}


function isInWishlist($user_id, $product_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    return $stmt->fetchColumn() > 0;
}


function addToCart($user_id, $product_id, $quantity = 1) {
    $db = Database::getInstance()->getConnection();
    try {
        // Проверяем, есть ли уже товар в корзине
        $check = $db->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $check->execute([$user_id, $product_id]);
        $existing = $check->fetch();
        
        if ($existing) {
            // Обновляем количество
            $stmt = $db->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            return $stmt->execute([$quantity, $existing['id']]);
        } else {
            // Добавляем новый товар
            $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$user_id, $product_id, $quantity]);
        }
    } catch(PDOException $e) {
        error_log("Cart error: " . $e->getMessage());
        return false;
    }
}

function removeFromCart($user_id, $product_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$user_id, $product_id]);
}

function updateCartQuantity($user_id, $product_id, $quantity) {
    $db = Database::getInstance()->getConnection();
    if ($quantity <= 0) {
        return removeFromCart($user_id, $product_id);
    }
    
    $stmt = $db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$quantity, $user_id, $product_id]);
}

function getUserCart($user_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT c.*, p.name, p.price, p.image_url, p.stock 
                         FROM cart c 
                         JOIN products p ON c.product_id = p.id 
                         WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getCartTotal($user_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT SUM(c.quantity * p.price) as total 
                         FROM cart c 
                         JOIN products p ON c.product_id = p.id 
                         WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

function clearCart($user_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ?");
    return $stmt->execute([$user_id]);
}

function isInCart($user_id, $product_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    return $stmt->fetchColumn() > 0;
}

?>