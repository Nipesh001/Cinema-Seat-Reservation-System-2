<?php
function renderHeader()
{
    session_start();

    // Start building the header HTML
    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Cinema</title>
    <link rel="icon" type="image/png" href="./img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f0f1a;
            --secondary: #1a1a2e;
            --accent: #4cc9f0;
            --text: #f8f9fa;
            --highlight: #ffeb3b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background-color: var(--primary);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background: linear-gradient(180deg, rgba(15,15,26,0.9) 0%, rgba(15,15,26,0.7) 100%);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(76,201,240,0.2);
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo img {
            height: 40px;
        }
        
        .logo h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(90deg, #4cc9f0, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        nav ul {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        
        nav a {
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            position: relative;
            padding: 0.5rem 0;
        }
        
        nav a:hover {
            color: var(--accent);
        }
        
        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }
        
        nav a:hover::after {
            width: 100%;
        }
        
        .user-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: var(--accent);
            color: var(--primary);
        }
        
        .btn-primary:hover {
            background: #3aa8d8;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            border: 2px solid var(--accent);
            color: var(--accent);
        }
        
        .btn-outline:hover {
            background: var(--accent);
            color: var(--primary);
        }
        
        main {
            flex: 1;
        }
        
        footer {
            background: #1a1a2e;
            padding: 2rem;
            color: white;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }
        
        .footer-section {
            padding: 1rem;
        }
        
        .footer-section h2 {
            color: #4cc9f0;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .footer-section-inner-container {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        
        .footer-section-inner-container span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.8);
            transition: color 0.3s;
            cursor: pointer;
        }
        
        .footer-section-inner-container span:hover {
            color: #4cc9f0;
        }
        
        .footer-section p {
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
        }
        
        .footer-section a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: #4cc9f0;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .footer-logo img {
            height: 30px;
        }
        
        .footer-logo h2 {
            font-size: 1.2rem;
            background: linear-gradient(90deg, #4cc9f0, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .footer-section h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section a {
            color: var(--text);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: var(--accent);
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .social-links a {
            color: var(--text);
            font-size: 1.2rem;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: var(--accent);
        }
        
        .copyright {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="index.php"><img src="img/logo.png" alt="Premium Cinema Logo"></a>
                <a href="index.php"><h1>PREMIUM CINEMA</h1></a>
                
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="movies.php">Movies</a></li>
                    <li><a href="schedule.php">Schedule</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="user-actions">
HTML;

    // Add the appropriate buttons based on login status
    if (isset($_SESSION['user_id'])) {
        $html .= '<a href="logout.php" class="btn btn-outline">Logout</a>';
    } else {
        $html .= '<a href="login.php" class="btn btn-outline">Sign In</a>
                  <a href="signup.php" class="btn btn-primary">Register</a>';
    }

    $html .= '</div>
        </div>
    </header>
    <main>';

    echo $html;
}

function renderFooter()
{
    echo <<<HTML
    </main>
    <footer>
        <div class="footer-container">
    <div class="footer-section footer-section1">
        <h2><i class="fas fa-user-alt"></i> Follow Us</h2>
        <div class="footer-section-inner-container">
            <span><i class="fab fa-lg fa-facebook-square"></i> Facebook</span>
            <span><i class="fab fa-lg fa-twitter-square"></i> Twitter</span>
            <span><i class="fab fa-lg fa-instagram"></i> Instagram</span>
        </div>
    </div>
    <div class="footer-section footer-section2">
        <h2><i class="fas fa-map-marked"></i> Contact Us</h2>
        <div class="footer-section-inner-container">
            <h4>Phone Numbers</h4>
            <p><a href="tel:01011391148">+977 9876543210</a><br>
                <a href="tel:01011391148">+977 9765432108</a>
            </p>
            <h4>Address</h4>
            <p>Amrit Campus Leknath Marg, Kathmandu 44600</p>
            <h4>E-mail</h4>
            <p><a href="mailto:info@cinemareservation.com.np">info@cinemareservation.com.np</a></p>
        </div>
    </div>
    <div class="footer-section footer-section3">
        <p>© 2025 PREMIUM CINEMA. All rights reserved.</p>

    </div>
</div>

</body>
</html>
HTML;
}
