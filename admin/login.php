<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
require "../includes/db.inc.php";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        // Prepare and execute query
        $stmt = connectToDB()->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user and password
        if ($user && $password === $user['password_hash']) {
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['admin'] = true;

            // Redirect based on role
            if ($_SESSION['admin']) {
                header('Location: index.php');
                exit;
            }
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in both fields.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #008080;
            font-family: 'Tahoma', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        form {
            background: #d4d0c8;
            border: 2px solid #808080;
            padding: 20px;
            box-shadow: inset -2px -2px 0 #ffffff, inset 2px 2px 0 #000000;
            text-align: center;
        }

        input[type="text"],
        input[type="password"] {
            width: 25%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #808080;
            background: #f0f0f0;
            box-shadow: inset 1px 1px 0 #ffffff, inset -1px -1px 0 #808080;
        }

        button {
            padding: 10px 20px;
            background: #c0c0c0;
            border: 2px solid #808080;
            box-shadow: inset 1px 1px 0 #ffffff, inset -1px -1px 0 #808080;
            cursor: pointer;
        }

        button:hover {
            background: #d0d0d0;
        }

        .admin_button {
            position: absolute;
            right: 10px;
            top: 10px;

            padding: 10px 20px;
            font-size: 14px;
            color: #000;
            background-color: #c0c0c0;
            border: 2px solid #fff;
            /* Top/left border */
            border-bottom-color: #808080;
            /* Bottom shadow */
            border-right-color: #808080;
            /* Right shadow */
            text-decoration: none;
            box-shadow: 2px 2px 0 #808080;
            /* Shadow effect */
            cursor: pointer;
            text-align: center;

            &:hover {
                color: #ff0081;
            }

            &:active {
                border: 2px solid #808080;
                /* Inset border */
                border-top-color: #fff;
                /* Top highlight */
                border-left-color: #fff;
                /* Left highlight */
                box-shadow: none;
                /* Remove shadow */
                background-color: #a0a0a0;
                /* Darker background */
            }
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <form method="POST">
        <h1>Login</h1>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="../index.php" class="admin_button">Back</a>
</body>

</html>