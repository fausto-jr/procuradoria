<?php
session_start();

require_once 'config/database.php';
require_once 'src/helpers/auth_helper.php';
require_once 'src/helpers/flash_helper.php';
require_once 'src/helpers/parecer_helper.php';

// Define as rotas da aplicação
$route = isset($_GET['route']) ? $_GET['route'] : 'home';

// Verifica autenticação
if (!isLoggedIn() && $route !== 'login' && $route !== 'auth') {
    header('Location: index.php?route=login');
    exit();
}

// Roteamento básico
switch ($route) {
    case 'login':
        require_once 'src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
        
    case 'auth':
        require_once 'src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->authenticate();
        break;
        
    case 'logout':
        require_once 'src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case 'pareceres':
        require_once 'src/controllers/ParecerController.php';
        $controller = new ParecerController();
        $controller->index();
        break;
        
    case 'parecer/novo':
        require_once 'src/controllers/ParecerController.php';
        $controller = new ParecerController();
        $controller->create();
        break;
        
    case 'parecer/editar':
        require_once 'src/controllers/ParecerController.php';
        $controller = new ParecerController();
        $controller->edit();
        break;
        
    case 'parecer/visualizar':
        require_once 'src/controllers/ParecerController.php';
        $controller = new ParecerController();
        $controller->show();
        break;
        
    case 'parecer/excluir':
        require_once 'src/controllers/ParecerController.php';
        $controller = new ParecerController();
        $controller->delete();
        break;
        
    case 'usuarios':
        require_once 'src/controllers/UserController.php';
        $controller = new UserController();
        $controller->index();
        break;
        
    case 'usuario/novo':
        require_once 'src/controllers/UserController.php';
        $controller = new UserController();
        $controller->create();
        break;
        
    case 'usuario/editar':
        require_once 'src/controllers/UserController.php';
        $controller = new UserController();
        $controller->edit();
        break;
        

    case 'usuario/listar':
        require_once 'src/controllers/UserController.php';
        $controller = new UserController();
        $controller->index();
        break;
        
    case 'usuario/excluir':
        require_once 'src/controllers/UserController.php';
        $controller = new UserController();
        $controller->delete();
        break;

    case 'change-password-form':
        require_once 'src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->changePasswordForm();
        break;

    case 'change-password':
        require_once 'src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->changePassword();
        break;
        
    case 'relatorios':
        require_once 'src/controllers/RelatorioController.php';
        $controller = new RelatorioController($pdo);
        $controller->index();
        break;
        
    case 'relatorios/exportar/excel':
        require_once 'src/controllers/RelatorioController.php';
        $controller = new RelatorioController($pdo);
        $_GET['export'] = 'excel';
        $controller->index();
        break;
        
    case 'relatorios/exportar/pdf':
        require_once 'src/controllers/RelatorioController.php';
        $controller = new RelatorioController($pdo);
        $_GET['export'] = 'pdf';
        $controller->index();
        break;

    case 'relatorios/imprimir':
        require_once 'src/controllers/RelatorioController.php';
        $controller = new RelatorioController($pdo);
        $controller->print();
        break;
        
    case 'dashboard':
    default:
        require_once 'src/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
}