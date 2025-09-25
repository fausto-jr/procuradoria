<?php require_once(__DIR__ . '/../layouts/print.php'); ?>

<div class="print-content">
    <div class="print-header mb-4">
        <h1>Relatório de Pareceres Jurídicos</h1>
        <p class="text-muted">Período: <?= htmlspecialchars($dataInicio) ?> a <?= htmlspecialchars($dataFim) ?></p>
    </div>

    <!-- Estatísticas -->
    <div class="stats-section mb-4">
        <div class="row">
            <div class="col-4">
                <div class="stat-item">
                    <h6>Total de Pareceres</h6>
                    <strong><?php echo $total_pareceres; ?></strong>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-item">
                    <h6>Em Análise</h6>
                    <strong><?php echo $pareceres_em_analise; ?></strong>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-item">
                    <h6>Concluídos</h6>
                    <strong><?php echo $pareceres_concluidos; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Resultados -->
    <div class="table-section">
        <h2>Lista de Pareceres</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Data</th>
                    <th>Assunto</th>
                    <th>Relator</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pareceres)): ?>
                    <?php foreach ($pareceres as $parecer): ?>
                        <tr>
                            <td><?= htmlspecialchars($parecer['numero_processo']) ?></td>
                            <td><?= date('d/m/Y', strtotime($parecer['data_entrada'])) ?></td>
                            <td><?= htmlspecialchars($parecer['assunto']) ?></td>
                            <td><?= htmlspecialchars($parecer['relator_nome']) ?></td>
                            <td><?= getStatusLabel($parecer['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhum registro encontrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .print-content {
            padding: 20px;
        }
        .print-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .stats-section {
            margin: 20px 0;
        }
        .stat-item {
            text-align: center;
            margin-bottom: 15px;
        }
        .table-section {
            margin-top: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f8f9fa;
        }
    }
</style>

<script>
    window.onload = function() {
        window.print();
    }
</script>