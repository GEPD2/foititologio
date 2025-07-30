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
$code = trim($_POST['code'] ?? '');               // Κωδικός τμήματος (πχ "CS")
$name_ = trim($_POST['name_'] ?? '');             // Όνομα τμήματος (πχ "Πληροφορική")
$email = trim($_POST['email'] ?? '');             // Email τμήματος
$phone = trim($_POST['phone'] ?? '');             // Τηλέφωνο τμήματος
$region = trim($_POST['region'] ?? '');           // Περιοχή
$website = trim($_POST['website'] ?? '');         // Ιστοσελίδα
$headquarters = trim($_POST['headquarters'] ?? '');// Έδρα
$min_semesters = trim($_POST['min_semesters'] ?? 8); // Ελάχιστα εξάμηνα
$social_media = trim($_POST['social_media'] ?? '');  // Κοινωνικά δίκτυα
// Έλεγχος υποχρεωτικών πεδίων
$required_fields = [
    'code' => $code,
    'name_' => $name_,
    'email' => $email,
    'phone' => $phone,
    'region' => $region,
    'headquarters' => $headquarters
];
foreach ($required_fields as $field => $value) {
    if (empty($value)) {
        header("Location: ../status.php?status=error&type=missing_field");
        exit();
    }
}
// Επικύρωση email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../status.php?status=error&type=invalid_email");
    exit();
}
// Επικύρωση τηλεφώνου
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    header("Location: ../status.php?status=error&type=invalid_phone");
    exit();
}
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // εισαγωγή στον πίνακα
    $sql = "INSERT INTO Class (
                code, 
                name_, 
                email, 
                phone, 
                region, 
                website, 
                headquarters, 
                min_semesters, 
                social_media
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssis", 
        $code, 
        $name_, 
        $email, 
        $phone, 
        $region, 
        $website, 
        $headquarters, 
        $min_semesters, 
        $social_media
    );
    // όταν η εισαγωγή είναι επιτυχής
    if ($stmt->execute()) {
        header("Location: ../status.php?status=success&type=class_added");
        exit();
    } else {
        header("Location: ../status.php?status=error&type=db_error");
        exit();
    }
    exit();
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        header("Location: ../status.php?status=error&type=duplicate_code");
        exit();
    } else {
        header("Location: ../status.php?status=error&type=db_error");
        exit();
    }
    exit();
}