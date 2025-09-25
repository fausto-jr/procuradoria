<?php
// Incluir o helper de paginação
require_once(__DIR__ . '/../../helpers/pagination_helper.php');

// Preparar parâmetros de query para manter os filtros
$queryParams = [];
if (!empty($_GET['data_inicio'])) $queryParams['data_inicio'] = $_GET['data_inicio'];
if (!empty($_GET['data_fim'])) $queryParams['data_fim'] = $_GET['data_fim'];
if (!empty($_GET['status'])) $queryParams['status'] = $_GET['status'];
if (!empty($_GET['relator'])) $queryParams['relator'] = $_GET['relator'];
if (!empty($_GET['tipo'])) $queryParams['tipo'] = $_GET['tipo'];
?>
<?php require_once(__DIR__ . '/../layouts/app.php'); ?>

<div class="main-content p-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Relatórios</h1>
                    <p class="text-muted mb-0">Análise e estatísticas dos pareceres jurídicos</p>
                </div>
                <div>
                    <a href="index.php?route=relatorios/exportar/excel&<?= http_build_query(array_diff_key($_GET, ['route' => ''])) ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-file-excel"></i> Exportar Excel
                    </a>
                    <a href="index.php?route=relatorios/imprimir&<?= http_build_query(array_diff_key($_GET, ['route' => ''])) ?>" class="btn btn-primary btn-sm ms-2" target="_blank">
                        <i class="bi bi-printer"></i> Imprimir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="route" value="relatorios">
                    <div class="col-md-3">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($dataInicio) ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= htmlspecialchars($dataFim) ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="pendente" <?= $status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="em_analise" <?= $status === 'em_analise' ? 'selected' : '' ?>>Em Análise</option>
                            <option value="concluido" <?= $status === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo" name="tipo">
                            <option value="">Todos</option>
                            <option value="juridico" <?= $tipo === 'juridico' ? 'selected' : '' ?>>Jurídico</option>
                            <option value="administrativo" <?= $tipo === 'administrativo' ? 'selected' : '' ?>>Administrativo</option>
                            <option value="tributario" <?= $tipo === 'tributario' ? 'selected' : '' ?>>Tributário</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="relator" class="form-label">Relator</label>
                        <select class="form-select" id="relator" name="relator">
                            <option value="">Todos</option>
                            <?php foreach ($relatores as $rel): ?>
                                <option value="<?= $rel['id'] ?>" <?= $relator == $rel['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($rel['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="index.php?route=relatorios" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="col-12">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted mb-1">Total de Pareceres</h6>
                                <h3 class="mb-0 text-primary"><?= $total_pareceres ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-file-text fs-1 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted mb-1">Em Análise</h6>
                                <h3 class="mb-0 text-warning"><?= $pareceres_em_analise ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-1 text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-muted mb-1">Concluídos</h6>
                                <h3 class="mb-0 text-success"><?= $pareceres_concluidos ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Pareceres -->
        <div class="card mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Pareceres</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pareceres)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Nenhum parecer encontrado com os filtros aplicados.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Data</th>
                                    <th>Assunto</th>
                                    <th>Tipo</th>
                                    <th>Relator</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pareceres as $parecer): ?>
                                    <tr>
                                        <td>
                                            <strong class="text-primary"><?= htmlspecialchars($parecer['protocolo']) ?></strong>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($parecer['data_entrada'])) ?></td>
                                        <td class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($parecer['assunto']) ?>">
                                            <?= htmlspecialchars($parecer['assunto']) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= ucfirst(str_replace('_', ' ', $parecer['tipo'])) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($parecer['relator_nome'] ?? 'Não atribuído') ?></td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($parecer['status']) {
                                                case 'pendente':
                                                    $statusClass = 'bg-secondary';
                                                    $statusText = 'Pendente';
                                                    break;
                                                case 'em_analise':
                                                    $statusClass = 'bg-warning text-dark';
                                                    $statusText = 'Em Análise';
                                                    break;
                                                case 'concluido':
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Concluído';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-light text-dark';
                                                    $statusText = ucfirst($parecer['status']);
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Paginação -->
        <?php if ($totalPages > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="pagination-info">
                    <?= generatePaginationInfo($page, $limit, $total) ?>
                </div>
                <nav aria-label="Navegação de páginas">
                    <?= generateSmartPagination($page, $totalPages, 'index.php?route=relatorios', $queryParams) ?>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>