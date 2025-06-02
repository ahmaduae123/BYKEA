<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $vehicle_type = $_POST['vehicle_type'] ?? null;

    try {
        if ($type === 'user') {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $phone]);
            $user_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO wallets (user_id, balance) VALUES (?, 0.00)");
            $stmt->execute([$user_id]);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['type'] = 'user';
            header("Location: index.php");
        } else {
            $stmt = $pdo->prepare("INSERT INTO drivers (username, email, password, phone, vehicle_type) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $phone, $vehicle_type]);
            $_SESSION['driver_id'] = $pdo->lastInsertId();
            $_SESSION['type'] = 'driver';
            header("Location: driver.php");
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Bykea Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        input, select {
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
            .signup-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <select name="type" onchange="toggleVehicleType(this)">
                <option value="user">User</option>
                <option value="driver">Driver</option>
            </select>
            <select name="vehicle_type" id="vehicle_type" style="display: none;">
                <option value="bike">Bike</option>
                <option value="car">Car</option>
            </select>
            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="#" onclick="navigate('login.php')">Login</a></p>
    </div>
    <script>
        function toggleVehicleType(select) {
            document.getElementById('vehicle_type').style.display = select.value === 'driver' ? 'block' : 'none';
        }
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
