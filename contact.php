<?php
require_once 'includes/layout.php';
renderHeader();

// Initialize variables
$fName = $lName = $eMail = $feedback = '';
$errors = [];
$success = false;

?>

<section class="contact-hero">
    <div class="hero-content">
        <h2>Contact Us</h2>
        <p>Get in touch with our premium cinema team</p>
    </div>
</section>

<section class="contact-container">
    <div class="contact-grid">
        <div class="contact-info">
            <h3>Our Information</h3>
            <div class="info-card">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>Location</h4>
                    <p>Ascol Campus, Lainchaur<br>Kathmandu, Nepal</p>
                </div>
            </div>
            <div class="info-card">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <h4>Phone</h4>
                    <a href="tel:+9779765432108">+977 9765432108</a>
                </div>
            </div>
            <div class="info-card">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>Email</h4>
                    <p><a href="mailto:info@premiumcinema.com">info@cinemareservation.com.np</a></p>
                </div>
            </div>
            <div class="info-card">
                <i class="fas fa-clock"></i>
                <div>
                    <h4>Hours</h4>
                    <p>Monday-Friday: 10AM - 11PM<br>
                        Saturday-Sunday: 9AM - Midnight</p>
                </div>
            </div>
        </div>


        <div class="contact-form">
            <h3>Send Us a Message</h3>
            <form id="contactForm" method="POST">
                <div class="form-group">
                    <input type="text" id="fName" name="fName" class="form-control" placeholder="First Name" value="<?= htmlspecialchars($fName) ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" id="lName" name="lName" class="form-control" placeholder=" Last Name"
                        value="<?= htmlspecialchars($lName) ?>">
                </div>
                <div class="form-group">
                    <input type="email" id="eMail" name="eMail" class="form-control" placeholder="Email"
                        value="<?= htmlspecialchars($eMail) ?>" required>
                </div>
                <div class="form-group">
                    <textarea id="feedback" name="feedback" class="form-control" placeholder="Message" required><?= htmlspecialchars($feedback) ?></textarea>
                </div>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
                <div id="formResponse" class="mt-3"></div>
            </form>
        </div>
    </div>
</section>

<section class="map-container">
    <iframe src="https://maps.google.com/maps?q=Ascol+campus+lainchaur+kathmandu+nepal&t=&z=16&ie=UTF8&iwloc=&output=embed"
        width="100%"
        height="450"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
    </iframe>
</section>

<style>
    .contact-hero {
        background: linear-gradient(rgba(15, 15, 26, 0.8), rgba(15, 15, 26, 0.8)),
            url('img/contact-bg.jpg') no-repeat center center;
        background-size: cover;
        height: 50vh;
        display: flex;
        align-items: center;
        padding: 0 2rem;
    }

    .contact-hero h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        background: linear-gradient(90deg, #4cc9f0, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        max-width: 700px;
    }

    .contact-hero p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        max-width: 600px;
        line-height: 1.6;
    }

    .contact-container {
        padding: 4rem 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }

    .contact-info h3,
    .contact-form h3 {
        font-size: 1.8rem;
        margin-bottom: 2rem;
        color: var(--accent);
    }

    .info-card {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        align-items: flex-start;
    }

    .info-card i {
        font-size: 1.5rem;
        color: var(--accent);
        margin-top: 0.3rem;
    }

    .info-card h4 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .info-card p {
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.6;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        background: var(--secondary);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        color: var(--text);
        font-size: 1rem;
    }

    .form-group textarea {
        resize: vertical;
    }

    .map-container {
        width: 100%;
        height: 450px;
    }

    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js"></script>
<script>
    // Initialize ScrollReveal
    ScrollReveal().reveal('.info-card, .contact-form', {
        delay: 200,
        distance: '30px',
        origin: 'bottom',
        interval: 100,
        easing: 'ease-in-out'
    });

    // Handle form submission with AJAX
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        const responseDiv = document.getElementById('formResponse');

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        responseDiv.innerHTML = '';
        responseDiv.className = '';

        // Client-side validation
        const fName = form.fName.value.trim();
        const eMail = form.eMail.value.trim();
        const feedback = form.feedback.value.trim();

        if (!fName || !eMail || !feedback) {
            showErrorPopup('Please fill all required fields');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            return;
        }

        if (!/^\S+@\S+\.\S+$/.test(eMail)) {
            showErrorPopup('Please enter a valid email address');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            return;
        }

        if (feedback.length < 20) {
            showErrorPopup('Message should be at least 20 characters');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            return;
        }

        // Prepare form data
        const formData = new FormData(form);
        formData.append('submit', 'true');

        // Send AJAX request
        fetch('php/server.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showSuccessPopup(data.message);
                    form.reset();
                } else {
                    showErrorPopup(data.message);
                }
            })
            .catch(error => {
                showErrorPopup('An error occurred. Please try again.');
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
    });

    function showErrorPopup(message) {
        const popup = document.createElement('div');
        popup.className = 'popup-overlay active';
        popup.innerHTML = `
            <div class="popup-content">
                <i class="fas fa-exclamation-circle" style="color:#f44336"></i>
                <h3>Error</h3>
                <p>${message}</p>
                <button class="popup-close" onclick="this.parentElement.parentElement.remove()">OK</button>
            </div>
        `;
        document.body.appendChild(popup);

        // Auto-close after 5 seconds
        setTimeout(() => {
            popup.remove();
        }, 5000);
    }

    function showSuccessPopup(message) {
        const popup = document.createElement('div');
        popup.className = 'popup-overlay active';
        popup.innerHTML = `
            <div class="popup-content">
                <i class="fas fa-check-circle" style="color:#4CAF50"></i>
                <h3>Success!</h3>
                <p>${message}</p>
                <button class="popup-close" onclick="this.parentElement.parentElement.remove()">OK</button>
            </div>
        `;
        document.body.appendChild(popup);

        // Auto-close after 5 seconds
        setTimeout(() => {
            popup.remove();
        }, 5000);
    }
</script>

<style>
    /* Messages */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-success {
        background: rgba(76, 201, 240, 0.1);
        border: 1px solid #4cc9f0;
        color: #4cc9f0;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid #dc3545;
        color: #dc3545;
    }


    /* Popup Styles */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .popup-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .popup-content {
        background: rgb(15, 15, 26);
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        max-width: 400px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .popup-content i {
        color: #4cc9f0;
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .popup-content h3 {
        color: #4cc9f0;
        margin-bottom: 15px;
    }

    .popup-close {
        background: #4cc9f0;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .popup-close:hover {
        background: #3aa8d8;
    }

    .alert {
        padding: 12px;
        margin-top: 15px;
        border-radius: 4px;
        color: white;
    }

    .alert.success {
        background-color: #4CAF50;
    }

    .alert.error {
        background-color: #f44336;
    }
</style>
<?php if ($success): ?>
    <div class="popup-overlay active" id="successPopup">
        <div class="popup-content">
            <i class="fas fa-check-circle"></i>
            <h3>Thank You!</h3>
            <p>Your message has been sent successfully. We'll get back to you soon.</p>
            <button class="popup-close" onclick="closePopup()">OK</button>
        </div>
    </div>
    <script>
        function closePopup() {
            document.getElementById('successPopup').classList.remove('active');
        }
        // Auto-close after 5 seconds
        setTimeout(closePopup, 5000);
    </script>
<?php endif; ?>

<?php
renderFooter();
?>