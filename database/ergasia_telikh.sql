-- Class (Τμήματα)
-- Αποθηκεύει πληροφορίες για τα τμήματα του πανεπιστημίου
CREATE TABLE Class (
    code VARCHAR(20) PRIMARY KEY,               -- Κωδικός τμήματος (πχ "CS101")
    name_ VARCHAR(100) NOT NULL,                -- Όνομα τμήματος (πχ "Πληροφορική")
    email VARCHAR(100) NOT NULL UNIQUE,         -- Email τμήματος (πχ "cs@uni.gr")
    phone VARCHAR(20) NOT NULL,                 -- Τηλέφωνο τμήματος (πχ "2101234567")
    region VARCHAR(50) NOT NULL,                -- Περιοχή έδρας (πχ "Αθήνα")
    website VARCHAR(200) NOT NULL,              -- Ιστοσελίδα (πχ "https://cs.uni.gr")
    headquarters VARCHAR(200) NOT NULL,         -- Φυσική έδρα (πχ "Κεντρικό κτίριο, 3ος όροφος")
    min_semesters INT NOT NULL,                 -- Ελάχιστα εξάμηνα για απόκτηση πτυχίου (πχ 8)
    social_media VARCHAR(200)                   -- Λογαριασμοί κοινωνικών δικτύων (πχ "@cs_uni")
);

-- Professor (Καθηγητές)
-- Καταγράφει τους καθηγητές και τα στοιχεία επικοινωνίας τους
CREATE TABLE Professor (
    id VARCHAR(20) PRIMARY KEY,                 -- Μοναδικό ID καθηγητή (πχ "P100")
    name_ VARCHAR(50) NOT NULL,                 -- Όνομα (πχ "Μαρία")
    last_name VARCHAR(50) NOT NULL,             -- Επώνυμο (πχ "Παπαδοπούλου")
    specialty VARCHAR(100) NOT NULL,            -- Ειδικότητα (πχ "Βάσεις Δεδομένων")
    position VARCHAR(50) NOT NULL,              -- Ακαδημαϊκή θέση (πχ "Αναπληρώτρια Καθηγήτρια")
    phone VARCHAR(20) NOT NULL,                 -- Τηλέφωνο (πχ "2107654321")
    address_ VARCHAR(200) NOT NULL,             -- Διεύθυνση (πχ "Πανεπιστημίου 30, Αθήνα")
    email VARCHAR(100) NOT NULL UNIQUE,         -- Email (πχ "papadopoulou@cs.uni.gr")
    class_code VARCHAR(20) NOT NULL,            -- Τμήμα που ανήκει (συσχέτιση με πίνακα Class)
    FOREIGN KEY (class_code) REFERENCES Class(code)
);

-- Student (Φοιτητές)
-- Βασικές πληροφορίες για όλους τους φοιτητές
CREATE TABLE Student (
    registration_number VARCHAR(20) PRIMARY KEY, -- Αριθμός μητρώου (πχ "UP100")
    name_ VARCHAR(50) NOT NULL,                 -- Όνομα (πχ "Γιάννης")
    last_name VARCHAR(50) NOT NULL,             -- Επώνυμο (πχ "Κωστόπουλος")
    father_name VARCHAR(50) NOT NULL,           -- Όνομα Πατρός (πχ "Πέτρος")
    address_ VARCHAR(200) NOT NULL,             -- Διεύθυνση (πχ "Σόλωνος 5, Αθήνα")
    phone VARCHAR(20) NOT NULL,                 -- Τηλέφωνο (πχ "6941234567")
    email VARCHAR(100) NOT NULL UNIQUE,         -- Email (πχ "kostopoulos@uni.gr")
    registration_date DATE NOT NULL,            -- Ημερομηνία εγγραφής (πχ "2023-09-01")
    class_code VARCHAR(20) NOT NULL,            -- Τμήμα (συσχέτιση με πίνακα Class)
    professor_id VARCHAR(20),                   -- Σύμβουλος καθηγητής (συσχέτιση με Professor)
    FOREIGN KEY (class_code) REFERENCES Class(code),
    FOREIGN KEY (professor_id) REFERENCES Professor(id)
);

-- Schedule_Category (Κατηγορίες Προγράμματος)
-- Κατηγοριοποίηση μαθημάτων (πχ "Υποχρεωτικό", "Επιλογής")
CREATE TABLE Schedule_Category (
    schedule_category_pk VARCHAR(20) PRIMARY KEY, -- Κωδικός κατηγορίας (πχ "CORE")
    type_of_subject VARCHAR(50) NOT NULL         -- Τύπος μαθήματος (πχ "Υποχρεωτικό")
);

-- Subject (Μαθήματα)
-- Πληροφορίες για κάθε μάθημα που προσφέρεται
CREATE TABLE Subject (
    subject_id VARCHAR(20) PRIMARY KEY,         -- Κωδικός μαθήματος (πχ "CS101")
    title VARCHAR(100) NOT NULL,                -- Τίτλος (πχ "Βάσεις Δεδομένων")
    semester INT NOT NULL,                      -- Εξάμηνο διδασκαλίας (πχ 5)
    month_ VARCHAR(20) NOT NULL,                -- Μήνας διδασκαλίας (πχ "Φεβρουάριος")
    ects INT NOT NULL,                          -- Πιστωτικές μονάδες ECTS (πχ 6)
    importance VARCHAR(50) NOT NULL,            -- Βαρύτητα για τον μέσο όρο (πχ "Υψηλή")
    category VARCHAR(50) NOT NULL,              -- Κατηγορία (πχ "Θεωρία")
    program VARCHAR(100) NOT NULL,              -- Πρόγραμμα σπουδών (πχ "ΠΛΗΡΟΦΟΡΙΚΗ")
    min_duration_months INT,                    -- Ελάχιστος χρόνος ολοκλήρωσης (για πτυχιακές)
    max_duration_months INT,                    -- Μέγιστος χρόνος ολοκλήρωσης (για πτυχιακές)
    class_code VARCHAR(20) NOT NULL,            -- Τμήμα που προσφέρει το μάθημα
    FOREIGN KEY (class_code) REFERENCES Class(code)
);

-- Schedule (Πρόγραμμα Μαθημάτων)
-- Χρονοπρογραμματισμός μαθημάτων (ώρες/αίθουσες)
CREATE TABLE Schedule (
    schedule_code VARCHAR(20) PRIMARY KEY,      -- Κωδικός προγράμματος (πχ "SCH101")
    subject_id VARCHAR(20) NOT NULL,            -- Μάθημα (συσχέτιση με Subject)
    schedule_category_pk VARCHAR(20) NOT NULL,  -- Κατηγορία προγράμματος
    FOREIGN KEY (subject_id) REFERENCES Subject(subject_id),
    FOREIGN KEY (schedule_category_pk) REFERENCES Schedule_Category(schedule_category_pk)
);

-- Postgraduate (Μεταπτυχιακοί Φοιτητές)
-- Στοιχεία για μεταπτυχιακούς φοιτητές
CREATE TABLE Postgraduate (
    first_degree_name VARCHAR(100) NOT NULL,    -- Τίτλος προηγούμενου πτυχίου (πχ "Πληροφορική")
    registration_number VARCHAR(20) PRIMARY KEY,-- Αριθμός μητρώου (συσχέτιση με Student)
    FOREIGN KEY (registration_number) REFERENCES Student(registration_number)
);

-- Undergraduate (Προπτυχιακοί Φοιτητές)
-- Στοιχεία για προπτυχιακούς φοιτητές
CREATE TABLE Undergraduate (
    registration_number VARCHAR(20) PRIMARY KEY,-- Αριθμός μητρώου (συσχέτιση με Student)
    type_of_school VARCHAR(50) NOT NULL,        -- Τύπος σχολείου (πχ "Γενικό Λύκειο")
    type_of_exams VARCHAR(50) NOT NULL,         -- Τύπος εξετάσεων (πχ "Πανελλήνιες")
    name_of_school VARCHAR(100) NOT NULL,       -- Όνομα σχολείου (πχ "1ο Λύκειο Αθηνών")
    number_of_entrance INT NOT NULL,            -- Αριθμός εισαγωγής (πχ 125)
    admission_rank INT NOT NULL,                -- Βαθμολογία κατάταξης (πχ 1500)
    FOREIGN KEY (registration_number) REFERENCES Student(registration_number)
);

-- Teaching_Competence (Διδακτική Επάρκεια)
-- Φοιτητές σε προγράμματα διδακτικής επάρκειας
CREATE TABLE Teaching_Competence (
    university VARCHAR(100) NOT NULL,           -- Πανεπιστήμιο προέλευσης (πχ "Ιόνιο Πανεπιστήμιο")
    registration_number VARCHAR(20) NOT NULL,   -- Αριθμός μητρώου (συσχέτιση με Student)
    PRIMARY KEY (university, registration_number),
    FOREIGN KEY (registration_number) REFERENCES Student(registration_number)
);

-- Graduation_Assignment (Πτυχιακές Εργασίες)
-- Διαχείριση πτυχιακών εργασιών
CREATE TABLE Graduation_Assignment (
    id VARCHAR(20) PRIMARY KEY,                 -- Κωδικός πτυχιακής (πχ "GA100")
    title VARCHAR(200) NOT NULL,                -- Τίτλος εργασίας (πχ "Μια νέα προσέγγιση σε ΒΔ")
    must_do ENUM('YES', 'NO') NOT NULL,         -- Αν είναι υποχρεωτική (YES/NO)
    latest_month_to_finish VARCHAR(20) NOT NULL,-- Τελική προθεσμία (πχ "Ιούνιος 2024")
    professor_id VARCHAR(20) NOT NULL,          -- Επιβλέπων καθηγητής
    student_registration_number VARCHAR(20) NOT NULL, -- Φοιτητής
    FOREIGN KEY (professor_id) REFERENCES Professor(id),
    FOREIGN KEY (student_registration_number) REFERENCES Student(registration_number)
);

-- Has_a_pretake (Προαπαιτούμενα Μαθημάτων)
-- Αναδρομική σχέση για προαπαιτούμενα μαθημάτων
CREATE TABLE Has_a_pretake (
    subject_subject_id VARCHAR(20) NOT NULL,    -- Μάθημα που έχει προαπαιτούμενο
    pretake_subject_id VARCHAR(20) NOT NULL,    -- Το προαπαιτούμενο μάθημα
    PRIMARY KEY (subject_subject_id, pretake_subject_id),
    FOREIGN KEY (subject_subject_id) REFERENCES Subject(subject_id),
    FOREIGN KEY (pretake_subject_id) REFERENCES Subject(subject_id)
);

-- Enrollment (Εγγραφές σε Μαθήματα)
-- Καταγραφή εγγραφών και βαθμολογιών φοιτητών
CREATE TABLE Enrollment (
    enrollment_id VARCHAR(20) PRIMARY KEY,      -- Κωδικός εγγραφής (πχ "EN100")
    student_id VARCHAR(20) NOT NULL,            -- Φοιτητής (συσχέτιση με Student)
    subject_id VARCHAR(20) NOT NULL,            -- Μάθημα (συσχέτιση με Subject)
    professor_id VARCHAR(20) NOT NULL,          -- Διδάσκων (συσχέτιση με Professor)
    enrollment_date DATE NOT NULL,              -- Ημερομηνία εγγραφής (πχ "2023-09-15")
    exam_date DATE,                             -- Ημερομηνία εξέτασης (πχ "2024-02-10")
    grade DECIMAL(3,1),                         -- Βαθμός (πχ 8.5)
    FOREIGN KEY (student_id) REFERENCES Student(registration_number),
    FOREIGN KEY (subject_id) REFERENCES Subject(subject_id),
    FOREIGN KEY (professor_id) REFERENCES Professor(id)
);