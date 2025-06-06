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
        SELECT DISTINCT m.movieID, m.movieTitle, m.movieImg, m.movieGenre, m.movieDuration 
        FROM movietable m
        JOIN scheduletable s ON m.movieID = s.movieID
        WHERE s.scheduleDate >= CURDATE()
        ORDER BY m.movieRelDate DESC
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

<section class="hero">
    <div class="hero-content">
        <h2>Experience Cinema Like Never Before</h2>
        <p>Immerse yourself in our premium theaters with cutting-edge technology and luxurious comfort.</p>
        <a href="movies.php" class="btn btn-primary">Browse Movies</a>
    </div>
</section>

<section class="now-showing">
    <div class="section-header">
        <h3>Now Showing</h3>
        <a href="schedule.php" class="view-all">View All</a>
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
                        <a href="booking.php?id=<?= $movie['movieID'] ?>" class="btn btn-primary">Book Tickets</a>
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


        <!-- while ($movie = mysqli_fetch_assoc($movieResult)) {
        echo <<<HTML
            <div class="movie-card">
            <div class="movie-poster">
                <img src="img/{$movie['movieImg']}" alt="{$movie['movieTitle']}">
                <div class="movie-overlay">
                    <a href="booking.php?id={$movie['movieID']}" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            <div class="movie-info">
                <h4>{$movie['movieTitle']}</h4>
                <div class="movie-meta">
                    <span>{$movie['movieGenre']}</span>
                    <span>{$movie['movieDuration']} min</span>
                </div>
            </div>
    </div>
    HTML;
    }
    mysqli_close($link);
    ?> -->
    </div>
</section>

<section class="premium-features">
    <h3>Why Choose Premium Cinema?</h3>
    <div class="features-grid">
        <div class="feature">
            <i class="fas fa-couch"></i>
            <h4>Luxury Seating</h4>
            <p>Recliner seats with ample legroom and premium materials for maximum comfort.</p>
        </div>
        <div class="feature">
            <i class="fas fa-film"></i>
            <h4>4K Laser Projection</h4>
            <p>Crystal clear images with our state-of-the-art 4K laser projection systems.</p>
        </div>
        <div class="feature">
            <i class="fas fa-volume-up"></i>
            <h4>Dolby Atmos</h4>
            <p>Immersive 360° sound that puts you at the center of the action.</p>
        </div>
        <div class="feature">
            <i class="fas fa-utensils"></i>
            <h4>Gourmet Dining</h4>
            <p>Enjoy chef-prepared meals and craft cocktails delivered to your seat.</p>
        </div>
    </div>
</section>

<script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
<style>
    /* Add smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    .hero {
        background: linear-gradient(rgba(15, 15, 26, 0.7), rgba(15, 15, 26, 0.7)),
            url('img/hero-bg.jpg') no-repeat center center;
        background-size: cover;
        height: 80vh;
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

    .hero h2 {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        background: linear-gradient(90deg, #4cc9f0, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        max-width: 700px;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        max-width: 600px;
        line-height: 1.6;
    }

    section {
        padding: 4rem 2rem;
        max-width: 1800px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h3 {
        font-size: 1.8rem;
        color: var(--accent);
    }

    .view-all {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .view-all:hover {
        color: var(--highlight);
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
        align-items: center;
        justify-content: center;
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
        justify-content: space-between;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    .poster-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        padding: 1rem;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .movie-card:hover .poster-overlay {
        opacity: 1;
    }

    .premium-features {
        background: var(--secondary);
        border-radius: 12px;
        margin: 4rem auto;
        text-align: center;
    }

    .premium-features h3 {
        font-size: 2rem;
        margin-bottom: 3rem;
        color: var(--accent);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 3rem;
    }

    .feature {
        padding: 2rem;
    }

    .feature i {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 1.5rem;
    }

    .feature h4 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .feature p {
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .hero h2 {
            font-size: 2.5rem;
        }

        .hero p {
            font-size: 1rem;
        }

        section {
            padding: 2rem 1rem;
        }
    }
</style>

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

    ScrollReveal().reveal('.feature', {
        delay: 200,
        distance: '40px',
        origin: 'bottom',
        interval: 100,
        easing: 'ease-in-out'
    });

    // Add scroll-triggered header effect
    const header = document.querySelector('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.background = 'rgba(15,15,26,0.95)';
            header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            header.style.background = 'linear-gradient(180deg, rgba(15,15,26,0.9) 0%, rgba(15,15,26,0.7) 100%)';
            header.style.boxShadow = 'none';
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>

<?php
renderFooter();
?>