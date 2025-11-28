<?php
header('Content-Type: application/json');
require_once 'includes/TransactionManager.php';

$tmGeneral = new TransactionManager('data/transactions.csv');
$tmPetty = new TransactionManager('data/petty_cash.csv');

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $general = $tmGeneral->getAll();
            foreach ($general as &$g) { $g['source'] = 'general'; }
            
            $petty = $tmPetty->getAll();
            foreach ($petty as &$p) { $p['source'] = 'petty'; }

            $all = array_merge($general, $petty);
            
            // Sort by date descending
            usort($all, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });

            echo json_encode(['status' => 'success', 'data' => $all]);
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
            $payee = $input['payee'] ?? '';
            $authorized_by = $input['authorized_by'] ?? '';

            if (empty($description) || $amount <= 0) {
                throw new Exception('Invalid data provided');
            }

            // Route to appropriate storage
            if (!empty($payee) || !empty($authorized_by)) {
                $id = $tmPetty->add($date, $description, $type, $amount, $payee, $authorized_by);
            } else {
                $id = $tmGeneral->add($date, $description, $type, $amount, $payee, $authorized_by);
            }

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

            // Try deleting from general first, if not found, try petty cash
            if ($tmGeneral->delete($id)) {
                echo json_encode(['status' => 'success']);
            } elseif ($tmPetty->delete($id)) {
                echo json_encode(['status' => 'success']);
            } else {
                throw new Exception('Transaction not found');
            }
            break;

        case 'export_cashbook':
            // Export general transactions CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="cashbook_' . date('Y-m-d') . '.csv"');
            readfile('data/transactions.csv');
            exit;

        case 'export_petty':
            // Export petty cash CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="petty_cash_' . date('Y-m-d') . '.csv"');
            readfile('data/petty_cash.csv');
            exit;

        case 'delete_all':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            // Clear transactions.csv
            $file1 = fopen('data/transactions.csv', 'w');
            fputcsv($file1, ['id', 'date', 'description', 'type', 'amount', 'payee', 'authorized_by']);
            fclose($file1);
            
            // Clear petty_cash.csv
            $file2 = fopen('data/petty_cash.csv', 'w');
            fputcsv($file2, ['id', 'date', 'description', 'type', 'amount', 'payee', 'authorized_by']);
            fclose($file2);
            
            echo json_encode(['status' => 'success', 'message' => 'All data deleted']);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
