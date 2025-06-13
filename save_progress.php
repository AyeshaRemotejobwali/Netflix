<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_POST['movie_id']) || !isset($_POST['watch_time'])) {
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'];
$watch_time = $_POST['watch_time'];

$stmt = $pdo->prepare("INSERT INTO watch_history (user_id, movie_id, watch_time) VALUES (?, ?, ?) 
                       ON DUPLICATE KEY UPDATE watch_time = ?, watched_at = NOW()");
$stmt->execute([$user_id, $movie_id, $watch_time, $watch_time]);
echo "Progress saved";
?>
