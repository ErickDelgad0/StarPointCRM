<?php
include 'functions.php';

// Validate that required POST data exists
if (empty(trim($_POST['username'])) || empty(trim($_POST['password'])) || empty(trim($_POST['cpassword'])) || empty(trim($_POST['email'])) || empty(trim($_POST['firstname'])) || empty(trim($_POST['lastname']))) {
    exit('Please complete the registration form!');
}

// Validate email format
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Please provide a valid email address!');
}

// Validate username format
if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['username'])) {
    exit('Username must contain only letters and numbers!');
}

// Validate password length
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    exit('Password must be between 5 and 20 characters long!');
}

// Validate password confirmation
if ($_POST['cpassword'] != $_POST['password']) {
    exit('Passwords do not match!');
}

// Check if account with that username or email already exists
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM Employee WHERE username = ? OR email = ?');
$stmt->execute([ $_POST['username'], $_POST['email'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if ($account) {
    exit('Username and/or email exists!');
} else {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'guest';
    $date = date('Y-m-d\TH:i:s');
    
    $stmt = $pdo->prepare('INSERT INTO Employee (first_name, last_name, username, password, email, role, registered, last_seen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    
    if ($stmt->execute([ $_POST['firstname'], $_POST['lastname'], $_POST['username'], $password, $_POST['email'], $role, $date, $date ])) {
        header('location: ../view/login.php');
        exit;
    } else {
        echo 'There was an error registering your account.';
    }
}
