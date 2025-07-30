<?php
// απόλυτο μονοπάτι
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
// Ελέγχουμε αν η αίτηση είναι POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?error=invalid_request");
    exit();
}
// Λήψη και επικύρωση των δεδομένων
$subject_id = trim($_POST['subject_id'] ?? '');       // Κωδικός μαθήματος (πχ "CS101")
$title = trim($_POST['title'] ?? '');                // Τίτλος μαθήματος
$semester = trim($_POST['semester'] ?? '');          // Εξάμηνο
$month_ = trim($_POST['month_'] ?? '');              // Μήνας διδασκαλίας
$ects = trim($_POST['ects'] ?? '');                  // Πιστωτικές μονάδες
$importance = trim($_POST['importance'] ?? '');      // Βαρύτητα
$category = trim($_POST['category'] ?? '');          // Κατηγορία
$program = trim($_POST['program'] ?? '');            // Πρόγραμμα σπουδών
$min_duration_months = trim($_POST['min_duration_months'] ?? 0);  // Ελάχιστος χρόνος
$max_duration_months = trim($_POST['max_duration_months'] ?? 0);  // Μέγιστος χρόνος
$class_code = trim($_POST['class_code'] ?? '');      // Κωδικός τμήματος
// Υποχρεωτικά πεδία
$required_fields = [
    'subject_id' => $subject_id,
    'title' => $title,
    'semester' => $semester,
    'ects' => $ects,
    'importance' => $importance,
    'category' => $category,
    'program' => $program,
    'class_code' => $class_code
];
foreach ($required_fields as $field => $value) {
    if (empty($value)) {
        header("Location: ../status.php?status=error&type=missing_field-".urlencode($field));
        exit();
    }
}
// Επιπλέον validation
if (!preg_match('/^[A-Za-z0-9]{2,20}$/', $subject_id)) {
    header("Location: ../status.php?status=error&type=invalid_subject_id");
    exit();
}
if (!is_numeric($semester) || $semester < 1 || $semester > 10) {
    header("Location: ../status.php?status=error&type=invalid_semester");
    exit();
}
if (!is_numeric($ects) || $ects < 1 || $ects > 30) {
    header("Location: ../status.php?status=error&type=invalid_ects");
    exit();
}
if (!in_array($importance, ['Υψηλή', 'Μέτρια', 'Χαμηλή'])) {
    header("Location: ../status.php?status=error&type=invalid_importance");
    exit();
}
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // Αρχή transaction για atomic operations
    $conn->begin_transaction();
    // Εισαγωγή του μαθήματος
    $sql = "INSERT INTO Subject (
                subject_id, 
                title, 
                semester, 
                month_, 
                ects, 
                importance, 
                category, 
                program, 
                min_duration_months, 
                max_duration_months, 
                class_code
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssisisssiis", 
        $subject_id, 
        $title, 
        $semester, 
        $month_, 
        $ects, 
        $importance, 
        $category, 
        $program, 
        $min_duration_months, 
        $max_duration_months, 
        $class_code
    );
    if (!$stmt->execute()) {
        throw new Exception("Failed to insert subject");
    }
    // Εισαγωγή αν είναι προαπαιτούμενο
    if (!empty($_POST['prerequisites'])) {
        $prerequisites = is_array($_POST['prerequisites']) ? $_POST['prerequisites'] : [$_POST['prerequisites']];
        $pretake_sql = "INSERT INTO Has_a_pretake (subject_subject_id, pretake_subject_id) VALUES (?, ?)";
        $pretake_stmt = $conn->prepare($pretake_sql);
        foreach ($prerequisites as $pretake_id) {
            $pretake_id = trim($pretake_id);
            if (!empty($pretake_id)) {
                $pretake_stmt->bind_param("ss", $subject_id, $pretake_id);
                if (!$pretake_stmt->execute()) {
                    throw new Exception("Failed to insert prerequisite");
                }
            }
        }
    }
    // Ολοκλήρωση όλων των εισαγωγών
    $conn->commit();
    header("Location: ../status.php?status=success&type=subject_added");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    error_log("Database error: " . $e->getMessage());
    
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        header("Location: ../status.php?status=error&type=duplicate_subject_id");
    } elseif (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
        header("Location: ../status.php?status=error&type=invalid_prerequisite");
    } else {
        header("Location: ../status.php?status=error&type=db_exception");
    }
    exit();
}