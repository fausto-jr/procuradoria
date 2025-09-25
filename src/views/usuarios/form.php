<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3"><?php echo isset($usuario) ? 'Editar Usuário' : 'Novo Usuário'; ?></h1>
            <a href="index.php?route=usuarios" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome*</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?php echo isset($usuario) ? htmlspecialchars($usuario['nome']) : ''; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email*</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($usuario) ? htmlspecialchars($usuario['email']) : ''; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="senha" class="form-label"><?php echo isset($usuario) ? 'Nova Senha (deixe em branco para manter a atual)' : 'Senha*'; ?></label>
                        <input type="password" class="form-control" id="senha" name="senha" 
                               <?php echo !isset($usuario) ? 'required' : ''; ?>
                               minlength="6">
                        <?php if (!isset($usuario)): ?>
                            <div class="form-text">A senha deve ter no mínimo 6 caracteres.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label for="nivel_acesso" class="form-label">Nível de Acesso*</label>
                        <select class="form-select" id="nivel_acesso" name="nivel_acesso" required>
                            <option value="">Selecione...</option>
                            <option value="advogado" <?php echo isset($usuario) && $usuario['nivel_acesso'] === 'advogado' ? 'selected' : ''; ?>>
                                Advogado
                            </option>
                            <option value="admin" <?php echo isset($usuario) && $usuario['nivel_acesso'] === 'admin' ? 'selected' : ''; ?>>
                                Administrador
                            </option>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" 
                                   <?php echo !isset($usuario) || $usuario['ativo'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="ativo">
                                Usuário Ativo
                            </label>
                        </div>
                    </div>

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