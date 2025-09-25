<?php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['nivel_acesso'] === 'admin';
}

function checkPermission($requiredLevel = 'advogado') {
    if (!isLoggedIn()) {
        header('Location: index.php?route=login');
        exit();
    }
    
    if ($requiredLevel === 'admin' && !isAdmin()) {
        setFlashMessage('error', 'Acesso negado. Você não tem permissão para acessar este recurso.');
        header('Location: index.php');
        exit();
    }
}