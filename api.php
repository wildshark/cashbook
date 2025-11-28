<?php
header('Content-Type: application/json');
require_once 'includes/TransactionManager.php';

$csvFile = 'data/transactions.csv';
$tm = new TransactionManager($csvFile);

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            echo json_encode(['status' => 'success', 'data' => $tm->getAll()]);
            break;

        case 'add':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON input');
            }

            $date = $input['date'] ?? date('Y-m-d');
            $description = $input['description'] ?? '';
            $type = $input['type'] ?? 'expense';
            $amount = floatval($input['amount'] ?? 0);

            if (empty($description) || $amount <= 0) {
                throw new Exception('Invalid data provided');
            }

            $id = $tm->add($date, $description, $type, $amount);
            echo json_encode(['status' => 'success', 'id' => $id]);
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? '';

            if (empty($id)) {
                throw new Exception('ID required');
            }

            if ($tm->delete($id)) {
                echo json_encode(['status' => 'success']);
            } else {
                throw new Exception('Transaction not found');
            }
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
