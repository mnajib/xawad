<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Car Service Center</title>
    <!-- Local Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Car Service Center</a>
        </div>
    </nav>

    <div class="container" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Book an Appointment</h2>
            <a href="index.php" class="btn btn-outline-secondary">← Back to List</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="process-book.php" method="POST">

                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required placeholder="e.g. Ahmad Razak">
                    </div>

                    <div class="mb-3">
                        <label for="car_plate" class="form-label fw-semibold">Car Plate Number</label>
                        <input type="text" class="form-control font-monospace" id="car_plate" name="car_plate" required placeholder="e.g. WYY 8899">
                    </div>

                    <div class="mb-3">
                        <label for="service_type" class="form-label fw-semibold">Service Type</label>
                        <select class="form-select" id="service_type" name="service_type" required>
                            <option value="" selected disabled>Select a service...</option>
                            <option value="Basic Engine Oil Change">Basic Engine Oil Change</option>
                            <option value="Full Major Service">Full Major Service</option>
                            <option value="Brake Pad Replacement">Brake Pad Replacement</option>
                            <option value="Tire Alignment & Balancing">Tire Alignment & Balancing</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="appointment_date" class="form-label fw-semibold">Appointment Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Submit Booking</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Local Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
