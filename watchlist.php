<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT m.* FROM watchlist w JOIN movies m ON w.movie_id = m.movie_id WHERE w.user_id = ?");
$stmt->execute([$user_id]);
$watchlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Netflix Clone</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #141414;
            color: #fff;
        }
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }
        .movie-card {
            background: #222;
            border-radius: 5px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .movie-card:hover {
            transform: scale(1.05);
        }
        .movie-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .movie-card p {
            padding: 10px;
            margin: 0;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="section">
        <h2>My Watchlist</h2>
        <div class="movie-grid">
            <?php foreach ($watchlist as $movie): ?>
                <div class="movie-card" onclick="navigate('watch.php?id=<?php echo $movie['movie_id']; ?>')">
                    <img src="<?php echo $movie['thumbnail_url']; ?>" alt="<?php echo $movie['title']; ?>">
                    <p><?php echo $movie['title']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
