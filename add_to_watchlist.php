<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_POST['movie_id'])) {
    echo "Error: Not logged in or invalid movie.";
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'];

$stmt = $pdo->prepare("INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?) 
                       ON DUPLICATE KEY UPDATE added_at = NOW()");
$stmt->execute([$user_id, $movie_id]);
echo "Added to watchlist";
?>
