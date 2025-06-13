<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$movie_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// Fetch watch history
$stmt = $pdo->prepare("SELECT watch_time FROM watch_history WHERE user_id = ? AND movie_id = ?");
$stmt->execute([$user_id, $movie_id]);
$history = $stmt->fetch(PDO::FETCH_ASSOC);
$watch_time = $history ? $history['watch_time'] : 0;

// Recommendations
$stmt = $pdo->prepare("SELECT * FROM movies WHERE genre = ? AND movie_id != ? LIMIT 3");
$stmt->execute([$movie['genre'], $movie_id]);
$recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch - <?php echo $movie['title']; ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #141414;
            color: #fff;
        }
        .video-container {
            position: relative;
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
        }
        video {
            width: 100%;
            height: auto;
        }
        .controls {
            margin: 20px 0;
            text-align: center;
        }
        .controls button {
            padding: 10px 20px;
            background: #e50914;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
        .controls button:hover {
            background: #f40612;
        }
        .section {
            padding: 20px;
        }
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
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
            .video-container {
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="video-container">
        <video id="videoPlayer" controls>
            <source src="<?php echo $movie['video_url']; ?>" type="video/mp4">
        </video>
        <div class="controls">
            <button onclick="addToWatchlist(<?php echo $movie['movie_id']; ?>)">Add to Watchlist</button>
        </div>
    </div>

    <div class="section">
        <h2>Recommended for You</h2>
        <div class="movie-grid">
            <?php foreach ($recommendations as $rec): ?>
                <div class="movie-card" onclick="navigate('watch.php?id=<?php echo $rec['movie_id']; ?>')">
                    <img src="<?php echo $rec['thumbnail_url']; ?>" alt="<?php echo $rec['title']; ?>">
                    <p><?php echo $rec['title']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        const video = document.getElementById('videoPlayer');
        video.currentTime = <?php echo $watch_time; ?>;

        video.addEventListener('timeupdate', () => {
            fetch('save_progress.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `movie_id=<?php echo $movie_id; ?>&watch_time=${Math.floor(video.currentTime)}`
            });
        });

        function addToWatchlist(movieId) {
            fetch('add_to_watchlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `movie_id=${movieId}`
            }).then(response => response.text())
              .then(data => alert(data));
        }

        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
