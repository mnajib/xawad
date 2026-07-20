<?php
// public/process-book.php
require_once __DIR__ . '/../db.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Retrieve and trim form input
    $customer_name    = trim($_POST['customer_name'] ?? '');
    $car_plate        = strtoupper(trim($_POST['car_plate'] ?? ''));
    $service_type     = trim($_POST['service_type'] ?? '');
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $status           = 'Pending'; // Default initial status

    // 2. Simple Validation
    if (empty($customer_name) || empty($car_plate) || empty($service_type) || empty($appointment_date)) {
        die("Error: All fields are required. <a href='book.php'>Go back</a>");
    }

    // 3. Prepare SQL with Parameterized Queries (Security Best Practice)
    $sql = "INSERT INTO appointments (customer_name, car_plate, service_type, appointment_date, status) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters: 'sssss' means 5 string parameters
        $stmt->bind_param("sssss", $customer_name, $car_plate, $service_type, $appointment_date, $status);

        if ($stmt->execute()) {
            // Success: Redirect back to the index page
            header("Location: index.php?status=success");
            exit;
        } else {
            echo "Execution Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Preparation Error: " . $conn->error;
    }

    $conn->close();
} else {
    // If accessed directly via GET, redirect to book page
    header("Location: book.php");
    exit;
}
