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
$id = trim($_POST['id'] ?? '');                 // Κωδικός καθηγητή (πχ "P100")
$name = trim($_POST['name'] ?? '');             // Όνομα (πχ "Μαρία")
$last_name = trim($_POST['last_name'] ?? '');   // Επώνυμο (πχ "Παπαδοπούλου")
$specialty = trim($_POST['specialty'] ?? '');   // Ειδικότητα (πχ "Βάσεις Δεδομένων")
$position = trim($_POST['position'] ?? '');     // Ακαδημαϊκή θέση
$phone = trim($_POST['phone'] ?? '');           // Τηλέφωνο
$address = trim($_POST['address'] ?? '');       // Διεύθυνση
$email = trim($_POST['email'] ?? '');           // Email
$class_code = trim($_POST['class_code'] ?? ''); // Τμήμα
// Υποχρεωτικά πεδία
if (empty($id) || empty($name) || empty($last_name) || empty($specialty) || 
    empty($position) || empty($class_code) || empty($email)) {
    header("Location: ../status.php?status=error&type=missing_fields");
    exit();
}
// Επιπλέον validation
if (!preg_match('/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώπρστυφχψς\s]+$/', $name) || 
    !preg_match('/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώπρστυφχψς\s]+$/', $last_name)) {
    header("Location: ../status.php?status=error&type=invalid_name");
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../status.php?status=error&type=invalid_email");
    exit();
}
if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
    header("Location: ../status.php?status=error&type=invalid_phone");
    exit();
}
try {
    // Παίρνουμε τη σύνδεση με τη βάση
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // Εισαγωγή στον πίνακα Professor
    $sql = "INSERT INTO Professor (id, name_, last_name, specialty, position, phone, address_, email, class_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $id, $name, $last_name, $specialty, $position, $phone, $address, $email, $class_code);
    // όταν η εισαγωγή είναι επιτυχής
    if ($stmt->execute()) {
        header("Location: ../status.php?status=success&type=professor_added");
        exit();
    } else {
        header("Location: ../status.php?status=error&type=db_error");
        exit();
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    // Ειδική αντιμετώπιση για διπλό email ή ID
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        if (strpos($e->getMessage(), 'email') !== false) {
            header("Location: ../status.php?status=error&type=duplicate_email");
        } else {
            header("Location: ../status.php?status=error&type=duplicate_id");
        }
    } else {
        header("Location: ../status.php?status=error&type=db_exception");
    }
    exit();
}