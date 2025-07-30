-- Class (Τμήματα)
INSERT INTO Class (code, name_, email, phone, region, website, headquarters, min_semesters, social_media)
VALUES 
('CS101', 'Πληροφορική', 'cs@uni.gr', '2101234567', 'Αθήνα', 'https://cs.uni.gr', 'Κεντρικό κτίριο, 3ος όροφος', 8, '@cs_uni'),
('PH201', 'Φυσική', 'physics@uni.gr', '2102345678', 'Θεσσαλονίκη', 'https://physics.uni.gr', 'Κτίριο Β, 1ος όροφος', 8, '@physics_uni'),
('MA301', 'Μαθηματικά', 'math@uni.gr', '2103456789', 'Πάτρα', 'https://math.uni.gr', 'Κτίριο Γ, 2ος όροφος', 8, '@math_uni');

-- Professor (Καθηγητές)
INSERT INTO Professor (id, name_, last_name, specialty, position, phone, address_, email, class_code)
VALUES 
('P100', 'Μαρία', 'Παπαδοπούλου', 'Βάσεις Δεδομένων', 'Καθηγήτρια', '2101111111', 'Πανεπιστημίου 30, Αθήνα', 'papadopoulou@cs.uni.gr', 'CS101'),
('P200', 'Νίκος', 'Ανδρέου', 'Κβαντική Φυσική', 'Αναπληρωτής Καθηγητής', '2102222222', 'Αριστοτέλους 10, Θεσσαλονίκη', 'andreou@physics.uni.gr', 'PH201'),
('P300', 'Ελένη', 'Γεωργίου', 'Αλγεβρικές Δομές', 'Επίκουρη Καθηγήτρια', '2103333333', 'Καραϊσκάκη 5, Πάτρα', 'georgiou@math.uni.gr', 'MA301');

-- Student (Φοιτητές)
INSERT INTO Student (registration_number, name_, last_name,father_name, address_, phone, email, registration_date, class_code, professor_id)
VALUES 
('UP100', 'Γιάννης', 'Κωστόπουλος', 'Ελευθέριος' ,'Σόλωνος 5, Αθήνα', ' ', 'kostopoulos@uni.gr', '2023-09-01', 'CS101', 'P100'),
('UP200', 'Αννα', 'Ιωαννίδου', 'Σταμάτης' , 'Πλατεία Συντάγματος 10, Θεσσαλονίκη', '6942345678', 'ioannidou@uni.gr', '2023-09-01', 'PH201', 'P200'),
('UP300', 'Δημήτρης', 'Παυλίδης', 'Σπύριδον' , 'Μαυρομιχάλη 15, Πάτρα', '6943456789', 'pavlidis@uni.gr', '2023-09-01', 'MA301', 'P300');

-- Schedule_Category (Κατηγορίες Προγράμματος)
INSERT INTO Schedule_Category (schedule_category_pk, type_of_subject)
VALUES 
('CORE', 'Υποχρεωτικό'),
('ELECTIVE', 'Επιλογής'),
('SEMINAR', 'Σεμιναριακό');

-- Subject (Μαθήματα)
INSERT INTO Subject (subject_id, title, semester, month_, ects, importance, category, program, min_duration_months, max_duration_months, class_code)
VALUES 
('CS101DB', 'Βάσεις Δεδομένων', 5, 'Φεβρουάριος', 6, 'Υψηλή', 'Θεωρία', 'ΠΛΗΡΟΦΟΡΙΚΗ', NULL, NULL, 'CS101'),
('PH201QM', 'Κβαντική Μηχανική', 3, 'Οκτώβριος', 5, 'Μέτρια', 'Εργαστήριο', 'ΦΥΣΙΚΗ', NULL, NULL, 'PH201'),
('MA301AL', 'Γραμμική Άλγεβρα', 1, 'Οκτώβριος', 6, 'Υψηλή', 'Θεωρία', 'ΜΑΘΗΜΑΤΙΚΑ', NULL, NULL, 'MA301'),
('CS102GA', 'Πτυχιακή Εργασία', 8, 'Ιούνιος', 12, 'Υψηλή', 'Έρευνα', 'ΠΛΗΡΟΦΟΡΙΚΗ', 6, 12, 'CS101');

-- Schedule (Πρόγραμμα Μαθημάτων)
INSERT INTO Schedule (schedule_code, subject_id, schedule_category_pk)
VALUES 
('SCH101', 'CS101DB', 'CORE'),
('SCH201', 'PH201QM', 'CORE'),
('SCH301', 'MA301AL', 'CORE'),
('SCH102', 'CS102GA', 'SEMINAR');

-- Postgraduate (Μεταπτυχιακοί Φοιτητές)
INSERT INTO Postgraduate (first_degree_name, registration_number)
VALUES 
('Πληροφορική', 'UP100'),
('Φυσική', 'UP200');

-- Undergraduate (Προπτυχιακοί Φοιτητές)
INSERT INTO Undergraduate (registration_number, type_of_school, type_of_exams, name_of_school, number_of_entrance, admission_rank)
VALUES 
('UP300', 'Γενικό Λύκειο', 'Πανελλήνιες', '1ο Λύκειο Αθηνών', 125, 1500);

-- Teaching_Competence (Διδακτική Επάρκεια)
INSERT INTO Teaching_Competence (university, registration_number)
VALUES 
('Ιόνιο Πανεπιστήμιο', 'UP100'),
('Αριστοτέλειο Πανεπιστήμιο', 'UP200');

-- Graduation_Assignment (Πτυχιακές Εργασίες)
INSERT INTO Graduation_Assignment (id, title, must_do, latest_month_to_finish, professor_id, student_registration_number)
VALUES 
('GA100', 'Μια νέα προσέγγιση σε Βάσεις Δεδομένων', 'YES', 'Ιούνιος 2024', 'P100', 'UP100'),
('GA200', 'Κβαντικές Καταστάσεις σε Υπεργωματώδη', 'NO', 'Σεπτέμβριος 2024', 'P200', 'UP200');

-- Has_a_pretake (Προαπαιτούμενα Μαθημάτων)
INSERT INTO Has_a_pretake (subject_subject_id, pretake_subject_id)
VALUES 
('CS101DB', 'MA301AL'),  -- Γραμμική Άλγεβρα προαπαιτούμενο για Βάσεις Δεδομένων
('PH201QM', 'MA301AL');  -- Γραμμική Άλγεβρα προαπαιτούμενο για Κβαντική Μηχανική

-- Enrollment (Εγγραφές σε Μαθήματα)
INSERT INTO Enrollment (enrollment_id, student_id, subject_id, professor_id, enrollment_date, exam_date, grade)
VALUES 
('EN100', 'UP100', 'CS101DB', 'P100', '2023-09-15', '2024-02-10', 8.5),
('EN200', 'UP200', 'PH201QM', 'P200', '2023-09-15', '2024-02-15', 7.0),
('EN300', 'UP300', 'MA301AL', 'P300', '2023-09-15', '2024-02-20', 9.0),
('EN101', 'UP100', 'CS102GA', 'P100', '2024-01-10', NULL, NULL);