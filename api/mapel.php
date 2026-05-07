<?php
/**
 * API — Mata Pelajaran (Admin Only)
 * POST: Reorder mapel
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/config/database.php';

header('Content-Type: application/json');

// Auth check
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action']) && $input['action'] === 'reorder') {
        $ids = $input['ids'] ?? [];
        
        if (empty($ids)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'No IDs provided']);
            exit;
        }

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("UPDATE skl_mapel SET urutan = ? WHERE id = ?");
            foreach ($ids as $index => $id) {
                $urutan = $index + 1;
                $stmt->bind_param("ii", $urutan, $id);
                $stmt->execute();
            }
            $stmt->close();
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Urutan berhasil diperbarui']);
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Gagal memperbarui urutan: ' . $e->getMessage()]);
        }
        exit;
    }
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Invalid Request']);
$conn->close();
