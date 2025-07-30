<?php
// status.php
$type = $_GET['type'] ?? '';
$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Κατάσταση Ενέργειας</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; }
    .message { padding: 20px; border-radius: 5px; }
    .success { background: #e0ffe0; color: #007700; }
    .error { background: #ffe0e0; color: #990000; }
    a { display: inline-block; margin-top: 20px; }
  </style>
</head>
<body>

<?php if ($status === 'success'): ?>
  <div class="message success">
    ✅ Η εισαγωγή <?= htmlspecialchars($type) ?> ολοκληρώθηκε με επιτυχία.
  </div>
<?php elseif ($status === 'error'): ?>
  <div class="message error">
    ❌ Προέκυψε σφάλμα κατά την εισαγωγή <?= htmlspecialchars($type) ?>.
  </div>
<?php else: ?>
  <div class="message error">
    Άγνωστη κατάσταση ή μη εξουσιοδοτημένη πρόσβαση.
  </div>
<?php endif; ?>

<a href="index.php">🔙 Επιστροφή στην αρχική</a>

</body>
</html>