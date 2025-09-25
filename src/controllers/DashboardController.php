<?php

class DashboardController {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function index() {
        $currentUser = getCurrentUser();
        $isAdmin = $currentUser['nivel_acesso'] === 'admin';
        
        // Condição para filtrar por relator
        $relatorCondition = $isAdmin ? "" : "WHERE relator_id = " . $currentUser['id'];
        
        // Total de pareceres por tipo
        $sql = "SELECT tipo, COUNT(*) as total FROM pareceres " . 
               ($isAdmin ? "" : "WHERE relator_id = ?") . 
               " GROUP BY tipo";
        $stmt = $this->pdo->prepare($sql);
        if (!$isAdmin) {
            $stmt->execute([$currentUser['id']]);
        } else {
            $stmt->execute();
        }
        $pareceresPorTipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Total geral de pareceres
        $totalPareceres = array_sum(array_column($pareceresPorTipo, 'total'));
        
        // Pareceres por status e tipo
        $sql = "SELECT tipo, status, COUNT(*) as total FROM pareceres " . 
               ($isAdmin ? "" : "WHERE relator_id = ?") . 
               " GROUP BY tipo, status";
        $stmt = $this->pdo->prepare($sql);
        if (!$isAdmin) {
            $stmt->execute([$currentUser['id']]);
        } else {
            $stmt->execute();
        }
        $pareceresStatusTipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pareceres em atraso por tipo
        $sql = "SELECT tipo, COUNT(*) as total FROM pareceres WHERE status != 'concluido' AND data_limite < CURDATE()" . 
               ($isAdmin ? "" : " AND relator_id = ?") . 
               " GROUP BY tipo";
        $stmt = $this->pdo->prepare($sql);
        if (!$isAdmin) {
            $stmt->execute([$currentUser['id']]);
        } else {
            $stmt->execute();
        }
        $pareceresAtraso = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pareceres do mês atual por tipo
        $sql = "SELECT tipo, COUNT(*) as total FROM pareceres WHERE MONTH(data_entrada) = MONTH(CURRENT_DATE()) AND YEAR(data_entrada) = YEAR(CURRENT_DATE())" . 
               ($isAdmin ? "" : " AND relator_id = ?") . 
               " GROUP BY tipo";
        $stmt = $this->pdo->prepare($sql);
        if (!$isAdmin) {
            $stmt->execute([$currentUser['id']]);
        } else {
            $stmt->execute();
        }
        $pareceresMes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Últimos pareceres cadastrados
        $sql = "SELECT p.*, u.nome as relator_nome FROM pareceres p
                INNER JOIN usuarios u ON p.relator_id = u.id" . 
               ($isAdmin ? "" : " WHERE p.relator_id = ?") . 
               " ORDER BY p.created_at DESC LIMIT 5";
        $stmt = $this->pdo->prepare($sql);
        if (!$isAdmin) {
            $stmt->execute([$currentUser['id']]);
        } else {
            $stmt->execute();
        }
        $ultimosPareceres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'totalPareceres' => $totalPareceres,
            'pareceresPorTipo' => $pareceresPorTipo,
            'pareceresStatusTipo' => $pareceresStatusTipo,
            'pareceresAtraso' => $pareceresAtraso,
            'pareceresMes' => $pareceresMes,
            'ultimosPareceres' => $ultimosPareceres
        ];
        
        ob_start();
        require_once 'src/views/dashboard/index.php';
        $content = ob_get_clean();
        
        require_once 'src/views/layouts/app.php';
    }
}