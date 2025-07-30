<?php
declare(strict_types=1); // Strict typing για καλύτερο type checking
require_once __DIR__ . '/config.php'; // Χρήση __DIR__ για απόλυτα paths
// Database Connection Handler: Δημιουργεία ασφαλής σύνδεσης με τη βάση δεδομένων με εξαιρέσεις και logging
class Database {
    private static $instance = null;
    private $connection;
    private function __construct() {
        // Ενεργοποίηση αναφορών σφαλμάτων
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            // Δημιουργία σύνδεσης με επιπλέον επιλογές
            $this->connection = new mysqli(
                DB_SERVER, 
                DB_USERNAME, 
                DB_PASSWORD, 
                DB_NAME,
                DB_PORT
            );
            // Ορισμός χαρακτήρων UTF-8
            $this->connection->set_charset(defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');
            // Επιλογές SSL (αν έχουν οριστεί)
            if (defined('DB_SSL') && DB_SSL === true) {
                $this->connection->ssl_set(
                    DB_SSL_KEY ?? null,
                    DB_SSL_CERT ?? null,
                    DB_SSL_CA ?? null,
                    null,
                    null
                );
            }
        } catch (mysqli_sql_exception $e) {
            // Logging σφάλματος
            error_log("Database connection failed: " . $e->getMessage());
            // User-friendly μήνυμα (χωρίς ευαίσθητες πληροφορίες)
            die("Αποτυχία σύνδεσης με τη βάση δεδομένων. Παρακαλώ δοκιμάστε ξανά αργότερα.");
        }
    }
    // Εξασφάλιση μίας μόνο σύδεσης με τη βάση
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    // πρόσβαση στη σύνδεση για την εκτέλεση των queries
    public function getConnection(): mysqli {
        return $this->connection;
    }
    // κλείσιμο της σύνδεσης με τη βάση όταν δεν χρειάζεται πλέον
    public function __destruct() {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
}
// Singleton pattern για σύνδεση, δηλαδή δημιουργία ενός και μόνου αντικειμένου της κλάσης θα υπάρχει στην εφαρμογή
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    // Προαιρετικός έλεγχος σύνδεσης (μόνο για development)
    if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
        $conn->query("SELECT 1"); // Απλό test query
    }
} catch (Exception $e) {
    error_log("Database initialization error: " . $e->getMessage());
    die("Σφάλμα συστήματος. Παρακαλώ ειδοποιήστε τον διαχειριστή.");
}
?>