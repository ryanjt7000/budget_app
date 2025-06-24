<?php
$db = new SQLite3('data.db');

// Create users table
$db->exec("
CREATE TABLE IF NOT EXISTS users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
)");

// Create entries table
$db->exec("
CREATE TABLE IF NOT EXISTS entries (
    user_id INTEGER NOT NULL,
    entry_id INTEGER NOT NULL,
    amount REAL NOT NULL,
    category TEXT NOT NULL,
    note TEXT,
    PRIMARY KEY (user_id, entry_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)");

echo "Database initialized successfully.";
