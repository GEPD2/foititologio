# 🎓 Student Management Information System

This repository contains a comprehensive **student management system** implemented with **PHP**, **MySQL**, and **JavaScript**. The system allows **insertion, storage, and tracking** of student, professor, course, thesis, and academic progress data.

## 📌 Key Features

- Add new student records (undergraduate, postgraduate, teaching competence).
- Link students with academic programs, professors, thesis, and courses.
- Track grades, enrollment dates, and exam results.
- Support for course prerequisites and course categories.
- Client-side form validation and interactivity using JavaScript.

## 🧰 Technologies Used

- 🐘 PHP (server-side logic)
- 🛢️ MySQL (relational database)
- 🌐 HTML/CSS (input forms and layout)
- ⚡ JavaScript (form validation and dynamic behavior)

---

## 🧱 Entities & Relationships

### 👨‍🎓 Student
- `registration_number` (Primary Key)
- `name_`, `last_name`, `father_name`
- `address_`, `phone`, `email`
- `registration_date`
- `class_code` (FK → Class)
- `professor_id` (FK → Professor)

#### 🔹 Subtypes:
- **Undergraduate**
  - `type_of_school`, `type_of_exams`, `name_of_school`
  - `number_of_entrance`, `admission_rank`
- **Postgraduate**
  - `first_degree_name`
- **Teaching_Competence**
  - `university`

### 🏫 Class (Department)
- `code` (Primary Key)
- `name_`, `email`, `phone`
- `region`, `headquarters`
- `min_semesters`, `max_semesters`
- `website`, `social_media`

### 👨‍🏫 Professor
- `id` (Primary Key)
- `name_`, `last_name`
- `specialty`, `position`
- `phone`, `email`, `address_`
- `class_code` (FK → Class)

### 📘 Subject (Course)
- `subject_id` (Primary Key)
- `title`, `semester`, `month_`
- `ects`, `importance`, `category`
- `program`, `class_code`
- `min_duration_months`, `max_duration_months` (used only for theses)

#### 🔁 Has_a_pretake (Course Prerequisite - recursive M:N)
- `subject_subject_id` (FK → Subject)
- `pretake_subject_id` (FK → Subject)

### 📚 Schedule_Category
- `schedule_category_pk` (Primary Key)
- `type_of_subject` (e.g., Mandatory, Elective)

### 🗓️ Schedule
- `schedule_code` (Primary Key)
- `subject_id` (FK → Subject)
- `schedule_category_pk` (FK → Schedule_Category)

### 📄 Graduation_Assignment (Thesis)
- `id` (Primary Key)
- `title`, `must_do`
- `latest_month_to_finish`
- `professor_id` (FK)
- `student_registration_number` (FK)

### 📝 Enrollment
- `enrollment_id` (Primary Key)
- `student_id` (FK → Student)
- `subject_id` (FK → Subject)
- `professor_id` (FK → Professor)
- `enrollment_date`, `exam_dat`, `grade`

---

## ⚙️ How to Use

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/foititologio.git
   cd foititologio
2. **install xampp**
   On **Windows** download the installer and install to default path C:\xampp\
   On **Debian/Ubuntu** download the installer .run file from Apache Friends. Then: sudo chmod +x xampp-linux-x64-*.run
   sudo ./xampp-linux-x64-*.run Default installation path: /opt/lampp/
   On **Fedora / RPM-based** ollow the same .run installation method as above. SELinux adjustments may be required.
   On **Arch-based (e.g., Manjaro)** Install via AUR:yay -S xampp
   or manually using the .run installer. Default path: /opt/lampp/
3. **Copy the folder**
   On **Linux (Ubuntu/Debian/Fedora/Arch):** sudo cp -r /path/to/foititologio /opt/lampp/htdocs/
   On **Windows:** Copy the entire foititologio folder to: C:\xampp\htdocs\
5. **Start Apache and MySQL Services**
   **Linux:** sudo /opt/lampp/lampp start
   **Windows:** Use the XAMPP Control Panel: Click Start next to Apache and then click Start next to MySQL
6. **Import the Database**
   Go to http://localhost/phpmyadmin
   Click New to create a database named foititologio, Click Import and upload the file ergasia_telikh.sql from this repository,also add some data from the data_for_all_tables.sql
7. **Open the browser**: and type http://localhost/foititologio/
