<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">Usuários</h1>
            <a href="index.php?route=usuario/novo" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo Usuário
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Nível de Acesso</th>
                                <th>Status</th>
                                <th>Último Acesso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($usuarios)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td>
                                            <?php if ($usuario['nivel_acesso'] === 'admin'): ?>
                                                <span class="badge bg-danger">Administrador</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Advogado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($usuario['ativo']): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($usuario['updated_at'])); ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="index.php?route=usuario/editar&id=<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if (isAdmin() && $usuario['id'] != $_SESSION['user_id']): ?>
                                                <a href="index.php?route=usuario/excluir&id=<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger ms-1" title="Excluir"
                                                   onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
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
            </div>
        </div>
    </div>
</div>