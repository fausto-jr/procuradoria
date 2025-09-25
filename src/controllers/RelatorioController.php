<?php

require_once(__DIR__ . '/../helpers/parecer_helper.php');

class RelatorioController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function print() {
        $currentUser = getCurrentUser();
        $isAdmin = $currentUser['nivel_acesso'] === 'admin';

        // Obter parâmetros de filtro
        $dataInicio = $_GET['data_inicio'] ?? '';
        $dataFim = $_GET['data_fim'] ?? '';
        $status = $_GET['status'] ?? '';
        $relator = $_GET['relator'] ?? '';
        $tipo = $_GET['tipo'] ?? '';

        // Construir a query base
        $sql = "SELECT p.*, u.nome as relator_nome 
                FROM pareceres p 
                LEFT JOIN usuarios u ON p.relator_id = u.id 
                WHERE 1=1";
        $params = [];

        // Restrição por relator se não for admin
        if (!$isAdmin) {
            $sql .= " AND p.relator_id = ?";
            $params[] = $currentUser['id'];
        }

        // Adicionar filtros
        if ($dataInicio) {
            $sql .= " AND p.data_entrada >= ?";
            $params[] = $dataInicio;
        }
        if ($dataFim) {
            $sql .= " AND p.data_entrada <= ?";
            $params[] = $dataFim;
        }
        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }
        if ($relator) {
            $sql .= " AND p.relator_id = ?";
            $params[] = $relator;
        }
        if ($tipo) {
            $sql .= " AND p.tipo = ?";
            $params[] = $tipo;
        }

        $sql .= " ORDER BY p.data_entrada DESC";

        // Executar a query
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pareceres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular estatísticas
        $total_pareceres = count($pareceres);
        $pareceres_em_analise = 0;
        $pareceres_concluidos = 0;

        foreach ($pareceres as $parecer) {
            if ($parecer['status'] === 'em_analise') {
                $pareceres_em_analise++;
            } else if ($parecer['status'] === 'concluido') {
                $pareceres_concluidos++;
            }
        }

        // Carregar a view de impressão
        require_once(__DIR__ . '/../views/relatorios/imprimir.php');
    }

    public function index() {
        $currentUser = getCurrentUser();
        $isAdmin = $currentUser['nivel_acesso'] === 'admin';

        // Obter parâmetros de filtro
        $dataInicio = $_GET['data_inicio'] ?? '';
        $dataFim = $_GET['data_fim'] ?? '';
        $status = $_GET['status'] ?? '';
        $relator = $_GET['relator'] ?? '';
        $tipo = $_GET['tipo'] ?? '';

        // Construir a query base
        $sql = "SELECT p.*, u.nome as relator_nome 
                FROM pareceres p 
                LEFT JOIN usuarios u ON p.relator_id = u.id 
                WHERE 1=1";
        $params = [];

        // Restrição por relator se não for admin
        if (!$isAdmin) {
            $sql .= " AND p.relator_id = ?";
            $params[] = $currentUser['id'];
        }

        // Adicionar filtros
        if ($dataInicio) {
            $sql .= " AND p.data_entrada >= ?";
            $params[] = $dataInicio;
        }
        if ($dataFim) {
            $sql .= " AND p.data_entrada <= ?";
            $params[] = $dataFim;
        }
        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }
        if ($relator) {
            $sql .= " AND p.relator_id = ?";
            $params[] = $relator;
        }
        if ($tipo) {
            $sql .= " AND p.tipo = ?";
            $params[] = $tipo;
        }

        // Contar total de registros para paginação
        $countSql = "SELECT COUNT(*) as total FROM pareceres p LEFT JOIN usuarios u ON p.relator_id = u.id WHERE 1=1";
        if (!$isAdmin) {
            $countSql .= " AND p.relator_id = ?";
        }
        if ($dataInicio) {
            $countSql .= " AND p.data_entrada >= ?";
        }
        if ($dataFim) {
            $countSql .= " AND p.data_entrada <= ?";
        }
        if ($status) {
            $countSql .= " AND p.status = ?";
        }
        if ($relator) {
            $countSql .= " AND p.relator_id = ?";
        }
        if ($tipo) {
            $countSql .= " AND p.tipo = ?";
        }

        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Paginação
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15; // 15 registros por página para relatórios
        $offset = ($page - 1) * $limit;
        $totalPages = ceil($total / $limit);

        $sql .= " ORDER BY p.data_entrada DESC LIMIT {$limit} OFFSET {$offset}";

        // Executar a query
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $pareceres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obter lista de relatores para o filtro
        $stmtRelatores = $this->db->query("SELECT id, nome FROM usuarios WHERE nivel_acesso = 'advogado' ORDER BY nome");
        $relatores = $stmtRelatores->fetchAll(PDO::FETCH_ASSOC);

        // Verificar se é uma exportação
        if (isset($_GET['export'])) {
            if ($_GET['export'] === 'excel') {
                $this->exportarExcel($pareceres);
            } elseif ($_GET['export'] === 'pdf') {
                $this->exportarPDF($pareceres);
            }
        }

        // Calcular estatísticas (baseado no total filtrado, não apenas na página atual)
        $statsSql = "SELECT 
                        COUNT(*) as total_pareceres,
                        SUM(CASE WHEN status = 'em_analise' THEN 1 ELSE 0 END) as pareceres_em_analise,
                        SUM(CASE WHEN status = 'concluido' THEN 1 ELSE 0 END) as pareceres_concluidos
                     FROM pareceres p 
                     LEFT JOIN usuarios u ON p.relator_id = u.id 
                     WHERE 1=1";
        
        if (!$isAdmin) {
            $statsSql .= " AND p.relator_id = ?";
        }
        if ($dataInicio) {
            $statsSql .= " AND p.data_entrada >= ?";
        }
        if ($dataFim) {
            $statsSql .= " AND p.data_entrada <= ?";
        }
        if ($status) {
            $statsSql .= " AND p.status = ?";
        }
        if ($relator) {
            $statsSql .= " AND p.relator_id = ?";
        }
        if ($tipo) {
            $statsSql .= " AND p.tipo = ?";
        }

        $stmt = $this->db->prepare($statsSql);
        $stmt->execute($params);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_pareceres = $stats['total_pareceres'];
        $pareceres_em_analise = $stats['pareceres_em_analise'];
        $pareceres_concluidos = $stats['pareceres_concluidos'];

        // Renderizar a view
        require_once(__DIR__ . '/../views/relatorios/index.php');
    }

    private function exportarExcel($pareceres) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="relatorio_pareceres.xls"');
        header('Cache-Control: max-age=0');

        echo "<table border=\"1\">";
        echo "<tr>";
        echo "<th>Protocolo</th>";
        echo "<th>Data</th>";
        echo "<th>Assunto</th>";
        echo "<th>Relator</th>";
        echo "<th>Status</th>";
        echo "</tr>";

        foreach ($pareceres as $parecer) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($parecer['numero_processo']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($parecer['data_entrada'])) . "</td>";
            echo "<td>" . htmlspecialchars($parecer['assunto']) . "</td>";
            echo "<td>" . htmlspecialchars($parecer['relator_nome']) . "</td>";
            echo "<td>" . getStatusLabel($parecer['status']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit;
    }

    private function exportarPDF($pareceres) {
        require_once(__DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php');

       // $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetCreator('Sistema de Procuradoria');
        $pdf->SetAuthor('Procuradoria');
        $pdf->SetTitle('Relatório de Pareceres');

        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Relatório de Pareceres', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);
        $headers = ['Protocolo', 'Data', 'Assunto', 'Relator', 'Status'];
        $widths = [30, 25, 100, 50, 30];

        for($i = 0; $i < count($headers); $i++) {
            $pdf->Cell($widths[$i], 7, $headers[$i], 1, 0, 'C');
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 9);
        foreach($pareceres as $parecer) {
            $pdf->Cell($widths[0], 6, $parecer['numero_processo'], 1);
            $pdf->Cell($widths[1], 6, date('d/m/Y', strtotime($parecer['data_entrada'])), 1);
            $pdf->Cell($widths[2], 6, $parecer['assunto'], 1);
            $pdf->Cell($widths[3], 6, $parecer['relator_nome'], 1);
            $pdf->Cell($widths[4], 6, getStatusLabel($parecer['status']), 1);
            $pdf->Ln();
        }

        $pdf->Output('relatorio_pareceres.pdf', 'D');
        exit;
    }
}

// As funções getStatusLabel e getStatusBadgeClass foram movidas para o arquivo parecer_helper.php