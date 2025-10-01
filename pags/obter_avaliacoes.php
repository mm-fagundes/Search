<?php
header('Content-Type: application/json');
include 'connection.php';

$prestador_id = intval($_GET['prestador_id'] ?? 0);

if ($prestador_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID do prestador inválido']);
    exit;
}

try {
    // Buscar avaliações com informações do cliente
    $sql = "SELECT a.*, c.nome as cliente_nome 
            FROM avaliacoes a 
            JOIN clientes c ON a.cliente_id = c.id 
            WHERE a.prestador_id = ? 
            ORDER BY a.criado_em DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prestador_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $avaliacoes = [];
    while ($row = $result->fetch_assoc()) {
        $avaliacoes[] = [
            'id' => $row['id'],
            'nota' => (int)$row['nota'],
            'comentario' => $row['comentario'],
            'cliente_nome' => $row['cliente_nome'],
            'criado_em' => $row['criado_em']
        ];
    }
    
    // Calcular estatísticas
    $total_avaliacoes = count($avaliacoes);
    $media_avaliacao = 0;
    $distribuicao_notas = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    
    if ($total_avaliacoes > 0) {
        $soma_notas = 0;
        foreach ($avaliacoes as $avaliacao) {
            $nota = $avaliacao['nota'];
            $soma_notas += $nota;
            $distribuicao_notas[$nota]++;
        }
        $media_avaliacao = round($soma_notas / $total_avaliacoes, 1);
    }
    
    echo json_encode([
        'success' => true,
        'data' => [
            'avaliacoes' => $avaliacoes,
            'estatisticas' => [
                'total' => $total_avaliacoes,
                'media' => $media_avaliacao,
                'distribuicao' => $distribuicao_notas
            ]
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar avaliações: ' . $e->getMessage()
    ]);
}

$conn->close();
?>

