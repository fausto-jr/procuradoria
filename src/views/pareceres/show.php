<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Visualizar Parecer</h1>
            <div class="btn-group">
                <a href="index.php?route=pareceres" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <a href="index.php?route=parecer/editar&id=<?php echo $parecer['id']; ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Informações Básicas -->
                    <div class="col-md-6">
                        <h5 class="card-title">Informações do Processo</h5>
                        <dl class="row">
                            <dt class="col-sm-4">Número do Processo</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($parecer['numero_processo']); ?></dd>

                            <dt class="col-sm-4">Interessado</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($parecer['interessado']); ?></dd>

                            <dt class="col-sm-4">Assunto</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($parecer['assunto']); ?></dd>

                            <dt class="col-sm-4">Relator</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($parecer['relator_nome']); ?></dd>
                        </dl>
                    </div>

                    <!-- Informações de Prazo -->
                    <div class="col-md-6">
                        <h5 class="card-title">Prazos e Status</h5>
                        <dl class="row">
                            <dt class="col-sm-4">Data de Entrada</dt>
                            <dd class="col-sm-8"><?php echo date('d/m/Y', strtotime($parecer['data_entrada'])); ?></dd>

                            <dt class="col-sm-4">Prazo em Dias</dt>
                            <dd class="col-sm-8"><?php echo $parecer['prazo_dias']; ?> dias</dd>

                            <dt class="col-sm-4">Data Limite</dt>
                            <dd class="col-sm-8" class="<?php echo $parecer['status'] !== 'concluido' && strtotime($parecer['data_limite']) < time() ? 'text-danger' : ''; ?>">
                                <?php echo date('d/m/Y', strtotime($parecer['data_limite'])); ?>
                            </dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
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
                            </dd>

                            <?php if ($parecer['status'] === 'concluido'): ?>
                                <dt class="col-sm-4">Data de Conclusão</dt>
                                <dd class="col-sm-8"><?php echo date('d/m/Y', strtotime($parecer['data_conclusao'])); ?></dd>

                                <dt class="col-sm-4">Dias em Atraso</dt>
                                <dd class="col-sm-8">
                                    <?php if ($parecer['dias_atraso'] > 0): ?>
                                        <span class="text-danger"><?php echo $parecer['dias_atraso']; ?> dias</span>
                                    <?php else: ?>
                                        <span class="text-success">Sem atraso</span>
                                    <?php endif; ?>
                                </dd>
                            <?php endif; ?>
                        </dl>
                    </div>

                    <!-- Texto do Parecer -->
                    <?php if (!empty($parecer['parecer_texto'])): ?>
                        <div class="col-12 mt-4">
                            <h5 class="card-title">Texto do Parecer</h5>
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($parecer['parecer_texto'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Informações do Sistema -->
                    <div class="col-12 mt-4">
                        <h5 class="card-title">Informações do Sistema</h5>
                        <dl class="row">
                            <dt class="col-sm-2">Criado em</dt>
                            <dd class="col-sm-4"><?php echo date('d/m/Y H:i:s', strtotime($parecer['created_at'])); ?></dd>

                            <dt class="col-sm-2">Última Atualização</dt>
                            <dd class="col-sm-4"><?php echo date('d/m/Y H:i:s', strtotime($parecer['updated_at'])); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para Impressão -->
<style media="print">
    .btn-group, .navbar, .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
    body {
        padding: 0 !important;
        margin: 0 !important;
    }
</style>