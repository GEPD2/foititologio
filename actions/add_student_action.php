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
$first_name = trim($_POST['first_name'] ?? ''); // όνομα φοιτητή
$last_name = trim($_POST['last_name'] ?? ''); // επώνθμο φοιτητή
$father_name = trim($_POST['father_name'] ?? ''); // όνομα πατέρα
$address_ = trim($_POST['address_'] ?? ''); // διεύθυνση φοιτητή
$email = trim($_POST['email'] ?? ''); // email φοιτητή
$class_code = trim($_POST['class_code'] ?? ''); // κωδικός τμήματος
$professor_id = trim($_POST['professor_id'] ?? null); // κωδικός καθηγητή
$student_type = trim($_POST['student_type'] ?? ''); //είδος μαθητή (προπτυχιακός,μεταπτυχιακός ή )
$phone = trim($_POST['phone'] ?? ''); // αριθμός τηλεφώνου
$registration_date = trim($_POST['registration_date'] ?? ''); // ημερομηνία εγγτραφής
// Υποχρεωτικά πεδία
if (empty($first_name) || empty($last_name) || empty($father_name) ||
    empty($address_) || empty($phone) || empty($email) || empty($registration_date) ||
    empty($class_code) || empty($student_type)) {
    header("Location: ../status.php?status=error&type=db_error");
    exit();
}
// Επιπλέον validation
if (!preg_match('/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώπρστυφχψς\s]+$/', $last_name) || 
    !preg_match('/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώπρστυφχψς\s]+$/', $first_name)) {
    header("Location: ../status.php?status=error&type=db_error");
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
if (!in_array($student_type, ['undergraduate', 'postgraduate', 'teaching'])) {
    header("Location: ../status.php?status=error&type=invalid_type_of_student");
    exit();
}
if (!empty($father_name) && !preg_match('/^[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώ\s]+$/u', $father_name)) {
    header("Location: ../status.php?status=error&type=invalid_fathers_name");
    exit();
}
// Δημιουργία registration_number
$registration_number = 'UP' . strtoupper(uniqid());
try {
    // Παίρνουμε τη σύνδεση με τη βάση
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // Εισαγωγή στον πίνακα Student
    $sql = "INSERT INTO Student (registration_number, name_, last_name, father_name, address_, phone, email, registration_date, class_code, professor_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $registration_number, $first_name, $last_name, $father_name, $address_, $phone, $email, $registration_date, $class_code, $professor_id);
    if ($stmt->execute()) {
        // Εισαγωγή σε υπο-πίνακα ανάλογα με τον τύπο
        if ($student_type === 'undergraduate') {
            $type_of_school = $_POST['type_of_school'] ?? '';
            $type_of_exams = $_POST['type_of_exams'] ?? '';
            $name_of_school = $_POST['name_of_school'] ?? '';
            $number_of_entrance = $_POST['number_of_entrance'] ?? 0;
            $admission_rank = $_POST['admission_rank'] ?? 0;
            $ug_sql = "INSERT INTO Undergraduate (registration_number, type_of_school, type_of_exams, name_of_school, number_of_entrance, admission_rank) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($ug_sql);
            $stmt->bind_param("ssssii", $registration_number, $type_of_school, $type_of_exams, $name_of_school, $number_of_entrance, $admission_rank);
            $stmt->execute();
        } elseif ($student_type === 'postgraduate') {
            $first_degree_name = $_POST['first_degree_name'] ?? '';
            $pg_sql = "INSERT INTO Postgraduate (registration_number, first_degree_name) 
                      VALUES (?, ?)";
            $stmt = $conn->prepare($pg_sql);
            $stmt->bind_param("ss", $registration_number, $first_degree_name);
            $stmt->execute();
        } elseif ($student_type === 'teaching') {
            $university=$_POST['university'] ?? '';
            $t_sql="INSERT INTO Teaching_Competence (registration_number, university) VALUES (?, ?)";
            $stmt = $conn->prepare($t_sql);
            $stmt->bind_param("ss", $registration_number, $university);
            $stmt->execute();
        }
        //όταν γίνει επιτυχής η εισαγωγή
        header("Location: ../status.php?status=success&type=student_added");
        exit();
    } else {
        //αποτυχής εισαγωγή
        header("Location: ../status.php?status=error&type=db_error");
        exit();
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: ../status.php?status=error&type=db_error");
    exit();
}