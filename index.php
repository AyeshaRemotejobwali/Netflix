<?php
session_start();
require 'db.php';

// Fetch featured and trending movies
$stmt = $pdo->query("SELECT * FROM movies ORDER BY RAND() LIMIT 3");
$featured = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM movies WHERE genre = 'Comedy' LIMIT 3");
$trending = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix Clone</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #141414;
            color: #fff;
        }
        .navbar {
            background: #000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }
        .navbar a:hover {
            color: #e50914;
        }
        .carousel {
            position: relative;
            height: 500px;
            overflow: hidden;
        }
        .carousel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .carousel-text {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px;
            border-radius: 5px;
        }
        .section {
            padding: 20px;
        }
        .section h2 {
            font-size: 24px;
            margin-bottom: 10px;
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
            .carousel {
                height: 300px;
            }
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="#" onclick="navigate('index.php')">Home</a>
            <a href="#" onclick="navigate('search.php')">Search</a>
            <a href="#" onclick="navigate('watchlist.php')">Watchlist</a>
        </div>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="navigate('profile.php')">Profile</a>
                <a href="#" onclick="navigate('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="navigate('login.php')">Login</a>
                <a href="#" onclick="navigate('signup.php')">Signup</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="carousel">
        <?php foreach ($featured as $index => $movie): ?>
            <div class="carousel-item" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                <img src="<?php echo $movie['thumbnail_url']; ?>" alt="<?php echo $movie['title']; ?>">
                <div class="carousel-text">
                    <h2><?php echo $movie['title']; ?></h2>
                    <p><?php echo $movie['description']; ?></p>
                    <button onclick="navigate('watch.php?id=<?php echo $movie['movie_id']; ?>')">Watch Now</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <h2>Trending Now</h2>
        <div class="movie-grid">
            <?php foreach ($trending as $movie): ?>
                <div class="movie-card" onclick="navigate('watch.php?id=<?php echo $movie['movie_id']; ?>')">
                    <img src="<?php echo $movie['thumbnail_url']; ?>" alt="<?php echo $movie['title']; ?>">
                    <p><?php echo $movie['title']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
        }
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);

        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
