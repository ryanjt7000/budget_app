<?php
$db = new SQLite3('data.db');

$db->exec("ALTER TABLE entries ADD COLUMN type TEXT DEFAULT 'expense'");

echo 'DB updated';