<?php
require_once "../config.php"; // config.php should handle session_start()

$errors = [];

// ------------------------------------
// CREATE BOOKING
// ------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_type'])) {

    $facility_type = sanitize_input($_POST['booking_type']);
    $name          = sanitize_input($_POST['name']);
    $email         = sanitize_input($_POST['email']);
    $phone         = sanitize_input($_POST['phone']);
    $booking_date  = sanitize_input($_POST['booking_date']);
    $booking_time  = sanitize_input($_POST['booking_time']);
    $duration      = intval($_POST['duration']);

    // Validate basic fields
    if (empty($name))  $errors[] = "Name is required.";
    if (!validate_email($email)) $errors[] = "Valid email is required.";
    if (empty($phone)) $errors[] = "Phone number is required.";
    if (!validate_date($booking_date)) $errors[] = "Invalid booking date.";
    if (empty($booking_time)) $errors[] = "Booking time is required.";
    if ($duration <= 0) $errors[] = "Duration must be at least 1 hour.";

    if (empty($errors)) {
        // Extract extra fields (facility-specific)
        $ignored = ['booking_type','name','email','phone','booking_date','booking_time','duration','submit_booking'];
        $extra   = [];

        foreach ($_POST as $key => $value) {
            if (!in_array($key, $ignored)) {
                $extra[$key] = sanitize_input($value);
            }
        }

        $facility_details = json_encode($extra, JSON_UNESCAPED_UNICODE);

        // Insert into DB
        $stmt = $conn->prepare("
            INSERT INTO bookings 
            (facility_type, name, email, phone, booking_date, booking_time, duration, facility_details, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed')
        ");

        $stmt->bind_param(
            "ssssssis",
            $facility_type,
            $name,
            $email,
            $phone,
            $booking_date,
            $booking_time,
            $duration,
            $facility_details
        );

        if ($stmt->execute()) {
            // Use relative path instead of absolute
            redirect("booking.php?added=1");

        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}

// ------------------------------------
// CANCEL BOOKING
// ------------------------------------
if (isset($_GET['cancel_id'])) {
    $id = intval($_GET['cancel_id']);

    $stmt = $conn->prepare("UPDATE bookings SET status='Cancelled' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Use relative path instead of absolute
    redirect("booking.php?cancelled=1");
}

// ------------------------------------
// FETCH BOOKINGS
// ------------------------------------
$bookings = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC, booking_time DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
        }

        .font {
            font-weight: 700;
        }

        /* Header Styles */
        .top-header {
            background: linear-gradient(135deg, #d4f1f9 0%, #a8e6f5 100%);
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-placeholder {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .logo-placeholder img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .header-text h1 {
            font-size: 1.5rem;
            color: #1a1a1a;
            margin-bottom: 2px;
            font-weight: 700;
        }

        .header-text p {
            font-size: 0.9rem;
            color: #555;
            margin: 0;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1a1a1a;
            font-weight: 600;
        }

        .back-btn-header {
            background: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: #3e6991;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn-header:hover {
            background: #3e6991;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            text-decoration: none;
        }

        /* Remove underline from all links */
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        /* Blue Banner */
        .container-fluid {
            display: flex;
            justify-content: center;
            height: 70px;
            background-color: rgba(62, 105, 145, 0.95);
            align-items: center;
            margin-bottom: 30px;
        }

        .h1 {
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 0;
            font-size: 2rem;
        }

        /* Card Styles */
        .card-custom {
            background-color: white !important;
            border: 2px solid #e0e0e0 !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            height: 450px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            border-color: #3e6991 !important;
        }

        .card-custom img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-wrapper {
            text-decoration: none;
            padding: 0 !important;
            width: 100%;
            background: transparent;
            border: none;
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, #3e6991 0%, #5a8db8 100%);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.4rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3e6991;
            box-shadow: 0 0 0 0.2rem rgba(62, 105, 145, 0.15);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .modal-footer {
            border-top: 2px solid #e0e0e0;
            padding: 1.5rem;
            background-color: white;
        }

        .modal-footer .btn {
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #3e6991;
            border: none;
        }

        .btn-primary:hover {
            background: #2d4d6b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-info {
            background: #17a2b8;
            border: none;
        }

        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Table Section */
        #booked-details {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f0f4f8;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Footer */
        footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 1.5rem;
            text-align: center;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .h1 {
                font-size: 1.5rem;
            }

            .card-custom {
                height: auto;
            }

            .modal-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Top Header with Logo -->
<header class="top-header">
    <div class="header-content">
        <div class="logo-section">
            <div class="logo-placeholder">
                <!-- Replace the src with your actual logo path -->
                <img src="../images/Favicon.png" alt="SUSL Logo" onerror="this.style.display='none'">
            </div>
            <div class="header-text">
                <h1>Sports Club</h1>
                <p>Sabaragamuwa University Of Sri Lanka</p>
            </div>
        </div>
        <div class="user-section">
            <a href="../Coach_Interface/coach_dashboard.php" class="back-btn-header">
                <i class="bi bi-arrow-left"></i> Back to Coach
            </a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <p class="h1 font">Sports Facility Booking</p>
</div>

<section id="Cards">
    <div class="container py-5">

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><ul>
            <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
            <?php endforeach; ?>
        </ul></div>
    <?php endif; ?>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Booking successfully added!</div>
    <?php endif; ?>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="alert alert-warning">Booking cancelled.</div>
    <?php endif; ?>

    <!-- Buttons -->
    <div class="row justify-content-center align-items-center">
        <!-- Ground Booking Card -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-wrapper" data-bs-toggle="modal" data-bs-target="#groundModal">
                <div class="card text-center card-custom">
                    <img src="../images/Football.webp" alt="Football" class="img-fluid">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h4 class="card-title text-dark"><b>Ground Booking</b></h4>
                        <p class="card-text text-muted">
                            Book outdoor sports grounds for football, cricket, and athletics
                        </p>
                    </div>
                </div>
            </button>
        </div>

        <!-- Gym Booking Card -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-wrapper" data-bs-toggle="modal" data-bs-target="#gymModal">
                <div class="card text-center card-custom">
                    <img src="../images/weightlifting.jpeg" alt="Weightlifting" class="img-fluid">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h4 class="card-title text-dark"><b>Gym Booking</b></h4>
                        <p class="card-text text-muted">
                            Access our fully-equipped gym with modern equipment
                        </p>
                    </div>
                </div>
            </button>
        </div>

        <!-- Indoor Booking Card -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-wrapper" data-bs-toggle="modal" data-bs-target="#indoorModal">
                <div class="card text-center card-custom">
                    <img src="../images/badminton.webp" alt="Badminton" class="img-fluid">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h4 class="card-title text-dark"><b>Indoor Booking</b></h4>
                        <p class="card-text text-muted">
                            Book indoor courts for badminton, basketball, and squash
                        </p>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>
</section>

    <!-- Booked Details Section -->
    <section id="booked-details" class="py-5">
        <div class="container">

            <h1 class="text-center mb-4 font" style="color: rgba(62, 105, 145, 0.95); font-size: 3rem;">Booked Details</h1>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead style="background:red; color:white;">
                        <tr>
                            <th>Facility</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($bookings->num_rows === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center">No bookings yet!</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($b = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= h($b['facility_type']) ?></td>
                                <td><?= h($b['booking_date']) ?></td>
                                <td><?= date("h:i A", strtotime($b['booking_time'])) ?></td>
                                <td><?= h($b['duration']) ?> hour(s)</td>

                                <td>
                                    <?php if ($b['status'] !== "Cancelled"): ?>
                                        <a href="booking.php?cancel_id=<?= $b['id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to cancel this booking?');">
                                           Cancel
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </section>

</div>


<!-- ---------------------------------------------------------
     GROUND BOOKING MODAL
--------------------------------------------------------- -->
<div class="modal fade" id="groundModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="POST" action="">

        <div class="modal-header">
          <h5 class="modal-title">Ground Booking</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="booking_type" value="Ground Booking">

            <div class="mb-3">
                <label for="groundName" class="form-label">Name</label>
                <input type="text" class="form-control" id="groundName" name="name" required>
            </div>

            <div class="mb-3">
                <label for="groundEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="groundEmail" name="email" required>
            </div>

            <div class="mb-3">
                <label for="groundPhone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="groundPhone" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="groundSportsType" class="form-label">Sports Type</label>
                <select class="form-control" id="groundSportsType" name="sports_type" required>
                    <option value="">Select Sport</option>
                    <option value="Cricket">Cricket</option>
                    <option value="Football">Football</option>
                    <option value="Athletics">Athletics</option>
                    <option value="Basketball">Basketball</option>
                    <option value="Volleyball">Volleyball</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="groundPlayers" class="form-label">Number of Players/Participants</label>
                <input type="number" class="form-control" id="groundPlayers" name="num_players" min="1" required>
            </div>

            <div class="mb-3">
                <label for="groundDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="groundDate" name="booking_date" required>
            </div>

            <div class="mb-3">
                <label for="groundTime" class="form-label">Time</label>
                <input type="time" class="form-control" id="groundTime" name="booking_time" required>
            </div>

            <div class="mb-3">
                <label for="groundDuration" class="form-label">Duration (hours)</label>
                <input type="number" class="form-control" id="groundDuration" name="duration" min="1" value="1" required>
            </div>

            <div class="mb-3">
                <label for="groundRequirements" class="form-label">Special Requirements</label>
                <textarea class="form-control" id="groundRequirements" name="special_requirements" rows="3" placeholder="e.g., Tournament, Coaching Session, etc."></textarea>
            </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="submit_booking">Submit Booking</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- ---------------------------------------------------------
     GYM BOOKING MODAL
--------------------------------------------------------- -->
<div class="modal fade" id="gymModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="POST" action="">

        <div class="modal-header">
          <h5 class="modal-title">Gym Booking</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="booking_type" value="Gym Booking">

            <div class="mb-3">
                <label for="gymName" class="form-label">Name</label>
                <input type="text" class="form-control" id="gymName" name="name" required>
            </div>

            <div class="mb-3">
                <label for="gymEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="gymEmail" name="email" required>
            </div>

            <div class="mb-3">
                <label for="gymPhone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="gymPhone" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="gymSessionType" class="form-label">Session Type</label>
                <select class="form-control" id="gymSessionType" name="session_type" required>
                    <option value="">Select Session Type</option>
                    <option value="General Workout">General Workout</option>
                    <option value="Personal Training">Personal Training</option>
                    <option value="Group Class">Group Class</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="gymPeople" class="form-label">Number of People (for group bookings)</label>
                <input type="number" class="form-control" id="gymPeople" name="num_people" min="1">
            </div>

            <div class="mb-3">
                <label for="gymPreferredArea" class="form-label">Preferred Area</label>
                <select class="form-control" id="gymPreferredArea" name="preferred_area" required>
                    <option value="">Select Area</option>
                    <option value="Cardio Zone">Cardio Zone</option>
                    <option value="Weight Training">Weight Training</option>
                    <option value="Functional Training">Functional Training</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="gymEquipment" class="form-label">Equipment Preference</label>
                <textarea class="form-control" id="gymEquipment" name="equipment_preference" rows="3" placeholder="Specific machines or areas"></textarea>
            </div>

            <div class="mb-3">
                <label for="gymDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="gymDate" name="booking_date" required>
            </div>

            <div class="mb-3">
                <label for="gymTime" class="form-label">Time</label>
                <input type="time" class="form-control" id="gymTime" name="booking_time" required>
            </div>

            <div class="mb-3">
                <label for="gymDuration" class="form-label">Duration (hours)</label>
                <input type="number" class="form-control" id="gymDuration" name="duration" min="1" value="1" required>
            </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary" name="submit_booking">Submit Booking</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- ---------------------------------------------------------
     INDOOR BOOKING MODAL
--------------------------------------------------------- -->
<div class="modal fade" id="indoorModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="POST" action="">

        <div class="modal-header">
          <h5 class="modal-title">Indoor Booking</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="booking_type" value="Indoor Booking">

            <div class="mb-3">
                <label for="indoorName" class="form-label">Name</label>
                <input type="text" class="form-control" id="indoorName" name="name" required>
            </div>

            <div class="mb-3">
                <label for="indoorEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="indoorEmail" name="email" required>
            </div>

            <div class="mb-3">
                <label for="indoorPhone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="indoorPhone" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="indoorSportType" class="form-label">Sport Type</label>
                <select class="form-control" id="indoorSportType" name="sport_type" required>
                    <option value="">Select Sport</option>
                    <option value="Badminton">Badminton</option>
                    <option value="Basketball">Basketball</option>
                    <option value="Table Tennis">Table Tennis</option>
                    <option value="Squash">Squash</option>
                    <option value="Volleyball">Volleyball</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="indoorCourtSelection" class="form-label">Court Selection</label>
                <select class="form-control" id="indoorCourtSelection" name="court_selection" required>
                    <option value="">Select Court</option>
                    <option value="Court 1">Court 1</option>
                    <option value="Court 2">Court 2</option>
                    <option value="Court 3">Court 3</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="indoorNumPlayers" class="form-label">Number of Players</label>
                <input type="number" class="form-control" id="indoorNumPlayers" name="num_players" min="1" required>
            </div>

            <div class="mb-3">
                <label for="indoorCourtType" class="form-label">Court Type</label>
                <select class="form-control" id="indoorCourtType" name="court_type" required>
                    <option value="">Select Court Type</option>
                    <option value="Singles">Singles</option>
                    <option value="Doubles">Doubles</option>
                    <option value="Full Court">Full Court</option>
                    <option value="Half Court">Half Court</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="indoorEquipmentRental" class="form-label">Equipment Rental</label>
                <textarea class="form-control" id="indoorEquipmentRental" name="equipment_rental" rows="3" placeholder="Rackets, balls, nets, etc."></textarea>
            </div>

            <div class="mb-3">
                <label for="indoorBookingPurpose" class="form-label">Booking Purpose</label>
                <select class="form-control" id="indoorBookingPurpose" name="booking_purpose" required>
                    <option value="">Select Purpose</option>
                    <option value="Casual play">Casual play</option>
                    <option value="Training">Training</option>
                    <option value="Tournament">Tournament</option>
                    <option value="Match">Match</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="indoorDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="indoorDate" name="booking_date" required>
            </div>

            <div class="mb-3">
                <label for="indoorTime" class="form-label">Time</label>
                <input type="time" class="form-control" id="indoorTime" name="booking_time" required>
            </div>

            <div class="mb-3">
                <label for="indoorDuration" class="form-label">Duration (hours)</label>
                <input type="number" class="form-control" id="indoorDuration" name="duration" min="1" value="1" required>
            </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-info text-white" name="submit_booking">Submit Booking</button>
        </div>

      </form>
    </div>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.</p>
</footer>

</body>
</html>