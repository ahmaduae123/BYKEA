<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bykea Clone - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            background-color: #218838;
            padding: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .hero {
            text-align: center;
            padding: 50px;
            background: linear-gradient(to right, #28a745, #218838);
            color: white;
        }
        .hero h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .services {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 50px;
            flex-wrap: wrap;
        }
        .service-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
        }
        .service-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        footer {
            background-color: #28a745;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            .services {
                flex-direction: column;
                align-items: center;
            }
            .service-card {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bykea Clone</h1>
    </header>
    <nav>
        <a href="#" onclick="navigate('signup.php')">Signup</a>
        <a href="#" onclick="navigate('login.php')">Login</a>
        <a href="#" onclick="navigate('ride.php')">Book Ride</a>
        <a href="#" onclick="navigate('parcel.php')">Send Parcel</a>
        <a href="#" onclick="navigate('driver.php')">Driver Portal</a>
    </nav>
    <div class="hero">
        <h2>Welcome to Bykea Clone</h2>
        <p>Book rides or send parcels with ease!</p>
    </div>
    <div class="services">
        <div class="service-card">
            <img src="https://via.placeholder.com/300x150?text=Ride" alt="Ride">
            <h3>Ride-Hailing</h3>
            <p>Book a bike ride to your destination quickly.</p>
        </div>
        <div class="service-card">
            <img src="https://via.placeholder.com/300x150?text=Parcel" alt="Parcel">
            <h3>Parcel Delivery</h3>
            <p>Send packages anywhere with our reliable service.</p>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Bykea Clone. All rights reserved.</p>
    </footer>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
