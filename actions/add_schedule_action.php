<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?error=invalid_request");
    exit();
}

$schedule_code = 'SCH' . uniqid();
$subject_id = trim($_POST['subject_id'] ?? '');
$schedule_category_pk = trim($_POST['schedule_category_pk'] ?? '');
$type_of_subject = trim($_POST['type_of_subject'] ?? '');

// Έλεγχος υποχρεωτικών πεδίων
if (empty($subject_id)) {
    header("Location: ../status.php?status=error&type=missing_field_subject_id");
    exit();
}

// Έλεγχος ότι έχει δοθεί είτε κατηγορία είτε τύπος μαθήματος
if (empty($schedule_category_pk) && empty($type_of_subject)) {
    header("Location: ../status.php?status=error&type=missing_category_or_type");
    exit();
}

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    $conn->begin_transaction();

    // Εισαγωγή νέας κατηγορίας αν χρειάζεται
    if (!empty($type_of_subject) && empty($schedule_category_pk)) {
        // Έλεγχος αν ο τύπος μαθήματος υπάρχει ήδη
        $check_sql = "SELECT schedule_category_pk FROM Schedule_Category WHERE type_of_subject = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $type_of_subject);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $schedule_category_pk = $row['schedule_category_pk'];
        } else {
            $schedule_category_pk = 'CAT' . uniqid();
            $sql = "INSERT INTO Schedule_Category (schedule_category_pk, type_of_subject) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $schedule_category_pk, $type_of_subject);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert schedule category.");
            }
        }
    }

    // Έλεγχος αν ο συνδυασμός υπάρχει ήδη
    $check_sql = "SELECT schedule_code FROM Schedule WHERE subject_id = ? AND schedule_category_pk = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $subject_id, $schedule_category_pk);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception("Schedule already exists for this subject and category.");
    }

    // Εισαγωγή νέου προγράμματος
    $sql = "INSERT INTO Schedule (schedule_code, subject_id, schedule_category_pk) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $schedule_code, $subject_id, $schedule_category_pk);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to insert schedule.");
    }

    $conn->commit();
    header("Location: ../status.php?status=success&type=schedule_added");
    exit();
    
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    error_log("Schedule Error: " . $e->getMessage());
    $errorType = str_contains($e->getMessage(), 'foreign key constraint') ? 'invalid_reference' : 'db_exception';
    header("Location: ../status.php?status=error&type=$errorType");
    exit();
}
?>