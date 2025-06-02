<?php
session_start();
require 'db.php';

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$type = $_GET['type'];
$id = $_GET['id'];

if ($type === 'ride') {
    $stmt = $pdo->prepare("SELECT * FROM rides WHERE id = ?");
} else {
    $stmt = $pdo->prepare("SELECT * FROM parcels WHERE id = ?");
}
$stmt->execute([$id]);
$request = $stmt->fetch();

if (!$request) {
    header("Location: index.php");
    exit;
}

// Fetch driver info if assigned
$driver_name = "Not assigned";
if ($request['driver_id']) {
    $stmt = $pdo->prepare("SELECT username FROM drivers WHERE id = ?");
    $stmt->execute([$request['driver_id']]);
    $driver = $stmt->fetch();
    $driver_name = $driver ? $driver['username'] : "Unknown";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track <?php echo ucfirst($type); ?> - Bykea Clone</title>
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
        #map {
            width: 100%;
            height: 300px;
            background-color: #ccc;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #333;
        }
        .status {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 10px;
        }
        .pending-message {
            color: #ff9800;
            font-weight: bold;
        }
        .accepted-message {
            color: #28a745;
            font-weight: bold;
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
        <h2>Track <?php echo ucfirst($type); ?></h2>
        <div class="status">Status: <?php echo $request['status']; ?></div>
        <div class="info">Driver: <?php echo $driver_name; ?></div>
        <div class="info">Pickup: <?php echo $request['pickup_location']; ?></div>
        <div class="info">Dropoff: <?php echo $request['dropoff_location']; ?></div>
        <div class="info">Distance: <?php echo $request['distance']; ?> km</div>
        <div class="info">Price: $<?php echo $request['price']; ?></div>
        <div id="map">
            <?php if ($request['status'] === 'pending') { ?>
                <span class="pending-message">Waiting for a driver to accept your <?php echo $type; ?>...</span>
            <?php } else { ?>
                <span id="tracking-message">Driver is on the way... (Simulated)</span>
            <?php } ?>
        </div>
        <p><a href="#" onclick="navigate('index.php')">Back to Home</a></p>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }

        // Simulated real-time tracking for accepted rides
        const status = "<?php echo $request['status']; ?>";
        if (status !== 'pending') {
            const messages = [
                'Driver is on the way... (Simulated)',
                'Driver is approaching pickup location... (Simulated)',
                'Driver has arrived at pickup! (Simulated)',
                'En route to dropoff... (Simulated)',
                'Almost there... (Simulated)',
                'Arrived at destination! (Simulated)'
            ];
            let index = 0;
            const trackingMessage = document.getElementById('tracking-message');
            setInterval(() => {
                if (index < messages.length) {
                    trackingMessage.textContent = messages[index];
                    index++;
                }
            }, 5000);
        }

        // Auto-refresh to check status
        setInterval(() => {
            window.location.reload();
        }, 10000);
    </script>
</body>
</html>
