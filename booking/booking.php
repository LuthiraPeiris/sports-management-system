<?php
// Start session to track logged-in users
session_start();

// Include database configuration file from parent directory
require_once '../config.php';

// Check if user is logged in (optional - remove if not needed)
// Uncomment the following lines if you want to restrict access to logged-in users only
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../login.php");
//     exit();
// }

// Get user details from session (if logged in)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Fetch all booked details from database
$booked_facilities = [];
try {
    // Query to get all bookings
    $stmt = $conn->prepare("
        SELECT 
            facility_type,
            booking_date,
            booking_time,
            duration,
            status,
            created_at
        FROM bookings 
        WHERE user_id = ? OR ? IS NULL
        ORDER BY booking_date DESC, booking_time DESC
    ");
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $booked_facilities[] = $row;
    }
    $stmt->close();
} catch (Exception $e) {
    // Log error but don't display to user
    error_log("Error fetching bookings: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - Sports Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font{
            font-weight: 700;  
        }

        .h1{
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .container-fluid{
            display: flex;
            justify-content: center;
            height: 80px;
            background-color: rgba(62, 105, 145, 0.95);
            align-items: center;
            margin-bottom: 20px;
        } 
    
        .card-custom {
            background-color:azure !important;
            border: 2px solid black !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 450px;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: #333 !important;
        }

        .booking-card .row {
            margin: 0;
        }

        .booking-card .card-body {
            padding: 1.5rem;
        }
        
        .p-3{
            padding: 0 !important;
        }

        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="container-fluid">
        <p class="h1 font">Sports Facility Booking</p>
    </div>

    <!-- Alert Messages (for success/error notifications) -->
    <div id="alertContainer"></div>

    <!-- Cards Section - Booking Options -->
    <section id="Cards">
        <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">

                <!-- Ground Booking Card -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <button type="button" class="btn text-decoration-none p-0 w-100" data-bs-toggle="modal" data-bs-target="#groundBookingModal">
                        <div class="card text-center card-custom">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <img src="../images/Football.webp" alt="Football" class="img-fluid mb-2">
                                <i class="fas fa-futbol fa-3x mb-3 text-dark"></i>
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
                    <button type="button" class="btn text-decoration-none p-0 w-100" data-bs-toggle="modal" data-bs-target="#gymBookingModal">
                        <div class="card text-center card-custom">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <img src="../images/weightlifting.jpeg" alt="Weightlifting" class="img-fluid mb-2">
                                <i class="fas fa-dumbbell fa-3x mb-3 text-dark"></i>
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
                    <button type="button" class="btn text-decoration-none p-0 w-100" data-bs-toggle="modal" data-bs-target="#indoorBookingModal">
                        <div class="card text-center card-custom">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <img src="../images/badminton.webp" alt="Badminton" class="img-fluid mb-2">
                                <i class="fas fa-table-tennis fa-3x mb-3 text-dark"></i>
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
    <section id="booked-details" class="py-5" style="background-color: #e0e2e4ff;">
        <div class="container">
            <h1 class="text-center mb-4 font" style="color: rgba(62, 105, 145, 0.95); font-size: 3rem;">Booked Details</h1>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead style="background-color:red; color: white;">
                        <tr>
                            <th>Facility</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <?php if (empty($booked_facilities)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No bookings found. Make your first booking above!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($booked_facilities as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['facility_type']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($booking['booking_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($booking['booking_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($booking['duration']); ?> hour(s)</td>
                                    <td>
                                        <span class="badge <?php echo $booking['status'] == 'Confirmed' ? 'bg-success' : 'bg-warning'; ?>">
                                            <?php echo htmlspecialchars($booking['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Ground Booking Modal -->
    <div class="modal fade" id="groundBookingModal" tabindex="-1" aria-labelledby="groundBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groundBookingModalLabel">Ground Booking Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="groundBookingForm" method="POST" action="process_booking.php">
                        <input type="hidden" name="facility_type" value="Ground Booking">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        
                        <div class="mb-3">
                            <label for="groundName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="groundName" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="groundEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="groundEmail" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
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
                            <input type="date" class="form-control" id="groundDate" name="booking_date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="groundTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="groundTime" name="booking_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="groundDuration" class="form-label">Duration (hours)</label>
                            <input type="number" class="form-control" id="groundDuration" name="duration" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="groundRequirements" class="form-label">Special Requirements</label>
                            <textarea class="form-control" id="groundRequirements" name="special_requirements" rows="3" placeholder="e.g., Tournament, Coaching Session, etc."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gym Booking Modal -->
    <div class="modal fade" id="gymBookingModal" tabindex="-1" aria-labelledby="gymBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gymBookingModalLabel">Gym Booking Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="gymBookingForm" method="POST" action="process_booking.php">
                        <input type="hidden" name="facility_type" value="Gym Booking">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        
                        <div class="mb-3">
                            <label for="gymName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="gymName" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="gymEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="gymEmail" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
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
                            <input type="date" class="form-control" id="gymDate" name="booking_date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="gymTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="gymTime" name="booking_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="gymDuration" class="form-label">Duration (hours)</label>
                            <input type="number" class="form-control" id="gymDuration" name="duration" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Indoor Booking Modal -->
    <div class="modal fade" id="indoorBookingModal" tabindex="-1" aria-labelledby="indoorBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="indoorBookingModalLabel">Indoor Booking Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="indoorBookingForm" method="POST" action="process_booking.php">
                        <input type="hidden" name="facility_type" value="Indoor Booking">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        
                        <div class="mb-3">
                            <label for="indoorName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="indoorName" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="indoorEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="indoorEmail" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
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
                            <input type="date" class="form-control" id="indoorDate" name="booking_date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="indoorTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="indoorTime" name="booking_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="indoorDuration" class="form-label">Duration (hours)</label>
                            <input type="number" class="form-control" id="indoorDuration" name="duration" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show alert messages
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alertDiv);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Check for success/error messages in URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            showAlert('Booking submitted successfully!', 'success');
            // Remove the parameter from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.has('error')) {
            showAlert('Error: ' + urlParams.get('error'), 'danger');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-4 text-center">
        <p>&copy; 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.</p>
    </footer>

</body>
</html>