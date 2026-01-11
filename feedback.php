<?php
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($subject) || empty($message)) {
        header('Location: contact.php?error=empty');
        exit;
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO feedback (user_id, subject, message) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $subject, $message]);
        
        header('Location: contact.php?success=1');
        exit;
    } catch (Exception $e) {
        header('Location: contact.php?error=db');
        exit;
    }
} else {
    header('Location: contact.php');
    exit;
}
?>