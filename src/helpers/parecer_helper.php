<?php

/**
 * Retorna o label formatado para o status do parecer
 */
function getStatusLabel($status) {
    $labels = [
        'pendente' => 'Pendente',
        'em_analise' => 'Em Análise',
        'concluido' => 'Concluído'
    ];
    return $labels[$status] ?? $status;
}

/**
 * Retorna a classe CSS para o badge de status
 */
function getStatusBadgeClass($status) {
    $classes = [
        'pendente' => 'warning',
        'em_analise' => 'info',
        'concluido' => 'success'
    ];
    return $classes[$status] ?? 'secondary';
}