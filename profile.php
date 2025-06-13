<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->execute([$username, $email, $user_id]);
    echo "<script>alert('Profile updated!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Netflix Clone</title>
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
        <h2>Edit Profile</h2>
        <form method="POST">
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
