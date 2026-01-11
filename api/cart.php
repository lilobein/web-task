<?php
header('Content-Type: application/json');
require_once 'functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!isset($data['action'], $data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Неверные данные']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $product_id = intval($data['product_id']);
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;
    
    switch ($data['action']) {
        case 'add':
            $result = addToCart($user_id, $product_id, $quantity);
            $message = $result ? 'Товар добавлен в корзину' : 'Ошибка';
            break;
            
        case 'remove':
            $result = removeFromCart($user_id, $product_id);
            $message = $result ? 'Товар удален из корзины' : 'Ошибка';
            break;
            
        case 'update':
            $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;
            $result = updateCartQuantity($user_id, $product_id, $quantity);
            $message = $result ? 'Количество обновлено' : 'Ошибка';
            break;
            
        default:
            $result = false;
            $message = 'Неизвестное действие';
    }

    $cart_items = getUserCart($user_id);
    $cart_count = array_sum(array_column($cart_items, 'quantity'));
    
    echo json_encode([
        'success' => $result,
        'message' => $message,
        'cart_count' => $cart_count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
}
?>