<?php

class ParecerController {
    public function delete() {
        checkPermission('admin');
        
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            setFlashMessage('error', 'Parecer não encontrado.');
            header('Location: index.php?route=pareceres');
            exit();
        }
        
        try {
            $stmt = $this->pdo->prepare("DELETE FROM pareceres WHERE id = ?");
            $stmt->execute([$id]);
            
            setFlashMessage('success', 'Parecer excluído com sucesso!');
        } catch (Exception $e) {
            setFlashMessage('error', 'Erro ao excluir parecer: ' . $e->getMessage());
        }
        
        header('Location: index.php?route=pareceres');
        exit();
    }

    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function index() {
        $currentUser = getCurrentUser();
        $isAdmin = $currentUser['nivel_acesso'] === 'admin';
        
        $where = "1=1";
        $params = [];
        
        // Restrição por relator se não for admin
        if (!$isAdmin) {
            $where .= " AND relator_id = ?";
            $params[] = $currentUser['id'];
        }
        
        // Filtros
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $where .= " AND status = ?";
            $params[] = $_GET['status'];
        }
        
        if (isset($_GET['processo']) && !empty($_GET['processo'])) {
            $where .= " AND numero_processo LIKE ?";
            $params[] = '%' . $_GET['processo'] . '%';
        }

        if (isset($_GET['assunto']) && !empty($_GET['assunto'])) {
            $where .= " AND assunto LIKE ?";
            $params[] = '%' . $_GET['assunto'] . '%';
        }

        if (isset($_GET['interessado']) && !empty($_GET['interessado'])) {
            $where .= " AND interessado LIKE ?";
            $params[] = '%' . $_GET['interessado'] . '%';
        }
        
        if (isset($_GET['relator']) && !empty($_GET['relator'])) {
            $where .= " AND relator_id = ?";
            $params[] = $_GET['relator'];
        }
        
        if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
            $where .= " AND tipo = ?";
            $params[] = $_GET['tipo'];
        }
        
        if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
            $where .= " AND data_entrada >= ?";
            $params[] = $_GET['data_inicio'];
        }
        
        if (isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
            $where .= " AND data_entrada <= ?";
            $params[] = $_GET['data_fim'];
        }
        
        // Busca relatores para o filtro
        $stmt = $this->pdo->query("SELECT id, nome FROM usuarios WHERE ativo = TRUE ORDER BY nome");
        $relatores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Busca pareceres com paginação
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, u.nome as relator_nome 
                FROM pareceres p 
                INNER JOIN usuarios u ON p.relator_id = u.id 
                WHERE {$where} 
                ORDER BY p.created_at DESC 
                LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $pareceres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Total de registros para paginação
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM pareceres WHERE {$where}");
        $stmt->execute($params);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($total / $limit);
        
        $data = [
            'pareceres' => $pareceres,
            'relatores' => $relatores,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $total,
            'filters' => $_GET
        ];
        
        ob_start();
        require_once 'src/views/pareceres/index.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = $this->validateParecerData($_POST);
                
                $sql = "INSERT INTO pareceres (numero_processo, assunto, interessado, relator_id, 
                        data_entrada, prazo_dias, data_limite, status, tipo) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";;
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $data['numero_processo'],
                    $data['assunto'],
                    $data['interessado'],
                    $data['relator_id'],
                    $data['data_entrada'],
                    $data['prazo_dias'],
                    $data['data_limite'],
                    'pendente',
                    $data['tipo']
                ]);
                
                setFlashMessage('success', 'Parecer cadastrado com sucesso!');
                header('Location: index.php?route=pareceres');
                exit();
                
            } catch (Exception $e) {
                setFlashMessage('error', $e->getMessage());
            }
        }
        
        // Busca relatores ativos
        $stmt = $this->pdo->query("SELECT id, nome FROM usuarios WHERE ativo = TRUE ORDER BY nome");
        $relatores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        ob_start();
        require_once 'src/views/pareceres/form.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    public function edit() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            setFlashMessage('error', 'Parecer não encontrado.');
            header('Location: index.php?route=pareceres');
            exit();
        }

        $currentUser = getCurrentUser();
        $isAdmin = $currentUser['nivel_acesso'] === 'admin';
        
        // Busca o parecer
        $stmt = $this->pdo->prepare("SELECT * FROM pareceres WHERE id = ?");
        $stmt->execute([$id]);
        $parecer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$parecer) {
            setFlashMessage('error', 'Parecer não encontrado!');
            header('Location: index.php?route=pareceres');
            exit();
        }
        
        // Verifica se o usuário tem permissão para editar o parecer
        if (!$isAdmin && $parecer['relator_id'] != $currentUser['id']) {
            setFlashMessage('error', 'Você não tem permissão para editar este parecer!');
            header('Location: index.php?route=pareceres');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($isAdmin) {
                    $data = $this->validateParecerData($_POST);
                    
                    $sql = "UPDATE pareceres SET 
                            numero_processo = ?, 
                            assunto = ?, 
                            interessado = ?, 
                            relator_id = ?, 
                            data_entrada = ?, 
                            prazo_dias = ?, 
                            data_limite = ?, 
                            status = ?, 
                            parecer_texto = ?, 
                            data_conclusao = ?,
                            tipo = ?
                            WHERE id = ?";
                    
                    $params = [
                        $data['numero_processo'],
                        $data['assunto'],
                        $data['interessado'],
                        $data['relator_id'],
                        $data['data_entrada'],
                        $data['prazo_dias'],
                        $data['data_limite'],
                        $_POST['status'],
                        $_POST['parecer_texto'] ?? null,
                        $_POST['status'] === 'concluido' ? date('Y-m-d') : null,
                        $data['tipo'],
                        $id
                    ];
                } else {
                    $sql = "UPDATE pareceres SET 
                            status = ?, 
                            parecer_texto = ?, 
                            data_conclusao = ?
                            WHERE id = ?";
                    
                    $params = [
                        $_POST['status'],
                        $_POST['parecer_texto'] ?? null,
                        $_POST['status'] === 'concluido' ? date('Y-m-d') : null,
                        $id
                    ];
                }
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
                
                // Atualiza dias de atraso
                if ($_POST['status'] === 'concluido') {
                    $this->updateDiasAtraso($id);
                }
                
                setFlashMessage('success', 'Parecer atualizado com sucesso!');
                header('Location: index.php?route=pareceres');
                exit();
                
            } catch (Exception $e) {
                setFlashMessage('error', $e->getMessage());
            }
        }
        
        // Busca dados do parecer
        $stmt = $this->pdo->prepare("SELECT * FROM pareceres WHERE id = ?");
        $stmt->execute([$id]);
        $parecer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$parecer) {
            setFlashMessage('error', 'Parecer não encontrado.');
            header('Location: index.php?route=pareceres');
            exit();
        }
        
        // Busca relatores ativos
        $stmt = $this->pdo->query("SELECT id, nome FROM usuarios WHERE ativo = TRUE ORDER BY nome");
        $relatores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        ob_start();
        require_once 'src/views/pareceres/form.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    public function show() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            setFlashMessage('error', 'Parecer não encontrado.');
            header('Location: index.php?route=pareceres');
            exit();
        }

        $currentUser = getCurrentUser();
        $isAdmin = $currentUser['nivel_acesso'] === 'admin';
        
        $sql = "SELECT p.*, u.nome as relator_nome 
                FROM pareceres p 
                INNER JOIN usuarios u ON p.relator_id = u.id 
                WHERE p.id = ?";
                
        if (!$isAdmin) {
            $sql .= " AND p.relator_id = ?";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = [$id];
        if (!$isAdmin) {
            $params[] = $currentUser['id'];
        }
        $stmt->execute($params);
        $parecer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$parecer) {
            setFlashMessage('error', 'Parecer não encontrado.');
            header('Location: index.php?route=pareceres');
            exit();
        }
        
        ob_start();
        require_once 'src/views/pareceres/show.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
    
    private function validateParecerData($data) {
        $errors = [];
        
        if (empty($data['numero_processo'])) {
            $errors[] = 'O número do processo é obrigatório.';
        }
        
        if (empty($data['assunto'])) {
            $errors[] = 'O assunto é obrigatório.';
        }
        
        if (empty($data['interessado'])) {
            $errors[] = 'O interessado é obrigatório.';
        }
        
        if (empty($data['relator_id'])) {
            $errors[] = 'O relator é obrigatório.';
        }
        
        if (empty($data['data_entrada'])) {
            $errors[] = 'A data de entrada é obrigatória.';
        }
        
        if (empty($data['prazo_dias']) || !is_numeric($data['prazo_dias'])) {
            $errors[] = 'O prazo em dias é obrigatório e deve ser um número.';
        }

        if (empty($data['tipo']) || !in_array($data['tipo'], ['licitacao', 'administrativo'])) {
            $errors[] = 'O tipo do parecer é obrigatório e deve ser Licitação ou Administrativo.';
        }
        
        if (!empty($errors)) {
            throw new Exception(implode('<br>', $errors));
        }
        
        // Calcula a data limite
        $data_entrada = new DateTime($data['data_entrada']);
        $data['data_limite'] = $data_entrada->modify("+{$data['prazo_dias']} days")->format('Y-m-d');
        
        return $data;
    }
    
    private function updateDiasAtraso($id) {
        $sql = "UPDATE pareceres SET dias_atraso = 
               CASE 
                   WHEN data_conclusao > data_limite 
                   THEN DATEDIFF(data_conclusao, data_limite) 
                   ELSE 0 
               END 
               WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}