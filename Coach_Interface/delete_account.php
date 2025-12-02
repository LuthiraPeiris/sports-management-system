<?php
// Start session BEFORE any output
session_start();

// Force JSON content type 
header('Content-Type: application/json');

// Include database connection
include '../Dashboard/db.php';

// Response array
$response = ['success' => false, 'message' => ''];

try {
    // Check if user is logged in and is a coach
    if (empty($_SESSION['user_id'])) {
        throw new Exception('User not logged in.');
    }

    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'coach') {
        throw new Exception('Unauthorized: only coaches can delete accounts.');
    }

    $user_id = (int)$_SESSION['user_id'];

    // First delete from coach table (remove foreign key dependency)
    $delete_coach = "DELETE FROM coach WHERE user_id = ?";
    $stmt_coach = $conn->prepare($delete_coach);
    if (!$stmt_coach) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    $stmt_coach->bind_param("i", $user_id);
    if (!$stmt_coach->execute()) {
        throw new Exception('Failed to delete coach record: ' . $stmt_coach->error);
    }
    $stmt_coach->close();

    // Delete from sport_coach table (if any records exist)
    $delete_sport_coach = "DELETE FROM sport_coach WHERE coach_id = ?";
    $stmt_sport_coach = $conn->prepare($delete_sport_coach);
    if (!$stmt_sport_coach) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    $stmt_sport_coach->bind_param("i", $user_id);
    if (!$stmt_sport_coach->execute()) {
        throw new Exception('Failed to delete sport_coach records: ' . $stmt_sport_coach->error);
    }
    $stmt_sport_coach->close();

    // Now delete from users table
    $sql = "DELETE FROM users WHERE id = ? AND role = 'coach'";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }

    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        throw new Exception('Deletion failed: ' . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        // Success
        session_destroy();
        $response['success'] = true;
        $response['message'] = 'Account deleted successfully.';
    } else {
        throw new Exception('No coach account found to delete.');
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Always output JSON
echo json_encode($response);
exit;
