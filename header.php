    
    <style>
/* header.css */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 0px;
    width: 100%; /* Full width of the viewport */
    box-sizing: border-box; /* Ensure padding is included in the total width */
}

.logo-section img {
    height: 50px;
    width: auto;
}

h1 {
    font-size: 24px;
    margin: 0;
    color: #003cb3; /* Moved color from inline style to CSS */
    text-align: center;
}

.send-request a {
    text-decoration: none;
    font-size: 24px;
    color: #007bff;
    transition: transform 0.3s ease-in-out;
}

.send-request a:hover {
    transform: scale(1.2);
}

.bx {
    font-size: 24px;
}

.bx-burst {
    animation: bx-burst 1s infinite;
}

@keyframes bx-burst {
    50% {
        transform: scale(1.1);
    }
}


    </style>


     <header>
        <div class="logo-section">
            <img src="KCBLLOGO.png" alt="Your Logo">
        </div>
        <h1 style="color: #003cb3;">SERVICENET</h1>
        <div class="send-request">
            <a href="sendrequest.php"></i></a>
        </div>
    </header>