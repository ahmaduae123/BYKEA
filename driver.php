<?php
session_start();
require 'db.php';

if (!isset($_SESSION['driver_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $type = $_POST['type'];

    try {
        if ($type === 'ride') {
            $stmt = $pdo->prepare("UPDATE rides SET driver_id = ?, status = 'accepted' WHERE id = ? AND status = 'pending'");
            $stmt->execute([$_SESSION['driver_id'], $request_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE parcels SET driver_id = ?, status = 'accepted' WHERE id = ? AND status = 'pending'");
            $stmt->execute([$_SESSION['driver_id'], $request_id]);
        }
        header("Location: track.php?type=$type&id=$request_id");
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$rides = $pdo->query("SELECT * FROM rides WHERE status = 'pending'")->fetchAll();
$parcels = $pdo->query("SELECT * FROM parcels WHERE status = 'pending'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Portal - Bykea Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Driver Portal</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <h3>Available Rides</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Pickup</th>
                <th>Dropoff</th>
                <th>Distance (km)</th>
                <th>Price ($)</th>
                <th>Action</th>
            </tr>
            <?php foreach ($rides as $ride): ?>
            <tr>
                <td><?php echo $ride['id']; ?></td>
                <td><?php echo $ride['pickup_location']; ?></td>
                <td><?php echo $ride['dropoff_location']; ?></td>
                <td><?php echo $ride['distance']; ?></td>
                <td><?php echo $ride['price']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?php echo $ride['id']; ?>">
                        <input type="hidden" name="type" value="ride">
                        <button type="submit">Accept</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>Available Parcels</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Pickup</th>
                <th>Dropoff</th>
                <th>Distance (km)</th>
                <th>Price ($)</th>
                <th>Action</th>
            </tr>
            <?php foreach ($parcels as $parcel): ?>
            <tr>
                <td><?php echo $parcel['id']; ?></td>
                <td><?php echo $parcel['pickup_location']; ?></td>
                <td><?php echo $parcel['dropoff_location']; ?></td>
                <td><?php echo $parcel['distance']; ?></td>
                <td><?php echo $parcel['price']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="request_id" value="<?php echo $parcel['id']; ?>">
                        <input type="hidden" name="type" value="parcel">
                        <button type="submit">Accept</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="#" onclick="navigate('index.php')">Back to Home</a></p>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
