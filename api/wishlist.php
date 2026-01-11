<?php
// Минимальная рабочая версия API

// 1. Установить заголовок ДО всего вывода
header('Content-Type: application/json');

// 2. Проверить, нет ли лишнего вывода ДО этого заголовка
ob_start(); // Буферизируем вывод

try {
    // 3. Проверить метод
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }
    
    // 4. Получить данные
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid JSON data');
    }
    
    // 5. Проверить авторизацию (упрощенно)
    session_start();
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Not authorized');
    }
    
    // 6. Имитировать успешный ответ
    $response = [
        'success' => true,
        'message' => 'Success from simplified API',
        'action' => $data['action'] ?? 'unknown',
        'product_id' => $data['product_id'] ?? 0,
        'user_id' => $_SESSION['user_id'] ?? 0,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ];
}


ob_end_clean();
echo json_encode($response);
exit;
?>