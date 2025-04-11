<?php
require_once 'includes/layout.php';

// Check if movie ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: movies.php");
    exit();
}

$movieID = (int)$_GET['id'];
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

// Get movie details
$movieQuery = "SELECT * FROM movieTable WHERE movieID = $movieID";
$movieResult = mysqli_query($link, $movieQuery);
$movie = mysqli_fetch_assoc($movieResult);

if (!$movie) {
    header("Location: movies.php");
    exit();
}

// Get showtimes
$showtimeQuery = "SELECT * FROM scheduletable 
                 WHERE movieID = $movieID 
                 AND scheduleDate >= CURDATE()
                 ORDER BY scheduleDate, scheduleTime";
$showtimeResult = mysqli_query($link, $showtimeQuery);

// Get similar movies
$similarQuery = "SELECT * FROM movieTable 
                WHERE movieGenre LIKE '%{$movie['movieGenre']}%'
                AND movieID != $movieID
                LIMIT 4";
$similarResult = mysqli_query($link, $similarQuery);

function getValidPoster($movie)
{
    $imgDir = 'img/';
    $default = $imgDir . 'default-poster.jpg';

    // If no image specified, use default
    if (empty($movie['movieImg'])) {
        return $default;
    }

    // Check for different image naming patterns
    $possibleFiles = [
        $imgDir . $movie['movieID'] . '.jpg',
        $imgDir . 'movie-' . $movie['movieID'] . '.jpg',
        $imgDir . preg_replace('/[^\w\-\.]/', '', $movie['movieImg']),
        $imgDir . 'movie-poster-' . $movie['movieID'] . '.jpg'
    ];

    // Return first valid image found
    foreach ($possibleFiles as $file) {
        if (file_exists($file) && is_readable($file)) {
            return $file;
        }
    }

    return $default;
}

renderHeader();
?>

<section class="movie-hero" style="background: linear-gradient(rgba(15, 15, 26, 0.8), rgba(15, 15, 26, 0.8)), url('./<?php echo $movie['movieImg']; ?>') no-repeat center center/cover;">
    <div class="hero-content">
        <h1><?php echo $movie['movieTitle']; ?></h1>
        <div class="movie-meta">
            <span><?php echo $movie['movieGenre']; ?></span>
            <span><?php echo $movie['movieDuration']; ?> min</span>
            <span>Release Date: <?php echo $movie['movieRelDate']; ?></span>
        </div>
    </div>
</section>

<section class="movie-details">
    <div class="container">
        <div class="detail-content">
            <div class="trailer-container">
                <div class="trailer-wrapper">
                    <?php if (!empty($movie['movieTrailerLink'])): 
                        // Extract YouTube video ID from URL
                        $videoId = '';
                        $url = $movie['movieTrailerLink'];
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                            $videoId = $matches[1];
                        }
                    ?>
                        <?php if ($videoId): ?>
                            <iframe width="100%" height="100%" 
                                src="https://www.youtube.com/embed/<?php echo $videoId; ?>?autoplay=0&rel=0" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        <?php else: ?>
                            <div class="trailer-placeholder">
                                <i class="fas fa-play"></i>
                                <p>Invalid Trailer Link</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="trailer-placeholder">
                            <i class="fas fa-play"></i>
                            <p>No Trailer Available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="movie-info">
                <h2>Synopsis</h2>
                <p><?php echo $movie['movieDescription']; ?></p>

                <div class="detail-grid">
                    <div class="detail-group">
                        <h3>Director</h3>
                        <p><?php echo $movie['movieDirector']; ?></p>
                    </div>
                    <div class="detail-group">
                        <h3>Cast</h3>
                        <p><?php echo $movie['movieActors']; ?></p>
                    </div>
                    <div class="detail-group">
                        <h3>Release Date</h3>
                        <p><?php echo date('F j, Y', strtotime($movie['movieRelDate'])); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (mysqli_num_rows($showtimeResult) > 0): ?>
            <div class="showtimes-section">
                <h2>Showtimes</h2>
                <div class="showtime-cards">
                    <?php while ($showtime = mysqli_fetch_assoc($showtimeResult)):
                        $showDate = date('l, F j', strtotime($showtime['scheduleDate']));
                        $showTime = date('g:i A', strtotime($showtime['scheduleTime']));
                    ?>
                        <div class="showtime-card">
                            <div class="showtime-info">
                                <h3><?php echo $showDate; ?></h3>
                                <p><?php echo $showTime; ?></p>
                                <p class="theater"><?php echo ucfirst(str_replace('-', ' ', $showtime['theatre'])); ?> Theater</p>
                            </div>
                            <a href="booking.php?id=<?php echo $movieID; ?>&date=<?php echo $showtime['scheduleDate']; ?>&time=<?php echo date('H-i', strtotime($showtime['scheduleTime'])); ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($similarResult) > 0): ?>
            <div class="similar-movies">
                <h2>You Might Also Like</h2>
                <div class="movies-grid">
                    <?php while ($similar = mysqli_fetch_assoc($similarResult)): ?>
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img src="./<?php echo $similar['movieImg']; ?>" alt="<?php echo $similar['movieTitle']; ?>">
                                <div class="movie-overlay">
                                    <a href="movie-details.php?id=<?php echo $similar['movieID']; ?>" class="btn btn-outline">Details</a>
                                </div>
                            </div>
                            <div class="movie-info">
                                <h4><?php echo $similar['movieTitle']; ?></h4>
                                <div class="movie-meta">
                                    <span><?php echo $similar['movieGenre']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .movie-hero {
        height: 70vh;
        display: flex;
        align-items: flex-end;
        padding: 0 2rem 4rem 2rem;
        position: relative;
    }

    .movie-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: linear-gradient(transparent, var(--primary));
    }

    .hero-content {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
        z-index: 1;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(90deg, #4cc9f0, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .movie-meta {
        display: flex;
        gap: 1.5rem;
        font-size: 1.1rem;
    }

    .movie-details {
        padding: 4rem 2rem;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .detail-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    .trailer-container {
        position: relative;
        padding-top: 56.25%;
        /* 16:9 Aspect Ratio */
        border-radius: 8px;
        overflow: hidden;
        background: var(--secondary);
    }

    .trailer-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .trailer-placeholder {
        text-align: center;
        color: var(--accent);
    }

    .trailer-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .movie-info h2 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        color: var(--accent);
    }

    .movie-info p {
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 2rem;
    }

    .detail-group h3 {
        color: var(--accent);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .showtimes-section {
        margin: 4rem 0;
    }

    .showtimes-section h2 {
        font-size: 1.8rem;
        margin-bottom: 2rem;
        color: var(--accent);
    }

    .showtime-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .showtime-card {
        background: var(--secondary);
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .showtime-info h3 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .showtime-info p {
        color: rgba(255, 255, 255, 0.7);
    }

    .theater {
        color: var(--accent) !important;
        font-weight: 600;
    }

    .similar-movies {
        margin-top: 4rem;
    }

    .similar-movies h2 {
        font-size: 1.8rem;
        margin-bottom: 2rem;
        color: var(--accent);
    }

    .movies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 2rem;
    }

    /* Reuse movie card styles */
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
        height: 0;
        padding-bottom: 150%;
        overflow: hidden;
    }

    .movie-poster img {
        position: absolute;
        top: 0;
        left: 0;
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
        padding: 1rem;
    }

    .movie-info h4 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .movie-meta {
        display: flex;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .detail-content {
            grid-template-columns: 1fr;
        }

        .hero-content h1 {
            font-size: 2.5rem;
        }
    }
</style>

<script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
<script>
    // Initialize ScrollReveal
    ScrollReveal().reveal('.detail-content, .showtimes-section, .similar-movies', {
        delay: 200,
        distance: '30px',
        origin: 'bottom',
        interval: 100,
        easing: 'ease-in-out'
    });
</script>

<?php
mysqli_close($link);
renderFooter();
?>