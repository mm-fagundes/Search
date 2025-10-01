<?php
header('Content-Type: application/json');
include 'connection.php';

// Parâmetros de busca
$termo_busca = $_GET['busca'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$localizacao = $_GET['localizacao'] ?? '';
$avaliacao = $_GET['avaliacao'] ?? '';
$ordenacao = $_GET['ordenacao'] ?? 'relevancia';

// Construir query base
$sql = "SELECT p.*, 
        COALESCE(AVG(a.nota), 0) as media_avaliacao,
        COUNT(a.id) as total_avaliacoes
        FROM prestadores p 
        LEFT JOIN avaliacoes a ON p.id = a.prestador_id";

$where_conditions = [];
$params = [];
$types = '';

// Filtro por termo de busca (nome ou nicho)
if (!empty($termo_busca)) {
    $where_conditions[] = "(p.nome LIKE ? OR p.nicho LIKE ? OR p.descricao LIKE ?)";
    $search_term = "%$termo_busca%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'sss';
}

// Filtro por categoria/nicho
if (!empty($categoria) && $categoria !== 'todas') {
    $where_conditions[] = "p.nicho = ?";
    $params[] = $categoria;
    $types .= 's';
}

// Filtro por localização (endereço)
if (!empty($localizacao)) {
    $where_conditions[] = "p.endereco LIKE ?";
    $params[] = "%$localizacao%";
    $types .= 's';
}

// Adicionar WHERE se houver condições
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Agrupar por prestador
$sql .= " GROUP BY p.id";

// Filtro por avaliação (após GROUP BY)
if (!empty($avaliacao) && is_numeric($avaliacao)) {
    $sql .= " HAVING media_avaliacao >= $avaliacao";
}

// Ordenação
switch ($ordenacao) {
    case 'melhor_avaliados':
        $sql .= " ORDER BY media_avaliacao DESC, total_avaliacoes DESC";
        break;
    case 'mais_avaliacoes':
        $sql .= " ORDER BY total_avaliacoes DESC";
        break;
    case 'nome':
        $sql .= " ORDER BY p.nome ASC";
        break;
    case 'mais_recentes':
        $sql .= " ORDER BY p.criado_em DESC";
        break;
    default: // relevancia
        if (!empty($termo_busca)) {
            $sql .= " ORDER BY 
                CASE 
                    WHEN p.nome LIKE '%$termo_busca%' THEN 1
                    WHEN p.nicho LIKE '%$termo_busca%' THEN 2
                    ELSE 3
                END, media_avaliacao DESC";
        } else {
            $sql .= " ORDER BY media_avaliacao DESC, total_avaliacoes DESC";
        }
        break;
}

try {
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }
    
    $prestadores = [];
    
    while ($row = $result->fetch_assoc()) {
        $prestadores[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'nicho' => $row['nicho'],
            'telefone' => $row['telefone'],
            'endereco' => $row['endereco'],
            'descricao' => $row['descricao'],
            'foto' => $row['foto'] ? '../uploads/' . $row['foto'] : null,
            'media_avaliacao' => round($row['media_avaliacao'], 1),
            'total_avaliacoes' => (int)$row['total_avaliacoes'],
            'criado_em' => $row['criado_em']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $prestadores,
        'total' => count($prestadores),
        'filtros_aplicados' => [
            'busca' => $termo_busca,
            'categoria' => $categoria,
            'localizacao' => $localizacao,
            'avaliacao' => $avaliacao,
            'ordenacao' => $ordenacao
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro na busca: ' . $e->getMessage()
    ]);
}

$conn->close();
?>

