<?php
include 'functions.php';

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST['email'], $_POST['password'])) {
    exit('Please fill both the email and password fields!');
}

$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM Employee WHERE email = ?');
$stmt->execute([ $_POST['email'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if ($account) {
    if (password_verify($_POST['password'], $account['password'])) {
        // Check if the account has been verified by admin
        if ($account['activation_code'] != '1') {
            echo 'Your account has not been verified by an admin yet. Please wait for verification.';
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
            
            header('Location: ' . '/crm/');
        }
    } else {
        echo 'Incorrect email and/or password!';
    }
} else {
    echo 'Incorrect email and/or password!';
}
?>
