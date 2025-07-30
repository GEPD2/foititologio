<?php
// Πιθανές ενεργές σελίδες για highlight
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <nav class="navbar" style="background-color: #f8f9fa; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="nav-container" style="max-width: 1200px; margin: 0 auto; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <!-- Logo/Ονομασία Συστήματος -->
            <div class="nav-brand" style="font-weight: bold; font-size: 1.2rem;">
                <a href="index.php" style="text-decoration: none; color: #333;">🏛️ Φοιτητολόγιο</a>
            </div>
            <!-- Κύριο Μενού -->
            <div class="nav-links" style="display: flex; align-items: center;">
                <!-- Εισαγωγή Δεδομένων -->
                <div class="nav-group" style="margin-right: 1rem;">
                    <span style="color: #666; font-size: 0.9rem; display: block;">Εισαγωγή</span>
                    <div style="display: flex; flex-wrap: wrap;">
                        <a href="index.php" 
                           class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           🏠 Αρχική
                        </a>
                        <a href="add_student.html" 
                           class="nav-link <?= $current_page === 'add_student.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           👨‍🎓 Φοιτητής
                        </a>
                        <a href="add_professor.html" 
                           class="nav-link <?= $current_page === 'add_professor.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           👨‍🏫 Καθηγητής
                        </a>
                        <a href="add_subject.html" 
                           class="nav-link <?= $current_page === 'add_subject.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           📚 Μάθημα
                        </a>
                        <a href="add_class.html" 
                           class="nav-link <?= $current_page === 'add_class.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           🏫 Τμήμα
                        </a>
                        <a href="add_schedule.html" 
                           class="nav-link <?= $current_page === 'add_schedule.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           🕒 Πρόγραμμα
                        </a>
                        <a href="add_enrollment.html" 
                           class="nav-link <?= $current_page === 'add_enrollment.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           📝 Εγγραφή
                        </a>
                        <a href="add_graduation_assignment.html" 
                           class="nav-link <?= $current_page === 'add_graduation_assignment.html' ? 'active' : '' ?>" 
                           style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                           🎓 Πτυχιακή
                        </a>
                    </div>
                </div>
                <!-- Αναζητήσεις -->
                <!-- Αναζητήσεις -->
                <div class="nav-group">
                    <span style="color: #666; font-size: 0.9rem; display: block;">Αναζήτηση</span>
                    <div style="display: flex;">
                        <a href="searches/search.php" 
                            class="nav-link <?= $current_page === 'search.php' ? 'active' : '' ?>" 
                            style="text-decoration: none; color: #333; margin: 0 0.5rem; padding: 0.5rem; transition: all 0.3s ease;">
                            🔍 Αναζήτηση
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <style>
        /* Active State */
        .nav-link.active {
            color: #007bff !important;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* Hover Effect */
        .nav-link:hover {
            color: #007bff !important;
            transform: translateY(-2px);
        }
        /* Responsive Design */
        @media (max-width: 1200px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-group {
                margin-bottom: 1rem;
            }
        }
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                padding: 0.5rem !important;
            }
            .nav-links {
                width: 100%;
            }
            .nav-group > div {
                flex-wrap: wrap;
                justify-content: center;
            }
            .nav-link {
                margin: 0.3rem !important;
                font-size: 0.9rem;
                padding: 0.3rem !important;
            }
        }
        @media (max-width: 576px) {
            .nav-link {
                font-size: 0.8rem;
                margin: 0.2rem !important;
            }
            .nav-group span {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</body>
</html>