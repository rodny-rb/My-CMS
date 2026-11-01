<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            padding: 40px 0;
        }
        .admin-panel {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 2rem;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        .metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .metric {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the administration area for <?= htmlspecialchars(APP_NAME) ?>.</p>

        <div class="metrics">
            <div class="metric">
                <strong>Visitors</strong>
                <p>1,284</p>
            </div>
            <div class="metric">
                <strong>Messages</strong>
                <p>42</p>
            </div>
            <div class="metric">
                <strong>Status</strong>
                <p>Online</p>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <a class="btn primary" href="../index.php">Back to homepage</a>
        </div>
    </div>
</body>
</html>
