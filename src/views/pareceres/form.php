<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3"><?php echo isset($parecer) ? 'Editar Parecer' : 'Novo Parecer'; ?></h1>
            <a href="index.php?route=pareceres" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <?php if (isAdmin()): ?>
                    <div class="col-md-6">
                        <label for="numero_processo" class="form-label">Número do Processo*</label>
                        <input type="text" class="form-control" id="numero_processo" name="numero_processo" 
                               value="<?php echo isset($parecer) ? htmlspecialchars($parecer['numero_processo']) : ''; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="interessado" class="form-label">Interessado*</label>
                        <input type="text" class="form-control" id="interessado" name="interessado" 
                               value="<?php echo isset($parecer) ? htmlspecialchars($parecer['interessado']) : ''; ?>" required>
                    </div>

                    <div class="col-12">
                        <label for="assunto" class="form-label">Assunto*</label>
                        <input type="text" class="form-control" id="assunto" name="assunto" 
                               value="<?php echo isset($parecer) ? htmlspecialchars($parecer['assunto']) : ''; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="relator_id" class="form-label">Relator*</label>
                        <select class="form-select" id="relator_id" name="relator_id" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($relatores as $relator): ?>
                                <option value="<?php echo $relator['id']; ?>" 
                                    <?php echo isset($parecer) && $parecer['relator_id'] == $relator['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($relator['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="data_entrada" class="form-label">Data de Entrada*</label>
                        <input type="date" class="form-control" id="data_entrada" name="data_entrada" 
                               value="<?php echo isset($parecer) ? $parecer['data_entrada'] : date('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="prazo_dias" class="form-label">Prazo em Dias*</label>
                        <input type="number" class="form-control" id="prazo_dias" name="prazo_dias" min="1" 
                               value="<?php echo isset($parecer) ? $parecer['prazo_dias'] : '30'; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="tipo" class="form-label">Tipo do Parecer*</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="licitacao" <?php echo isset($parecer) && $parecer['tipo'] === 'licitacao' ? 'selected' : ''; ?>>
                                Licitação
                            </option>
                            <option value="administrativo" <?php echo isset($parecer) && $parecer['tipo'] === 'administrativo' ? 'selected' : ''; ?>>
                                Administrativo
                            </option>
                        </select>
                    </div>
                    <?php else: ?>
                    <div class="col-md-6">
                        <p><strong>Número do Processo:</strong> <?php echo htmlspecialchars($parecer['numero_processo']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Interessado:</strong> <?php echo htmlspecialchars($parecer['interessado']); ?></p>
                    </div>
                    <div class="col-12">
                        <p><strong>Assunto:</strong> <?php echo htmlspecialchars($parecer['assunto']); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($parecer)): ?>
                        <div class="col-md-12">
                            <label for="status" class="form-label">Status*</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pendente" <?php echo $parecer['status'] === 'pendente' ? 'selected' : ''; ?>>
                                    Pendente
                                </option>
                                <option value="em_analise" <?php echo $parecer['status'] === 'em_analise' ? 'selected' : ''; ?>>
                                    Em Análise
                                </option>
                                <option value="concluido" <?php echo $parecer['status'] === 'concluido' ? 'selected' : ''; ?>>
                                    Concluído
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="parecer_texto" class="form-label">Texto do Parecer</label>
                            <textarea class="form-control" id="parecer_texto" name="parecer_texto" rows="10"><?php 
                                echo htmlspecialchars($parecer['parecer_texto'] ?? ''); 
                            ?></textarea>
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>