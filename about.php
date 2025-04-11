<?php
require_once 'includes/layout.php';
renderHeader();
?>

<section class="about-hero">
    <div class="hero-content">
        <h2>Our Premium Experience</h2>
        <p>Discover what makes our cinema truly exceptional</p>
    </div>
</section>

<section class="about-story">
    <div class="container">
        <div class="section-header">
            <h3>Our Story</h3>
            <p class="section-subtitle">Redefining cinema since 2010</p>
        </div>
        <div class="story-content">
            <div class="story-text">
                <p>Founded with a vision to revolutionize movie-going, Premium Cinema has set new standards for luxury entertainment. What began as a single-screen theater has grown into the region's most acclaimed cinema destination.</p>
                <p>We combine cutting-edge technology with unparalleled comfort to create unforgettable experiences. Our dedicated team of cinephiles curates only the finest films and ensures every visit is perfect.</p>
            </div>
            <div class="story-image">
                <img src="img/about-1.jpg" alt="Our theater">
            </div>
        </div>
    </div>
</section>

<section class="about-features">
    <div class="container">
        <div class="section-header">
            <h3>Why Choose Us</h3>
            <p class="section-subtitle">The premium difference</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-couch"></i>
                </div>
                <h4>Luxury Seating</h4>
                <p>Plush recliners with premium leather and ample legroom for complete comfort.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-film"></i>
                </div>
                <h4>4K Laser Projection</h4>
                <p>Crystal clear images with our state-of-the-art projection systems.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-volume-up"></i>
                </div>
                <h4>Dolby Atmos</h4>
                <p>Immersive 360° sound that puts you at the center of the action.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h4>Gourmet Dining</h4>
                <p>Chef-prepared meals and craft cocktails delivered to your seat.</p>
            </div>
        </div>
    </div>
</section>

<section class="about-team">
    <div class="container">
        <div class="section-header">
            <h3>Our Team</h3>
            <p class="section-subtitle">Meet the visionaries</p>
        </div>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-image">
                    <img src="img/team-1.jpg" alt="Founder">
                </div>
                <h4>Nipesh Giri</h4>
                <p>Founder & CEO</p>
            </div>
            <div class="team-card">
                <div class="team-image">
                    <img src="img/team-2.jpg" alt="Head of Operations">
                </div>
                <h4>Sarah Williams</h4>
                <p>Head of Operations</p>
            </div>
            <div class="team-card">
                <div class="team-image">
                    <img src="img/team-3.jpg" alt="Creative Director">
                </div>
                <h4>Michael Chen</h4>
                <p>Creative Director</p>
            </div>
        </div>
    </div>
</section>

<style>
    .about-hero {
        background: linear-gradient(rgba(15, 15, 26, 0.8), rgba(15, 15, 26, 0.8)),
            url('img/about-bg.jpg') no-repeat center center;
        background-size: cover;
        height: 60vh;
        display: flex;
        align-items: center;
        padding: 0 2rem;
    }

    .about-hero h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        background: linear-gradient(90deg, #4cc9f0, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        max-width: 700px;
    }

    .about-hero p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 600px;
        line-height: 1.6;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-header h3 {
        font-size: 2rem;
        color: var(--accent);
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
    }

    .about-story {
        padding: 6rem 0;
        background: var(--primary);
    }

    .story-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .story-text p {
        line-height: 1.8;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    .story-image {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .story-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .about-features {
        padding: 6rem 0;
        background: var(--secondary);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: var(--primary);
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        transition: transform 0.3s;
    }

    .feature-card:hover {
        transform: translateY(-10px);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: rgba(76, 201, 240, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .feature-icon i {
        font-size: 2rem;
        color: var(--accent);
    }

    .feature-card h4 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .feature-card p {
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.6;
    }

    .about-team {
        padding: 6rem 0;
        background: var(--primary);
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 3rem;
    }

    .team-card {
        text-align: center;
    }

    .team-image {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1.5rem;
        border: 3px solid var(--accent);
    }

    .team-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .team-card h4 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }

    .team-card p {
        color: var(--accent);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .story-content {
            grid-template-columns: 1fr;
        }

        .story-image {
            order: -1;
        }
    }
</style>

<script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
<script>
    // Initialize ScrollReveal
    ScrollReveal().reveal('.story-content, .feature-card, .team-card', {
        delay: 200,
        distance: '30px',
        origin: 'bottom',
        interval: 100,
        easing: 'ease-in-out'
    });
</script>

<?php
renderFooter();
?>