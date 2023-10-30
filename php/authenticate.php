<?php
include 'functions.php';

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['email'], $_POST['password'])) {
    $_SESSION['error'] = 'Please fill both the email and password fields!';
    header('Location: ../login.php');
    exit();
}

$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM Employee WHERE email = ?');
$stmt->execute([ $_POST['email'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if ($account) {
    if (password_verify($_POST['password'], $account['password'])) {
        if ($account['activation_code'] != '1') {
            $_SESSION['error'] = 'Your account has not been verified by an admin yet. Please wait for verification.';
            header('Location: ../view/login.php');
            exit();
        } else {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $account['email']; 
            $_SESSION['id'] = $account['id'];
            $_SESSION['role'] = $account['role'];

            if (isset($_POST['rememberme'])) {
                $cookiehash = !empty($account['rememberme']) ? $account['rememberme'] : password_hash($account['id'] . $account['email'] . 'yoursecretkey', PASSWORD_DEFAULT);
                $days = 30;
                setcookie('rememberme', $cookiehash, (int)(time()+60*60*24*$days));
                $stmt = $pdo->prepare('UPDATE Employee SET rememberme = ? WHERE id = ?');
                $stmt->execute([ $cookiehash, $account['id'] ]);
            }

            $date = date('Y-m-d\TH:i:s');
            $stmt = $pdo->prepare('UPDATE Employee SET last_seen = ? WHERE id = ?');
            $stmt->execute([ $date, $account['id'] ]);
            
            $_SESSION['success'] = 'Login successful!';
            header('Location: ../view/crm/index.php'); // replace this with your actual destination
            exit();
        }
    } else {
        $_SESSION['error'] = 'Incorrect email and/or password!';
        header('Location: ../view/login.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Incorrect email and/or password!';
    header('Location: ../view/login.php');
    exit();
}
?>
