<?php
// Strict types για καλύτερο type checking
declare(strict_types=1);

/**
 * Database Configuration
 * 
 * Οι ρυθμίσεις σύνδεσης για τη βάση δεδομένων είναι οι προεπιλεγμένες.
 * - Username: root
 * - Password: (κενό)
 * - Server: localhost
 */

// Βασικές ρυθμίσεις
define('DB_SERVER', 'localhost');  // ή '127.0.0.1' για καλύτερη απόδοση
define('DB_USERNAME', 'root');     // Προεπιλογή XAMPP

// προσθήκη κωδικού για μεγαλύτερη ασφάλεια
define('DB_PASSWORD', ''); // κωδικός (κενό) από προεπιλογή

// Όνομα βάσης δεδομένων
define('DB_NAME', 'mydatabase'); // αν θέλετε αλλάξτε το και προσαρμόστετο σε ότι όνομα έχετε τι δικιά σας

// Προχωρημένες ρυθμίσεις
define('DB_CHARSET', 'utf8mb4');  // Καλύτερη υποστήριξη Unicode
define('DB_COLLATE', 'utf8mb4_general_ci'); // δεν θέλουμε να γίνει διάκριση μεταξύ πεζών-κεφαλαίων γραμμάτων για το σύνολο χαρακτήρων της UTF-8
define('DB_PORT', 3306);        // Προεπιλογή MySQL port

// Σημαία για σύνδεση με SSL (μέχρι στιγμής δεν έχουμε SSL certificate)
define('DB_SSL', false); // είναι false γιατί δεν έχουμε certificate, κατά πάσα πιθανότητα ούτε εσείς

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '0');     // Απενεργοποίηση για production
ini_set('log_errors', '1');         // Ενεργοποίηση error logging
ini_set('error_log', __DIR__ . '/php_errors.log');

// Εναλλακτική προσέγγιση με πίνακα
$dbConfig = [
    'host' => DB_SERVER,
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
    'dbname' => DB_NAME,
    'charset' => DB_CHARSET
];

// Επιλογή για μελλοντική χρήση environment variables
// require_once __DIR__ . '/vendor/autoload.php';
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();
?>