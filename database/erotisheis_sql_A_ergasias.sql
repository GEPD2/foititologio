-- Β1 μάθημα που έχει εγγραφεί ο φοιτητής χ
SELECT 
    s.subject_id AS "Κωδικός Μαθήματος",
    s.title AS "Τίτλος Μαθήματος",
    s.semester AS "Εξάμηνο"
FROM 
    Enrollment e
JOIN 
    Subject s ON e.subject_id = s.subject_id
WHERE 
    e.student_id = 'Φοιτητής_X';  -- Αντικατάσταση με τον πραγματικό αριθμό μητρώου (πχ 'UP100')

-- Β2 Μαθήματα, Διδάσκοντες και βαθμολογία του φοιτητή χ
SELECT 
    s.subject_id AS "Κωδικός",
    s.title AS "Μάθημα",
    CONCAT(p.name_, ' ', p.last_name) AS "Διδάσκων",
    e.grade AS "Βαθμός"
FROM 
    Enrollment e
JOIN 
    Subject s ON e.subject_id = s.subject_id
JOIN 
    Professor p ON e.professor_id = p.id
WHERE 
    e.student_id = 'Φοιτητής_X';  -- Αντικατάσταση με τον πραγματικό αριθμό μητρώου

-- Β3 Πλήρη δεδομένα των μαθημάτων του φοιτητή χ με διδάσκοντα Υ
SELECT 
    s.subject_id AS "Κωδικός",
    s.title AS "Μάθημα",
    e.enrollment_date AS "Ημερομηνία Εγγραφής",
    e.exam_date AS "Ημερομηνία Εξέτασης",
    e.grade AS "Βαθμός",
FROM 
    Enrollment e
JOIN 
    Subject s ON e.subject_id = s.subject_id
WHERE 
    e.student_id = 'Φοιτητής_X'  -- Αντικατάσταση με τον πραγματικό αριθμό μητρώου
    AND e.professor_id = 'Καθηγητής_Y';  -- Αντικατάσταση με το ID του καθηγητή (πχ 'P100')

-- Β4 Πληροφοίες των διδασκόντων και τμημάτων
SELECT 
    CONCAT(p.name_, ' ', p.last_name) AS "Ονοματεπώνυμο Καθηγητή",
    p.specialty AS "Ειδικότητα",
    c.name_ AS "Τμήμα",
    c.region AS "Περιοχή"
FROM 
    Professor p
JOIN 
    Class c ON p.class_code = c.code
ORDER BY 
    c.name_, p.last_name;

-- Β5 Μέσος όρος των βαθμών όλων των μαθημάτων του φοιτητή χ
SELECT 
    AVG(e.grade) AS "Μέσος Όρος Βαθμών"
FROM 
    Enrollment e
WHERE 
    e.student_id = 'Φοιτητής_X'  -- Αντικατάσταση με τον πραγματικό αριθμό μητρώου

-- Β6 Μέσος όρος των βαθμών των περασμένων μαθημάτων (βαθμός >= 5) του φοιτητή χ
SELECT 
    AVG(e.grade) AS "Μέσος Όρος Περασμένων Μαθημάτων"
FROM 
    Enrollment e
WHERE 
    e.student_id = 'Φοιτητής_X'  -- Αντικατάσταση με τον πραγματικό αριθμό μητρώου
    AND e.grade >= 5;

-- Β7 Πτυχιακές εργασίες του διδάσκοντα Υ
SELECT 
    ga.id AS "Κωδικός Εργασίας",
    ga.title AS "Τίτλος",
    CONCAT(s.name_, ' ', s.last_name) AS "Φοιτητής",
    ga.must_do AS "Υποχρεωτική",
    ga.latest_month_to_finish AS "Προθεσμία"
FROM 
    Graduation_Assignment ga
JOIN 
    Student s ON ga.student_registration_number = s.registration_number
WHERE 
    ga.professor_id = 'Καθηγητής_Y'  -- Αντικατάσταση με το ID του καθηγητή (πχ 'P100')
ORDER BY 
    ga.latest_month_to_finish;