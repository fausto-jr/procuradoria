<?php

/**
 * Gera uma paginação inteligente que mostra apenas páginas relevantes
 * 
 * @param int $currentPage Página atual
 * @param int $totalPages Total de páginas
 * @param string $baseUrl URL base para os links
 * @param array $queryParams Parâmetros de query string a serem mantidos
 * @param int $maxVisible Máximo de páginas visíveis (padrão: 7)
 * @return string HTML da paginação
 */
function generateSmartPagination($currentPage, $totalPages, $baseUrl, $queryParams = [], $maxVisible = 7) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Navegação de páginas" class="mt-4">';
    $html .= '<ul class="pagination justify-content-center">';
    
    // Botão "Anterior"
    if ($currentPage > 1) {
        $prevUrl = buildPaginationUrl($baseUrl, $currentPage - 1, $queryParams);
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $prevUrl . '" aria-label="Anterior">';
        $html .= '<span aria-hidden="true">&laquo;</span>';
        $html .= '</a>';
        $html .= '</li>';
    } else {
        $html .= '<li class="page-item disabled">';
        $html .= '<span class="page-link" aria-label="Anterior">';
        $html .= '<span aria-hidden="true">&laquo;</span>';
        $html .= '</span>';
        $html .= '</li>';
    }
    
    // Calcular quais páginas mostrar
    $pages = calculateVisiblePages($currentPage, $totalPages, $maxVisible);
    
    foreach ($pages as $page) {
        if ($page === '...') {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link">...</span>';
            $html .= '</li>';
        } else {
            $pageUrl = buildPaginationUrl($baseUrl, $page, $queryParams);
            $isActive = $page == $currentPage;
            
            $html .= '<li class="page-item ' . ($isActive ? 'active' : '') . '">';
            $html .= '<a class="page-link" href="' . $pageUrl . '">' . $page . '</a>';
            $html .= '</li>';
        }
    }
    
    // Botão "Próximo"
    if ($currentPage < $totalPages) {
        $nextUrl = buildPaginationUrl($baseUrl, $currentPage + 1, $queryParams);
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $nextUrl . '" aria-label="Próximo">';
        $html .= '<span aria-hidden="true">&raquo;</span>';
        $html .= '</a>';
        $html .= '</li>';
    } else {
        $html .= '<li class="page-item disabled">';
        $html .= '<span class="page-link" aria-label="Próximo">';
        $html .= '<span aria-hidden="true">&raquo;</span>';
        $html .= '</span>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</nav>';
    
    return $html;
}

/**
 * Calcula quais páginas devem ser visíveis na paginação
 */
function calculateVisiblePages($currentPage, $totalPages, $maxVisible) {
    if ($totalPages <= $maxVisible) {
        return range(1, $totalPages);
    }
    
    $pages = [];
    $halfVisible = floor($maxVisible / 2);
    
    // Sempre mostrar a primeira página
    $pages[] = 1;
    
    // Calcular o range ao redor da página atual
    $start = max(2, $currentPage - $halfVisible);
    $end = min($totalPages - 1, $currentPage + $halfVisible);
    
    // Ajustar se estivermos muito próximos do início
    if ($currentPage <= $halfVisible + 1) {
        $end = min($totalPages - 1, $maxVisible - 1);
    }
    
    // Ajustar se estivermos muito próximos do fim
    if ($currentPage >= $totalPages - $halfVisible) {
        $start = max(2, $totalPages - $maxVisible + 2);
    }
    
    // Adicionar "..." se necessário antes do range
    if ($start > 2) {
        $pages[] = '...';
    }
    
    // Adicionar páginas do range
    for ($i = $start; $i <= $end; $i++) {
        $pages[] = $i;
    }
    
    // Adicionar "..." se necessário depois do range
    if ($end < $totalPages - 1) {
        $pages[] = '...';
    }
    
    // Sempre mostrar a última página (se não for a primeira)
    if ($totalPages > 1) {
        $pages[] = $totalPages;
    }
    
    return $pages;
}

/**
 * Constrói a URL para uma página específica mantendo os parâmetros de query
 */
function buildPaginationUrl($baseUrl, $page, $queryParams) {
    $queryParams['page'] = $page;
    $queryString = http_build_query($queryParams);
    return $baseUrl . ($queryString ? '?' . $queryString : '');
}

/**
 * Gera informações sobre a paginação (ex: "Mostrando 1-10 de 50 resultados")
 */
function generatePaginationInfo($currentPage, $totalPages, $itemsPerPage, $totalItems) {
    if ($totalItems == 0) {
        return 'Nenhum resultado encontrado';
    }
    
    $start = ($currentPage - 1) * $itemsPerPage + 1;
    $end = min($currentPage * $itemsPerPage, $totalItems);
    
    return "Mostrando {$start}-{$end} de {$totalItems} resultados";
}