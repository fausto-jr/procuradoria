<?php

class AuthController {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function login() {
        if (isLoggedIn()) {
            header('Location: index.php');
            exit();
        }
        require_once 'src/views/auth/login.php';
    }
    
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=login');
            exit();
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'];
        
        if (!$email || !$senha) {
            setFlashMessage('error', 'Por favor, preencha todos os campos.');
            header('Location: index.php?route=login');
            exit();
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = TRUE");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_nivel'] = $usuario['nivel_acesso'];
            
            setFlashMessage('success', 'Bem-vindo(a) ' . $usuario['nome'] . '!');
            header('Location: index.php');
            exit();
        }
        
        setFlashMessage('error', 'Email ou senha inválidos.');
        header('Location: index.php?route=login');
        exit();
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php?route=login');
        exit();
    }
    
    public function changePasswordForm() {
        if (!isLoggedIn()) {
            header('Location: index.php?route=login');
            exit();
        }
        
        ob_start();
        require_once 'src/views/auth/change_password.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    public function changePassword() {
        if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit();
        }
        
        $senhaAtual = $_POST['senha_atual'];
        $novaSenha = $_POST['nova_senha'];
        $confirmarSenha = $_POST['confirmar_senha'];
        
        // Validações
        if (!$senhaAtual || !$novaSenha || !$confirmarSenha) {
            setFlashMessage('error', 'Por favor, preencha todos os campos.');
            header('Location: index.php?route=change-password-form');
            exit();
        }
        
        if ($novaSenha !== $confirmarSenha) {
            setFlashMessage('error', 'A nova senha e a confirmação não correspondem.');
            header('Location: index.php?route=change-password-form');
            exit();
        }
        
        // Verifica a senha atual
        $stmt = $this->pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario || !password_verify($senhaAtual, $usuario['senha'])) {
            setFlashMessage('error', 'Senha atual incorreta.');
            header('Location: index.php?route=change-password-form');
            exit();
        }
        
        // Atualiza a senha
        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->execute([$novaSenhaHash, $_SESSION['user_id']]);
        
        setFlashMessage('success', 'Senha alterada com sucesso!');
        header('Location: index.php');
        exit();
    }
}