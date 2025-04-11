<?php
require_once 'includes/layout.php';
renderHeader();


/**
 * Get validated movie poster path
 * @param array $movie Movie data record
 * @return string Safe image path
 */
function getValidPoster($movie)
{
    $imgDir = 'img/';
    $default = $imgDir . 'default-poster.jpg';

    // If no image specified, use default
    if (empty($movie['movieImg'])) {
        return $default;
    }

    // Get the actual filename from the database path
    $dbPath = $movie['movieImg'];
    $filename = basename($dbPath);
    
    // Check if file exists in img directory
    $fullPath = $imgDir . $filename;
    
    if (file_exists($fullPath) && is_readable($fullPath)) {
        return $fullPath;
    }

    // Fallback to default if file doesn't exist
    return $default;
}

// Database connection and query
$movies = [];
$db = new mysqli("localhost", "root", "", "cinema_db", 3307);

if ($db->connect_errno) {
    error_log("Database connection failed: " . $db->connect_error);
    $movies = [];
} else {
    $result = $db->query("
        SELECT movieID, movieTitle, movieImg, movieGenre, movieDuration 
        FROM movieTable 
        ORDER BY movieID DESC 
        LIMIT 6
    ");

    if (!$result) {
        error_log("Query failed: " . $db->error);
        $movies = [];
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
        $result->free();
    }
    $db->close();
}

?>

<section class="movies-hero">
    <div class="hero-content">
        <h2>Our Movie Collection</h2>
        <p>Discover the latest blockbusters and timeless classics in our premium theaters.</p>
    </div>
</section>

<section class="all-movies">
    <div class="section-header">
        <h3>All Movies</h3>
        <div class="sort-options">
            <select id="sort-by">
                <option value="latest">Latest Releases</option>
                <option value="title">A-Z</option>
                <option value="genre">By Genre</option>
            </select>
        </div>
    </div>

    <div class="movies-grid">
        <?php foreach ($movies as $movie): ?>
            <article class="movie-card">
                <div class="movie-poster">
                    <img src="<?= getValidPoster($movie) ?>"
                        alt="<?= htmlspecialchars($movie['movieTitle']) ?>"
                        loading="lazy"
                        onerror="this.src='img/default-poster.jpg'">
                    <div class="movie-overlay">
                        <a href="booking.php?id=' . $movie['movieID'] . '" class="btn btn-primary">Book Now</a>
                        <a href="movie-details.php?id=<?= $movie['movieID'] ?>" class="btn btn-outline">Details</a>
                    </div>
                </div>
                <div class="movie-info">
                    <h4><?= htmlspecialchars($movie['movieTitle']) ?></h4>
                    <div class="movie-meta">
                        <span><?= htmlspecialchars($movie['movieGenre']) ?></span>
                        <span><?= (int)$movie['movieDuration'] ?> mins</span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>


    </div>


    <!-- <div class="movies-grid">
        <?php
        $link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
        $movieQuery = "SELECT * FROM movieTable ORDER BY movieRelDate DESC";
        $movieResult = mysqli_query($link, $movieQuery);

        while ($movie = mysqli_fetch_assoc($movieResult)) {
            echo '<div class="movie-card">
                <div class="movie-poster">
                    <img src=" getValidPoster($movie) ' . '" alt="' . $movie['movieTitle'] . '">
                    <div class="movie-overlay">
                        <a href="booking.php?id=' . $movie['movieID'] . '" class="btn btn-primary">Book Now</a>
                        <a href="movie-details.php?id=' . $movie['movieID'] . '" class="btn btn-outline">Details</a>
                    </div>
                </div>
                <div class="movie-info">
                    <h4>' . $movie['movieTitle'] . '</h4>
                    <div class="movie-meta">
                        <span>' . $movie['movieGenre'] . '</span>
                        <span>' . $movie['movieDuration'] . ' min</span>
                        <span>' . date('M j, Y', strtotime($movie['movieRelDate'])) . '</span>
                    </div>
                </div>
            </div>';
        }
        mysqli_close($link);
        ?>
    </div> -->
</section>

<style>
    .movies-hero {
        background: linear-gradient(rgba(15, 15, 26, 0.8), rgba(15, 15, 26, 0.8)),
            url('img/hero-bg.jpg') no-repeat center center;
        background-size: cover;
        height: 70vh;
        display: flex;
        align-items: center;
        padding: 0 2rem;
        position: relative;
    }

    .hero-content {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    .movies-hero h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        background: linear-gradient(90deg, #4cc9f0, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        max-width: 700px;


    }

    .movies-hero p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 600px;
        line-height: 1.6;

    }

    .all-movies {
        padding: 4rem 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .sort-options select {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        background: var(--secondary);
        color: var(--text);
        border: 1px solid var(--accent);
    }

    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 2rem;
    }

    .movie-card {
        background: var(--secondary);
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s;
    }

    .movie-card:hover {
        transform: translateY(-10px);
    }

    .movie-poster {
        position: relative;
        height: 320px;
        overflow: hidden;
    }

    .movie-poster img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .movie-card:hover .movie-poster img {
        transform: scale(1.05);
    }

    .movie-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .movie-card:hover .movie-overlay {
        opacity: 1;
    }

    .movie-info {
        padding: 1.5rem;
    }

    .movie-info h4 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .movie-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
    }
</style>

<script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
<script>
    // Initialize ScrollReveal
    ScrollReveal().reveal('.hero-content', {
        delay: 200,
        distance: '50px',
        origin: 'left',
        easing: 'ease-in-out'
    });

    ScrollReveal().reveal('.movie-card', {
        delay: 200,
        distance: '30px',
        origin: 'bottom',
        interval: 100,
        easing: 'cubic-bezier(0.5, 0, 0, 1)'
    });

    // Sort functionality
    document.getElementById('sort-by').addEventListener('change', function() {
        const selectedValue = this.value;
        const movies = document.querySelectorAll('.movie-card');
        movies.forEach(movie => {
            const title = movie.querySelector('.movie-info h4').textContent;
            if (selectedValue === 'title') {
                if (title.toLowerCase() < selectedValue.toLowerCase()) {
                    movie.style.order = 1;
                } else {
                    movie.style.order = 2;
                }
            } else if (selectedValue === 'release-date') {
                const releaseDate = movie.querySelector('.movie-meta span').textContent;
                if (releaseDate < selectedValue) {
                    movie.style.order = 1;
                } else {
                    movie.style.order = 2;
                }
            }
        });
    });
</script>

<?php
renderFooter();
?>