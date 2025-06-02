<?php
session_start();
require 'db.php';

// Check if user is logged in and is a user (not a driver)
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Verify user_id exists in users table
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        session_destroy(); // Clear invalid session
        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $distance = floatval($_POST['distance']);
    $price = $distance * 10; // Example: $10 per km

    try {
        $stmt = $pdo->prepare("INSERT INTO rides (user_id, pickup_location, dropoff_location, distance, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $pickup, $dropoff, $distance, $price]);
        header("Location: track.php?type=ride&id=" . $pdo->lastInsertId());
        exit;
    } catch (PDOException $e) {
        $error = "Error booking ride: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ride - Bykea Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        @media (max-width: 480px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book a Ride</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="pickup" placeholder="Pickup Location" required>
            <input type="text" name="dropoff" placeholder="Dropoff Location" required>
            <input type="number" name="distance" placeholder="Distance (km)" step="0.1" required>
            <button type="submit">Book Ride</button>
        </form>
        <p><a href="#" onclick="navigate('index.php')">Back to Home</a></p>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
