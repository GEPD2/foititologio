<?php
require_once __DIR__ . '/../includes/db.php';
// Αρχικοποίηση μεταβλητών
$search_type = $_POST['search_type'] ?? '';
$student_id = $_POST['student_id'] ?? '';
$professor_id = $_POST['professor_id'] ?? '';
$results = [];
$title = '';
// Έλεγχος αν η αίτηση είναι POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($search_type) {
        case 'B1':
            // Β.1. Μαθήματα που έχει εγγραφεί ο φοιτητής Χ
            $title = "Μαθήματα που έχει εγγραφεί ο φοιτητής $student_id";
            $stmt = $conn->prepare("
                SELECT s.subject_id, s.title, e.enrollment_date 
                FROM Enrollment e
                JOIN Subject s ON e.subject_id = s.subject_id
                WHERE e.student_id = ?
            ");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            break;
        case 'B2':
            // Β.2. Μαθήματα που έχει παρακολουθήσει ο φοιτητής Χ με βαθμούς
            $title = "Μαθήματα και βαθμοί για φοιτητή $student_id";
            $stmt = $conn->prepare("
                SELECT s.subject_id, s.title, p.name_, p.last_name, e.grade
                FROM Enrollment e
                JOIN Subject s ON e.subject_id = s.subject_id
                JOIN Professor p ON e.professor_id = p.id
                WHERE e.student_id = ? AND e.grade IS NOT NULL
            ");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            break;
        case 'B3':
            // Β.3. Όλα τα δεδομένα μαθημάτων φοιτητή Χ με καθηγητή Υ
            $title = "Μαθήματα φοιτητή $student_id με καθηγητή $professor_id";
            $stmt = $conn->prepare("
                SELECT s.subject_id, s.title, e.enrollment_date, 
                       e.exam_date, e.grade, p.name_ AS prof_name, p.last_name AS prof_lastname
                FROM Enrollment e
                JOIN Subject s ON e.subject_id = s.subject_id
                JOIN Professor p ON e.professor_id = p.id
                WHERE e.student_id = ? AND e.professor_id = ?
            ");
            $stmt->bind_param("ss", $student_id, $professor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            break;
        case 'B4':
            // Β.4. Πληροφορίες διδασκόντων και τμημάτων
            $title = "Διδάσκοντες και Τμήματα";
            $stmt = $conn->prepare("
                SELECT p.id AS professor_id, p.name_, p.last_name, p.specialty,
                       c.code AS class_code, c.name_ AS class_name, c.region
                FROM Professor p
                JOIN Class c ON p.class_code = c.code
                ORDER BY p.last_name, p.name_
            ");
            $stmt->execute();
            $result = $stmt->get_result();
            break;
        case 'B5':
            // Β.5. Μέσος όρος όλων των μαθημάτων φοιτητή Χ
            $title = "Μέσος όρος όλων των μαθημάτων για φοιτητή $student_id";
            $stmt = $conn->prepare("
                SELECT AVG(e.grade) AS average_grade
                FROM Enrollment e
                WHERE e.student_id = ? AND e.grade IS NOT NULL
            ");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            break;   
        case 'B6':
            // Β.6. Μέσος όρος περασμένων μαθημάτων φοιτητή Χ (βαθμός >= 5)
            $title = "Μέσος όρος περασμένων μαθημάτων για φοιτητή $student_id";
            $stmt = $conn->prepare("
                SELECT AVG(e.grade) AS average_passed
                FROM Enrollment e
                WHERE e.student_id = ? AND e.grade IS NOT NULL AND e.grade >= 5
            ");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            break; 
        case 'B7':
            // Β.7. Πτυχιακές εργασίες του διδάσκοντα Υ
            $title = "Πτυχιακές εργασίες του καθηγητή $professor_id";
            $stmt = $conn->prepare("
                SELECT g.id, g.title, g.latest_month_to_finish, g.must_do,
                       s.registration_number, s.name_, s.last_name
                FROM Graduation_Assignment g
                JOIN Student s ON g.student_registration_number = s.registration_number
                WHERE g.professor_id = ?
            ");
            $stmt->bind_param("s", $professor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            break; 
        default:
            $error = "Μη έγκυρος τύπος αναζήτησης";
            break;
    }
    if (isset($result) && $result) {
        $results = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Σύστημα Αναζήτησης</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .search-form { background: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input { padding: 8px; width: 100%; max-width: 300px; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .result-title { margin-top: 30px; color: #333; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="form-group">
    <button type="button" onclick="window.location.href='/foititologio/index.php'" style="margin-top: 20px; background-color: #6c757d;">
        Επιστροφή στην Αρχική
    </button>
    </div>
    <div class="container">
        <h1>Σύστημα Αναζήτησης</h1>
        
        <div class="search-form">
            <form method="post">
                <div class="form-group">
                    <label for="search_type">Τύπος Αναζήτησης:</label>
                    <select name="search_type" id="search_type" required>
                        <option value="">-- Επιλέξτε --</option>
                        <option value="B1" <?= $search_type == 'B1' ? 'selected' : '' ?>>Β.1. Μαθήματα εγγραφής φοιτητή</option>
                        <option value="B2" <?= $search_type == 'B2' ? 'selected' : '' ?>>Β.2. Μαθήματα & βαθμοί φοιτητή</option>
                        <option value="B3" <?= $search_type == 'B3' ? 'selected' : '' ?>>Β.3. Μαθήματα φοιτητή με καθηγητή</option>
                        <option value="B4" <?= $search_type == 'B4' ? 'selected' : '' ?>>Β.4. Διδάσκοντες & Τμήματα</option>
                        <option value="B5" <?= $search_type == 'B5' ? 'selected' : '' ?>>Β.5. Μέσος όρος όλων μαθημάτων</option>
                        <option value="B6" <?= $search_type == 'B6' ? 'selected' : '' ?>>Β.6. Μέσος όρος περασμένων μαθημάτων</option>
                        <option value="B7" <?= $search_type == 'B7' ? 'selected' : '' ?>>Β.7. Πτυχιακές εργασίες καθηγητή</option>
                    </select>
                </div>
                
                <div class="form-group" id="student-id-group" style="<?= in_array($search_type, ['B1','B2','B3','B5','B6']) ? '' : 'display:none;' ?>">
                    <label for="student_id">Αριθμός Μητρώου Φοιτητή:</label>
                    <input type="text" name="student_id" id="student_id" value="<?= htmlspecialchars($student_id) ?>">
                </div>
                
                <div class="form-group" id="professor-id-group" style="<?= in_array($search_type, ['B3','B7']) ? '' : 'display:none;' ?>">
                    <label for="professor_id">Κωδικός Καθηγητή:</label>
                    <input type="text" name="professor_id" id="professor_id" value="<?= htmlspecialchars($professor_id) ?>">
                </div>
                
                <button type="submit">Αναζήτηση</button>
            </form>
        </div>
        
        <?php if (!empty($title)): ?>
            <h2 class="result-title"><?= htmlspecialchars($title) ?></h2>
            
            <?php if (!empty($results)): ?>
                <table>
                    <thead>
                        <tr>
                            <?php foreach (array_keys($results[0]) as $column): ?>
                                <th><?= htmlspecialchars($column) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?= htmlspecialchars($value) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Δεν βρέθηκαν αποτελέσματα</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        // Εμφάνιση/απόκρυψη πεδίων βάσει του τύπου αναζήτησης
        document.getElementById('search_type').addEventListener('change', function() {
            const type = this.value;
            document.getElementById('student-id-group').style.display = 
                ['B1','B2','B3','B5','B6'].includes(type) ? 'block' : 'none';
            document.getElementById('professor-id-group').style.display = 
                ['B3','B7'].includes(type) ? 'block' : 'none';
        });
    </script>

</body>
</html>