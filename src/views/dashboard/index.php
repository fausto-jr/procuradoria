<div class="row">


    <!-- Cards de estatísticas gerais -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-primary">
            <div class="card-body">
                <h5 class="card-title">Total de Pareceres</h5>
                <p class="display-4"><?php echo $data['totalPareceres']; ?></p>
                <div class="mt-3">
                    <?php foreach ($data['pareceresPorTipo'] as $tipo): ?>
                        <?php 
                        $tipoLabel = $tipo['tipo'] === 'licitacao' ? 'Licitação' : 'Administrativo';
                        ?>
                        <div class="mb-2">
                            <small><?php echo $tipoLabel; ?>: <?php echo $tipo['total']; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-warning">
            <div class="card-body">
                <h5 class="card-title">Pareceres em Atraso</h5>
                <?php 
                $totalAtraso = array_sum(array_column($data['pareceresAtraso'], 'total'));
                ?>
                <p class="display-4 <?php echo $totalAtraso > 0 ? 'text-danger' : ''; ?>">
                    <?php echo $totalAtraso; ?>
                </p>
                <div class="mt-3">
                    <?php foreach ($data['pareceresAtraso'] as $atraso): ?>
                        <?php 
                        $tipoLabel = $atraso['tipo'] === 'licitacao' ? 'Licitação' : 'Administrativo';
                        ?>
                        <div class="mb-2">
                            <small><?php echo $tipoLabel; ?>: <?php echo $atraso['total']; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-success">
            <div class="card-body">
                <h5 class="card-title">Pareceres do Mês</h5>
                <?php 
                $totalMes = array_sum(array_column($data['pareceresMes'], 'total'));
                ?>
                <p class="display-4"><?php echo $totalMes; ?></p>
                <div class="mt-3">
                    <?php foreach ($data['pareceresMes'] as $mes): ?>
                        <?php 
                        $tipoLabel = $mes['tipo'] === 'licitacao' ? 'Licitação' : 'Administrativo';
                        ?>
                        <div class="mb-2">
                            <small><?php echo $tipoLabel; ?>: <?php echo $mes['total']; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cards de status por tipo -->
<div class="row">
    <?php 
    $tipos = ['licitacao' => 'Licitação', 'administrativo' => 'Administrativo'];
    foreach ($tipos as $tipoKey => $tipoLabel): 
        $pareceresDoTipo = array_filter($data['pareceresStatusTipo'], function($item) use ($tipoKey) {
            return $item['tipo'] === $tipoKey;
        });
        $totalDoTipo = array_sum(array_column($pareceresDoTipo, 'total'));
    ?>
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-info">
            <div class="card-body">
                <h5 class="card-title">Status dos Pareceres - <?php echo $tipoLabel; ?></h5>
                <div class="mt-3">
                    <?php foreach ($pareceresDoTipo as $item): ?>
                        <?php 
                        $statusLabel = [
                            'pendente' => 'Pendentes',
                            'em_analise' => 'Em Análise',
                            'concluido' => 'Concluídos'
                        ][$item['status']];
                        
                        $statusClass = [
                            'pendente' => 'danger',
                            'em_analise' => 'warning',
                            'concluido' => 'success'
                        ][$item['status']];
                        ?>
                        <div class="mb-2">
                            <small><?php echo $statusLabel; ?></small>
                            <div class="progress">
                                <div class="progress-bar bg-<?php echo $statusClass; ?>" 
                                     role="progressbar" 
                                     style="width: <?php echo ($totalDoTipo > 0 ? ($item['total'] / $totalDoTipo) * 100 : 0); ?>%">
                                    <?php echo $item['total']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Últimos pareceres -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Últimos Pareceres</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Processo</th>
                                <th>Assunto</th>
                                <th>Relator</th>
                                <th>Data Entrada</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['ultimosPareceres'] as $parecer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($parecer['numero_processo']); ?></td>
                                    <td><?php echo htmlspecialchars($parecer['assunto']); ?></td>
                                    <td><?php echo htmlspecialchars($parecer['relator_nome']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($parecer['data_entrada'])); ?></td>
                                    <td>
                                        <span class="badge bg-secondary" style="padding: 0.5em 0.8em; font-size: 0.85em;">
                                            <?php echo $parecer['tipo'] === 'licitacao' ? 'Licitação' : 'Administrativo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $statusClass = [
                                            'pendente' => 'danger',
                                            'em_analise' => 'warning',
                                            'concluido' => 'success'
                                        ][$parecer['status']];
                                        
                                        $statusLabel = [
                                            'pendente' => 'Pendente',
                                            'em_analise' => 'Em Análise',
                                            'concluido' => 'Concluído'
                                        ][$parecer['status']];
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo $statusLabel; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?route=parecer/visualizar&id=<?php echo $parecer['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>