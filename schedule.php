<?php
require_once 'includes/layout.php';
renderHeader();

$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get selected date or default to tomorrow
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d', strtotime('+1 day'));

// Generate dates for next 7 days
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('F j, Y', strtotime("+$i days"));
}

// Get all movies
$movieQuery = "SELECT * FROM movieTable";
$movieResult = mysqli_query($link, $movieQuery);

// Get schedule data for selected date
$query = "SELECT 
    m.movieID,
    m.movieTitle,
    m.movieImg,
    m.movieDuration,
    m.movieGenre,
    s.scheduleTime,
    s.scheduleDate,
    s.theatre
FROM movieTable m
JOIN scheduletable s ON m.movieID = s.movieID
WHERE s.scheduleDate = '$selectedDate'
ORDER BY m.movieTitle, s.theatre, s.scheduleTime";

$result = mysqli_query($link, $query);

// Organize showtimes by movie and hall
$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $movieID = $row['movieID'];
    if (!isset($movies[$movieID])) {
        $movies[$movieID] = [
            'title' => $row['movieTitle'],
            'image' => $row['movieImg'],
            'duration' => $row['movieDuration'],
            'genre' => $row['movieGenre'],
            'halls' => [],
        ];
    }
    $movies[$movieID]['halls'][$row['theatre']][] = [
        'time' => $row['scheduleTime'],
        'date' => $row['scheduleDate']
    ];
}

?>
<div class="schedule-container">
    <h1>Movie Schedule</h1>

    <!-- Date Selector -->
    <div class="date-selector">
        <?php foreach ($dates as $index => $date):
            $dateKey = date('Y-m-d', strtotime($date));
        ?>
            <div class="date-item <?php echo $dateKey == $selectedDate ? 'selected' : '' ?>"
                data-date="<?php echo $dateKey ?>">
                <?php echo $date ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Schedule Table -->
    <div class="schedule-table-container">
        <?php if (empty($movies)): ?>
            <p class="no-showtimes">No showtimes available for selected date</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Main Hall</th>
                        <th>VIP Hall</th>
                        <th>Private Hall</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movies as $movieID => $movie): ?>
                        <tr class="movie-row">
                            <td class="movie-info">
                                <img src="<?php echo $movie['image'] ?>" alt="<?php echo $movie['title'] ?>">
                                <div>
                                    <h3><?php echo $movie['title'] ?></h3>
                                    <p><?php echo $movie['duration'] ?> min | <?php echo $movie['genre'] ?></p>
                                    <a href="booking.php?id=<?php echo $movieID ?>" class="btn-book">Book Now</a>
                                </div>
                            </td>
                            <?php
                            $hallTypes = ['Main Hall', 'VIP Hall', 'Private Hall'];
                            foreach ($hallTypes as $hallType):
                            ?>
                                <td class="showtimes">
                                    <?php if (isset($movie['halls'][$hallType])):
                                        foreach ($movie['halls'][$hallType] as $showtime): ?>
                                        
                                            <a href="booking.php?id=<?= htmlspecialchars($movieID) ?>&date=<?= htmlspecialchars(date('Y-m-d', strtotime($showtime['date']))) ?>&time=<?= htmlspecialchars(date('H-i', strtotime($showtime['time']))) ?>">
                                                <?= htmlspecialchars(date('g:i A', strtotime($showtime['time']))) ?>
                                            </a>
                                            
                                        <?php endforeach;
                                    else: ?>
                                        <span class="no-showtimes">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<style>
    .schedule-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: calc(10vh + 2rem) 1rem 1rem;
    }

    .date-selector {
        display: flex;
        gap: 1rem;
        margin: 2rem 0;
        overflow-x: auto;
        padding-bottom: 1rem;
    }

    .date-item {
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        min-width: 120px;
        text-align: center;
    }

    .date-item.selected {
        background: #4cc9f0;
        color: white;
        border-color: #4cc9f0;
    }

    .schedule-table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .movie-info {
        display: flex;
        gap: 1rem;
        align-items: center;
        text-align: left;
    }

    .movie-info img {
        width: 80px;
        height: 120px;
        object-fit: cover;
    }

    .showtimes a,
    .showtimes div {
        display: block;
        padding: 0.5rem;
        margin: 0.25rem 0;
        background: #f5f5f5;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: rgb(10, 52, 65);
    }

    .showtimes a:hover {
        background: #4cc9f0;
        color: white;
    }

    .no-showtimes {
        color: #999;
        font-style: italic;
    }

    .btn-book {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #4cc9f0;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .date-selector {
            flex-wrap: wrap;
        }

        .movie-info {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<script>
    // Date selection functionality
    document.querySelectorAll('.date-item').forEach(item => {
        item.addEventListener('click', () => {
            const date = item.dataset.date;
            window.location.href = `schedule.php?date=${date}`;
        });
    });
</script>
<?php
renderFooter();

mysqli_close($link);
?>