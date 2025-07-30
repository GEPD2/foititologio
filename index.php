<?php require_once __DIR__ . '/includes/db.php'; ?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Φοιτητολόγιο</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #f8f9fa;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .welcome-message {
            text-align: center;
            margin-top: 2rem;
            padding: 2rem;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    
    <main class="container">
        <div class="welcome-message">
            <h1>Καλώς ήρθατε στο Φοιτητολόγιο</h1>
            <p>Επιλέξτε από το μενού τι θέλετε να κάνετε.</p>
        </div>
    </main>
</body>
</html>