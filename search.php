<?php
session_start();
require 'db.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';

$query = "SELECT * FROM movies WHERE 1=1";
$params = [];
if ($search) {
    $query .= " AND (title LIKE ? OR actors LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($genre) {
    $query .= " AND genre = ?";
    $params[] = $genre;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch genres for filter
$genres = $pdo->query("SELECT DISTINCT genre FROM movies")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Netflix Clone</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #141414;
            color: #fff;
        }
        .search-container {
            padding: 20px;
            text-align: center;
        }
        .search-container input, .search-container select {
            padding: 10px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            background: #333;
            color: #fff;
        }
        .search-container button {
            padding: 10px 20px;
            background: #e50914;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
        .search-container button:hover {
            background: #f40612;
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
    <div class="search-container">
        <form method="GET">
            <input type="text" name="q" placeholder="Search by title or actor" value="<?php echo htmlspecialchars($search); ?>">
            <select name="genre">
                <option value="">All Genres</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?php echo $g; ?>" <?php echo $g === $genre ? 'selected' : ''; ?>><?php echo $g; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card" onclick="navigate('watch.php?id=<?php echo $movie['movie_id']; ?>')">
                <img src="<?php echo $movie['thumbnail_url']; ?>" alt="<?php echo $movie['title']; ?>">
                <p><?php echo $movie['title']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
