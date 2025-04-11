<?php
function showPopup($message, $type = 'success') {
    echo <<<HTML
    <div class="popup-overlay">
        <div class="popup popup-$type">
            <div class="popup-content">
                <svg class="popup-icon" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z" />
                </svg>
                <p>$message</p>
            </div>
        </div>
    </div>
    <style>
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        animation: fadeIn 0.3s;
    }
    .popup {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-align: center;
        max-width: 400px;
        transform: translateY(20px);
        animation: slideUp 0.4s forwards;
    }
    .popup-success {
        border-top: 4px solid #4CAF50;
    }
    .popup-icon {
        width: 50px;
        height: 50px;
        color: #4CAF50;
        margin-bottom: 1rem;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        to { transform: translateY(0); }
    }
    </style>
    <script>
    setTimeout(() => {
        document.querySelector('.popup-overlay').style.opacity = '0';
        setTimeout(() => {
            document.querySelector('.popup-overlay').remove();
        }, 300);
    }, 2000);
    </script>
HTML;
}
?>
