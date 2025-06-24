<?php
function getDBConnection($dbFile = 'data.db') {
    try {
        $db = new SQLite3($dbFile);
        $db->exec('PRAGMA foreign_keys = ON;'); // Ensure foreign key constraints are enforced
        return $db;
    } catch (Exception $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
