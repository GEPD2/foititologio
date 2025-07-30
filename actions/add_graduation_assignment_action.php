<?php
// απόλυτο μονοπάτι
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
// Έλεγχος αν η αίτηση είναι POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?error=invalid_request");
    exit();
}
// Λήψη και επικύρωση των δεδομένων
$id = 'GA' . uniqid(); // Αυτόματη δημιουργία ID
$title = trim($_POST['title'] ?? '');
$must_do = trim($_POST['must_do'] ?? 'NO') === 'YES' ? 'YES' : 'NO';
$latest_month_to_finish = trim($_POST['latest_month_to_finish'] ?? '');
$professor_id = trim($_POST['professor_id'] ?? '');
$student_registration_number = trim($_POST['student_registration_number'] ?? '');
// Validation
$required = ['title', 'latest_month_to_finish', 'professor_id', 'student_registration_number'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        header("Location: ../status.php?status=error&type=missing_field&field-$field");
        exit();
    }
}
if (!preg_match('/^[A-Za-zΑ-Ωα-ω0-9\s\-,\.]+$/u', $title)) {
    header("Location: ../status.php?error=invalid_title");
    exit();
}
try {
    // Παίρνουμε τη σύνδεση από τη βάση
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // Εισαγωγή πτυχιακής
    $sql = "INSERT INTO Graduation_Assignment (
                id, 
                title, 
                must_do, 
                latest_month_to_finish, 
                professor_id, 
                student_registration_number
            ) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssss",
        $id,
        $title,
        $must_do,
        $latest_month_to_finish,
        $professor_id,
        $student_registration_number
    );
    // επιτυχής εισαγωγή
    if ($stmt->execute()) {
        header("Location: ../status.php?status=success&type=assignment_added");
    } else {
        header("Location: ../status.php?status=error&type=db_error");
    }
    exit();
} catch (Exception $e) {
    error_log("Graduation assignment error: " . $e->getMessage());
    $error = strpos($e->getMessage(), 'foreign key constraint') !== false ? 
             'invalid_reference' : 'db_exception';
    header("Location: ../status.php?status&type=error-$error");
    exit();
}