"<?php
require_once 'config.php';

$request_uri = $_SERVER['REQUEST_URI'];

// Simple router
switch ($request_uri) {
    case '/auth':
        require_once 'auth.php';
        break;
    case (preg_match('/\/prestador\/(\d+)/', $request_uri, $matches) ? true : false):
        $_GET['id'] = $matches[1];
        require_once 'prestador.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Endpoint not found']);
        break;
}
"
