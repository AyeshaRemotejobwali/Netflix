<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        echo "<script>alert('Signup successful! Please login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Signup failed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Netflix Clone</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #141414;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #222;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #333;
            color: #fff;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background: #e50914;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #f40612;
        }
        .form-container a {
            color: #e50914;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
            <a href="#" onclick="navigate('login.php')">Already have an account? Login</a>
        </form>
    </div>

    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
