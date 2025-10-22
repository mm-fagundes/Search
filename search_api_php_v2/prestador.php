"'<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id_prestador = $_GET['id'];

        $stmt = $conn->prepare("SELECT id_prestador, nome, email, cpf, telefone FROM prestadores WHERE id_prestador = ?");
        $stmt->bind_param("i", $id_prestador);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $prestador = $result->fetch_assoc();
            echo json_encode(['success' => true, 'prestador' => $prestador]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Prestador not found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Prestador ID not provided']);
    }
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>'"
