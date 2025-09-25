<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Filtros do Relatório</h4>
                </div>
                <div class="card-body">
                    <form id="filtroRelatorio" method="GET" action="index.php">
                        <input type="hidden" name="route" value="relatorios">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="data_inicio" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="data_fim" class="form-label">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim" name="data_fim">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Todos</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="em_analise">Em Análise</option>
                                    <option value="concluido">Concluído</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="relator" class="form-label">Relator</label>
                                <select class="form-select" id="relator" name="relator">
                                    <option value="">Todos</option>
                                    <?php foreach($relatores as $relator): ?>
                                        <option value="<?php echo $relator['id']; ?>">
                                            <?php echo htmlspecialchars($relator['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                                <button type="button" class="btn btn-success" id="exportarExcel">
                                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                                </button>
                                <button type="button" class="btn btn-danger" id="exportarPDF">
                                    <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Resultados</h4>
                    <span class="badge bg-primary"><?php echo count($pareceres); ?> registros encontrados</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Data</th>
                                    <th>Assunto</th>
                                    <th>Relator</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pareceres)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pareceres as $parecer): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($parecer['protocolo']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($parecer['data'])); ?></td>
                                            <td><?php echo htmlspecialchars($parecer['assunto']); ?></td>
                                            <td><?php echo htmlspecialchars($parecer['relator_nome']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo getStatusBadgeClass($parecer['status']); ?>">
                                                    <?php echo getStatusLabel($parecer['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?route=parecer-view&id=<?php echo $parecer['id']; ?>" 
                                                   class="btn btn-sm btn-info" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Exportar para Excel
    document.getElementById('exportarExcel').addEventListener('click', function() {
        let formData = new FormData(document.getElementById('filtroRelatorio'));
        formData.append('export', 'excel');
        window.location.href = 'index.php?' + new URLSearchParams(formData).toString();
    });

    // Exportar para PDF
    document.getElementById('exportarPDF').addEventListener('click', function() {
        let formData = new FormData(document.getElementById('filtroRelatorio'));
        formData.append('export', 'pdf');
        window.location.href = 'index.php?' + new URLSearchParams(formData).toString();
    });
});
</script>