<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle de Pareceres Jurídicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--primary-color);
            padding-top: 1rem;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 0.8rem 1rem;
            margin: 0.2rem 0;
            border-radius: 0.375rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: var(--secondary-color);
        }
        .sidebar .nav-link.active {
            background: var(--accent-color);
            color: #fff;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        .main-content {
            margin-left: 250px;
        }
        .navbar {
            background: #fff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
            border-radius: 0.5rem;
        }
        .btn {
            border-radius: 0.375rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 250px; position: fixed;">
            <div class="px-3 mb-4">
                <a class="navbar-brand text-white" href="index.php">Procuradoria</a>
            </div>
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link <?php echo !isset($_GET['route']) ? 'active' : ''; ?>" href="index.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['route']) && $_GET['route'] === 'pareceres' ? 'active' : ''; ?>" href="index.php?route=pareceres">
                        <i class="bi bi-file-text"></i> Pareceres
                    </a>
                </li>
                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['route']) && $_GET['route'] === 'usuarios' ? 'active' : ''; ?>" href="index.php?route=usuarios">
                        <i class="bi bi-people"></i> Usuários
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isset($_GET['route']) && $_GET['route'] === 'relatorios' ? 'active' : ''; ?>" href="index.php?route=relatorios">
                        <i class="bi bi-graph-up"></i> Relatórios
                    </a>
                </li>
              
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="index.php?route=change-password-form"><i class="bi bi-key"></i> Alterar Senha</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="index.php?route=logout"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-4">
                <?php displayFlashMessage(); ?>
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>