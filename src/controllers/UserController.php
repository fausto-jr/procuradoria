<?php

class UserController {
   
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        checkPermission('admin');
    }
    
    public function index() {
        $sql = "SELECT * FROM usuarios ORDER BY nome";
        $stmt = $this->pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        ob_start();
        require_once 'src/views/usuarios/index.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->validateUserData($_POST, 'create');
                
                $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso, ativo) VALUES (?, ?, ?, ?, ?)";;
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $_POST['nome'],
                    $_POST['email'],
                    password_hash($_POST['senha'], PASSWORD_DEFAULT),
                    $_POST['nivel_acesso'],
                    isset($_POST['ativo']) ? 1 : 0
                ]);
                
                setFlashMessage('success', 'Usuário cadastrado com sucesso!');
                header('Location: index.php?route=usuarios');
                exit();
                
            } catch (Exception $e) {
                setFlashMessage('error', $e->getMessage());
            }
        }
        
        ob_start();
        require_once 'src/views/usuarios/form.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    public function edit() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            setFlashMessage('error', 'Usuário não encontrado.');
            header('Location: index.php?route=usuario/listar');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->validateUserData($_POST, 'edit');
                
                $sql = "UPDATE usuarios SET 
                        nome = ?, 
                        email = ?, 
                        nivel_acesso = ?, 
                        ativo = ?
                        WHERE id = ?";
                
                $params = [
                    $_POST['nome'],
                    $_POST['email'],
                    $_POST['nivel_acesso'],
                    isset($_POST['ativo']) ? 1 : 0,
                    $id
                ];
                
                // Se uma nova senha foi fornecida
                if (!empty($_POST['senha'])) {
                    $sql = "UPDATE usuarios SET 
                            nome = ?, 
                            email = ?, 
                            nivel_acesso = ?, 
                            ativo = ?,
                            senha = ? 
                            WHERE id = ?";
                    
                    $params = [
                        $_POST['nome'],
                        $_POST['email'],
                        $_POST['nivel_acesso'],
                        isset($_POST['ativo']) ? 1 : 0,
                        password_hash($_POST['senha'], PASSWORD_DEFAULT),
                        $id
                    ];
                }
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
                
                setFlashMessage('success', 'Usuário atualizado com sucesso!');
                header('Location: index.php?route=usuario/listar');
                exit();
                
            } catch (Exception $e) {
                setFlashMessage('error', $e->getMessage());
            }
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            setFlashMessage('error', 'Usuário não encontrado.');
            header('Location: index.php?route=usuario/listar');
            exit();
        }
        
        ob_start();
        require_once 'src/views/usuarios/form.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }

    public function delete() {
        checkPermission('admin');
        
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            setFlashMessage('error', 'Usuário não encontrado.');
            header('Location: index.php?route=usuarios');
            exit();
        }
        
        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ? AND id != ?");
            $stmt->execute([$id, $_SESSION['user_id']]);
            
            if ($stmt->rowCount() > 0) {
                setFlashMessage('success', 'Usuário excluído com sucesso!');
            } else {
                setFlashMessage('error', 'Não é possível excluir este usuário.');
            }
        } catch (Exception $e) {
            setFlashMessage('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
        
        header('Location: index.php?route=usuarios');
        exit();
    }

    
    private function validateUserData($data, $mode = 'create') {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors[] = 'O nome é obrigatório.';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'O email é obrigatório.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'O email informado é inválido.';
        } else {
            // Verifica se o email já existe
            $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data['email'], $mode === 'edit' ? $_GET['id'] : 0]);
            if ($stmt->fetch()) {
                $errors[] = 'Este email já está em uso.';
            }
        }
        
        if ($mode === 'create' && empty($data['senha'])) {
            $errors[] = 'A senha é obrigatória.';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        }
        
        if (empty($data['nivel_acesso'])) {
            $errors[] = 'O nível de acesso é obrigatório.';
        }
        
        if (!empty($errors)) {
            throw new Exception(implode('<br>', $errors));
        }
        
        return true;
    }
}