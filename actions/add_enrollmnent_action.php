<?php
// απόλυτο μονοπάτι
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
// Έλεγχος αν η αίτηση είναι POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?status=error&type=invalid_request");
    exit();
}
// Λήψη και επικύρωση των δεδομένων
$enrollment_id = 'ENR' . uniqid(); // Αυτόματη δημιουργία ID
$student_id = trim($_POST['student_id'] ?? '');
$subject_id = trim($_POST['subject_id'] ?? '');
$professor_id = trim($_POST['professor_id'] ?? '');
$enrollment_date = date('Y-m-d'); // Τρέχουσα ημερομηνία
$exam_date = !empty($_POST['exam_date']) ? trim($_POST['exam_date']) : null;
$grade = !empty($_POST['grade']) ? trim($_POST['grade']) : null;
// Validation
$required = ['student_id', 'subject_id', 'professor_id'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        header("Location: ../status.php?status=error&type=missing_field&field-$field");
        exit();
    }
}
if ($grade && (!is_numeric($grade) || $grade < 0 || $grade > 10)) {
    header("Location: ../add_enrollment.php?error=invalid_grade");
    exit();
}
try {
    // Παίρνουμε τη σύνδεση από τη βάση
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // Εισαγωγή εγγραφής
    $sql = "INSERT INTO Enrollment (
                enrollment_id, 
                student_id, 
                subject_id, 
                professor_id, 
                enrollment_date, 
                exam_date, 
                grade
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssd",
        $enrollment_id,
        $student_id,
        $subject_id,
        $professor_id,
        $enrollment_date,
        $exam_date,
        $grade
    );
    // επιτυχής εισαγωγή
    if ($stmt->execute()) {
        header("Location: ../status.php?status=success&type=enrollment_added");
    } else {
        header("Location: ../status.php?status=error&type=db_error");
        exit();
    }
    exit();
} catch (Exception $e) {
    error_log("Enrollment error: " . $e->getMessage());
    $error = strpos($e->getMessage(), 'foreign key constraint') !== false ? 
             'invalid_reference' : 'db_exception';
    header("Location: ../status.php?status=error&type=$error");
    exit();
}