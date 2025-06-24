<?php

require_once 'db.php';

function userExists($email, $username) {
    $db = getDBConnection();

    $stmt = $db->prepare("SELECT 1 FROM users WHERE username = :username OR email = :email LIMIT 1");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);

    $result = $stmt->execute();

    return $result->fetchArray() !== false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST input
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate that a 'type' field exists to distinguish request kind
    if (!isset($data['type'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Missing request type']);
        exit;
    }

    // Handle user signup
    if ($data['type'] === 'signup') {
        // Expect fields: username, email, password
        if (!isset($data['username'], $data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'Missing required signup fields']);
            exit;
        }

        $db = new SQLite3('data.db');

        // Check if email or username already exists
        $stmt = $db->prepare('SELECT COUNT(*) as count FROM users WHERE username = :username OR email = :email');
        $stmt->bindValue(':username', $data['username'], SQLITE3_TEXT);
        $stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if ($result['count'] > 0) {
            echo json_encode(['status' => 'error', 'error' => 'Username or email already exists']);
            exit;
        }

        // Insert new user
        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindValue(':username', $data['username'], SQLITE3_TEXT);
        $stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
        $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT), SQLITE3_TEXT); // secure hash
        $stmt->execute();

        echo json_encode(['status' => 'success']);
        exit;
    }

    // Handle new entry
    elseif ($data['type'] === 'entry') {
        if (!isset($data['user_id'], $data['date'], $data['amount'], $data['category'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'Missing required entry fields']);
            exit;
        }

        $amount = floatval($data['amount']);
        if ($amount < 0 || !is_numeric($data['amount'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'Invalid amount']);
            exit;
        }

        $db = new SQLite3('data.db');

        // Generate a new entry_id for this user
        $stmt = $db->prepare('SELECT IFNULL(MAX(entry_id), 0) + 1 AS new_id FROM entries WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $data['user_id'], SQLITE3_INTEGER);
        $entry_id = $stmt->execute()->fetchArray(SQLITE3_ASSOC)['new_id'];

        // Insert entry
        $stmt = $db->prepare('INSERT INTO entries (user_id, entry_id, amount, category, note) VALUES (:user_id, :entry_id, :amount, :category, :note)');
        $stmt->bindValue(':user_id', $data['user_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':entry_id', $entry_id, SQLITE3_INTEGER);
        $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
        $stmt->bindValue(':category', $data['category'], SQLITE3_TEXT);
        $stmt->bindValue(':note', $data['note'] ?? '', SQLITE3_TEXT);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'entry_id' => $entry_id]);
        exit;
    }
    elseif ($data['type'] === 'log in') {
        // Expect fields: identifier (username or email) and password
        if (!isset($data['identifier'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'Missing identifier or password']);
            exit;
        }
    
        $identifier = $data['identifier'];
        $password = $data['password'];
    
        // Connect to the database
        $db = new SQLite3('data.db');
    
        // Prepare query to check for user by username or email
        $stmt = $db->prepare("
            SELECT user_id, username, email, password 
            FROM users 
            WHERE username = :identifier OR email = :identifier
        ");
        $stmt->bindValue(':identifier', $identifier, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            // Password matches
            echo json_encode([
                'status' => 'success',
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email']
            ]);
            exit;
        } else {
            // Invalid credentials
            echo json_encode(['status' => 'error', 'error' => 'Invalid username/email or password']);
            exit;
        }
    }

    // Unknown type
    else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'error' => 'Invalid request type']);
        exit;
    }
}

// Serve HTML if GET
readfile('index.html');
?>
