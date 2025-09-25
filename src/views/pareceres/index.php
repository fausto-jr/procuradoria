<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Pareceres</h1>
                <p class="text-muted mb-0">Gerencie os pareceres jurídicos</p>
            </div>
            <?php if (isAdmin()): ?>
            <a href="index.php?route=parecer/novo" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo Parecer
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filtros -->
    <div class="col-12 mb-4">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="route" value="pareceres">
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="pendente" <?php echo isset($_GET['status']) && $_GET['status'] === 'pendente' ? 'selected' : ''; ?>>
                                Pendente
                            </option>
                            <option value="em_analise" <?php echo isset($_GET['status']) && $_GET['status'] === 'em_analise' ? 'selected' : ''; ?>>
                                Em Análise
                            </option>
                            <option value="concluido" <?php echo isset($_GET['status']) && $_GET['status'] === 'concluido' ? 'selected' : ''; ?>>
                                Concluído
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="licitacao" <?php echo isset($_GET['tipo']) && $_GET['tipo'] === 'licitacao' ? 'selected' : ''; ?>>
                                Licitação
                            </option>
                            <option value="administrativo" <?php echo isset($_GET['tipo']) && $_GET['tipo'] === 'administrativo' ? 'selected' : ''; ?>>
                                Administrativo
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="processo" class="form-label">Processo</label>
                        <input type="text" class="form-control" id="processo" name="processo" 
                               value="<?php echo isset($_GET['processo']) ? htmlspecialchars($_GET['processo']) : ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="assunto" class="form-label">Assunto</label>
                        <input type="text" class="form-control" id="assunto" name="assunto" 
                               value="<?php echo isset($_GET['assunto']) ? htmlspecialchars($_GET['assunto']) : ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="interessado" class="form-label">Interessado</label>
                        <input type="text" class="form-control" id="interessado" name="interessado" 
                               value="<?php echo isset($_GET['interessado']) ? htmlspecialchars($_GET['interessado']) : ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="relator" class="form-label">Relator</label>
                        <select name="relator" id="relator" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach ($data['relatores'] as $relator): ?>
                                <option value="<?php echo $relator['id']; ?>" 
                                    <?php echo isset($_GET['relator']) && $_GET['relator'] == $relator['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($relator['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                               value="<?php echo isset($_GET['data_inicio']) ? $_GET['data_inicio'] : ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim" 
                               value="<?php echo isset($_GET['data_fim']) ? $_GET['data_fim'] : ''; ?>">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="index.php?route=pareceres" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Pareceres -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Pareceres</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Processo</th>
                                <th>Assunto</th>
                                <th>Interessado</th>
                                <th>Relator</th>
                                <th>Data Entrada</th>
                                <th>Data Limite</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['pareceres'])): ?>
                                <tr>
                                    <td colspan="9" class="text-center">Nenhum parecer encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['pareceres'] as $parecer): ?>
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
                                    
                                    $dataLimite = new DateTime($parecer['data_limite']);
                                    $hoje = new DateTime();
                                    $atrasado = $parecer['status'] !== 'concluido' && $dataLimite < $hoje;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($parecer['numero_processo']); ?></td>
                                        <td><?php echo htmlspecialchars($parecer['assunto']); ?></td>
                                        <td><?php echo htmlspecialchars($parecer['interessado']); ?></td>
                                        <td><?php echo htmlspecialchars($parecer['relator_nome']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($parecer['data_entrada'])); ?></td>
                                        <td class="<?php echo $atrasado ? 'text-danger' : ''; ?>">
                                            <?php echo date('d/m/Y', strtotime($parecer['data_limite'])); ?>
                                            <?php if ($atrasado): ?>
                                                <span class="badge bg-danger">Atrasado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary" style="padding: 0.5em 0.8em; font-size: 0.85em;">
                                                <?php echo $parecer['tipo'] === 'licitacao' ? 'Licitação' : 'Administrativo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusClass; ?>" style="padding: 0.5em 0.8em; font-size: 0.85em;">
                                                <?php echo $statusLabel; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="index.php?route=parecer/visualizar&id=<?php echo $parecer['id']; ?>" 
                                                   class="btn btn-sm btn-outline-info me-1" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="index.php?route=parecer/editar&id=<?php echo $parecer['id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if (isAdmin()): ?>
                                                <a href="index.php?route=parecer/excluir&id=<?php echo $parecer['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger ms-1" title="Excluir"
                                                   onclick="return confirm('Tem certeza que deseja excluir este parecer?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <?php if ($data['totalPages'] > 1): ?>
                    <?php
                    // Incluir o helper de paginação
                    require_once 'src/helpers/pagination_helper.php';
                    
                    // Preparar parâmetros de query para manter os filtros
                    $queryParams = array_filter([
                        'route' => 'pareceres',
                        'status' => $_GET['status'] ?? '',
                        'tipo' => $_GET['tipo'] ?? '',
                        'processo' => $_GET['processo'] ?? '',
                        'assunto' => $_GET['assunto'] ?? '',
                        'interessado' => $_GET['interessado'] ?? '',
                        'relator' => $_GET['relator'] ?? '',
                        'data_inicio' => $_GET['data_inicio'] ?? '',
                        'data_fim' => $_GET['data_fim'] ?? ''
                    ]);
                    
                    // Gerar paginação inteligente
                    echo generateSmartPagination(
                        $data['currentPage'], 
                        $data['totalPages'], 
                        'index.php', 
                        $queryParams
                    );
                    ?>
                    
                    <!-- Informações da paginação -->
                    <div class="text-center text-muted mt-2">
                        <?php
                        echo generatePaginationInfo($data['currentPage'], $data['totalPages'], 10, $data['totalItems']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>