"'<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id_prestador, nome, email, senha FROM prestadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $prestador = $result->fetch_assoc();
        // In a real application, you should hash and verify passwords securely
        // For simplicity, we are comparing plain text passwords here as requested
        if ($password === $prestador['senha']) {
            echo json_encode(['success' => true, 'message' => 'Login successful', 'prestador' => ['id_prestador' => $prestador['id_prestador'], 'nome' => $prestador['nome'], 'email' => $prestador['email']]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>'"
