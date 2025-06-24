<?php
$db = new SQLite3('data.db');

// Fetch all users
$users = $db->query("SELECT * FROM users");

// Fetch all entries
$entries = $db->query("SELECT * FROM entries");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Viewer</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; margin-bottom: 40px; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>

<h1>SQLite Database Viewer</h1>

<h2>Users Table</h2>
<table>
    <tr>
        <th>user_id</th>
        <th>username</th>
        <th>email</th>
        <th>password (hashed)</th>
    </tr>
    <?php while ($row = $users->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($row['user_id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['password']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>Entries Table</h2>
<table>
    <tr>
        <th>user_id</th>
        <th>entry_id</th>
        <th>amount</th>
        <th>category</th>
        <th>note</th>
    </tr>
    <?php while ($row = $entries->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($row['user_id']) ?></td>
            <td><?= htmlspecialchars($row['entry_id']) ?></td>
            <td><?= htmlspecialchars($row['amount']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['note']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
