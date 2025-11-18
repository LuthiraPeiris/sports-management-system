<?php
session_start();
include '../Dashboard/db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'coach'){
    header("Location: ../Dashboard/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ---------------- Schedule CRUD ---------------- */
if(isset($_POST['add_schedule'])){
    $title = $_POST['title'];
    $date = $_POST['schedule_date'];
    $time = $_POST['schedule_time'];
    $description = $_POST['description'];

    $insert = $conn->prepare("
        INSERT INTO schedules (user_id, title, schedule_date, schedule_time, description) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert->bind_param("issss", $user_id, $title, $date, $time, $description);
    $insert->execute();

    header("Location: Coach_Dashboard.php");
    exit();
}

if(isset($_GET['delete'])){
    $schedule_id = $_GET['delete'];
    $delete = $conn->prepare("DELETE FROM schedules WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $schedule_id, $user_id);
    $delete->execute();

    header("Location: Coach_Dashboard.php");
    exit();
}

/* ---------------- Fetch Logged Coach User ---------------- */
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

/* ---------------- Fetch Coach's Sport ---------------- */
$coachData = $conn->prepare("
    SELECT sports.name AS sport_name, coach.sport_id 
    FROM coach
    JOIN sports ON coach.sport_id = sports.sport_id
    WHERE coach.user_id = ?
");
$coachData->bind_param("i", $user_id);
$coachData->execute();
$coachDataResult = $coachData->get_result()->fetch_assoc();

$sportID = $coachDataResult['sport_id'];
$sportName = $coachDataResult['sport_name'];

/* ---------------- Fetch Students Registered in This Sport ---------------- */
/* Combines:
   1. Initial registration → student.sport_id
   2. Later registrations → student_sport_registration
*/

$students = $conn->prepare("
    (
        SELECT 
            u.name, 
            u.nic, 
            s.student_id
        FROM student s
        JOIN users u ON s.user_id = u.id
        WHERE s.sport_id = ?
    )
    UNION
    (
        SELECT 
            u.name, 
            u.nic,
            st.student_id
        FROM student_sport_registration ssr
        JOIN users u ON ssr.user_id = u.id
        JOIN student st ON st.user_id = u.id
        WHERE ssr.sport_id = ?
    )
");
$students->bind_param("ii", $sportID, $sportID);
$students->execute();
$studentsResult = $students->get_result();

/* ---------------- Fetch Coach's Schedule ---------------- */
$scheduleQuery = $conn->prepare("
    SELECT * 
    FROM schedules 
    WHERE user_id = ? 
    ORDER BY schedule_date ASC
");
$scheduleQuery->bind_param("i", $user_id);
$scheduleQuery->execute();
$schedules = $scheduleQuery->get_result();

$scheduleCount = $schedules->num_rows;
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard - Sports Club</title>
    <link rel="icon" type="image/x-icon" href="../images/Favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color: #d9d9d9;}

        /* Header */
        .top-header {background-color:  #b3e5fc;box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
        .logo-icon {width: 40px;height: 40px;background: linear-gradient(135deg, #ff6b9d 0%, #ffa07a 100%);border-radius: 50%;display: flex;align-items: center;justify-content: center;color: white;font-weight: bold;}

        /* Navigation */
        .navbar-custom {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);}
        .navbar-custom .nav-link {color: rgba(255, 255, 255, 0.85);font-weight: 500;transition: all 0.3s;}
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {color: white;background-color: rgba(255, 255, 255, 0.15);border-radius: 5px;}

        /* Welcome Banner */
        .welcome-banner {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);border-radius: 15px;color: white;box-shadow: 0 4px 12px rgba(0,0,0,0.15);}

        /* Stats Cards */
        .stat-card {box-shadow: 0 3px 10px rgba(0,0,0,0.12);}
        .stat-icon {width: 55px;height: 55px;border-radius: 50%;display: flex;align-items: center;justify-content: center;font-size: 26px;background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);color: white;}

        /* Schedule Section */
        .schedule-section {box-shadow: 0 3px 10px rgba(0,0,0,0.12);}
        .add-btn {background-color: #ff3333;color: white;border: none;border-radius: 20px;font-weight: 600;transition: background-color 0.3s}
        .add-btn:hover {background-color: #e62e2e;}
        .schedule-item {background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;color: white;}
        .schedule-date .day {font-size: 26px;font-weight: 700;line-height: 1;}
        .schedule-date .month {font-size: 12px;text-transform: uppercase;font-weight: 600;}

        /* Quick Actions */
        .quick-actions {box-shadow: 0 3px 10px rgba(0,0,0,0.12);}
        .action-btn {background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;border: none;border-radius: 10px;font-weight: 600;transition: transform 0.2s;}
        .action-btn:hover {transform: translateY(-2px);}

        /* Players Section */
        .players-section {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);}
        .players-table-container {box-shadow: 0 5px 20px rgba(0,0,0,0.15);}
        .players-table thead th {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);color: white;font-weight: 600;text-align: center;}
        .players-table thead th:first-child {border-radius: 8px 0 0 8px;}
        .players-table thead th:last-child {border-radius: 0 8px 8px 0;}
        .players-table tbody tr {background-color: #f0f0f0;}
        .players-table tbody tr td:first-child {border-radius: 8px 0 0 8px;}
        .players-table tbody tr td:last-child {border-radius: 0 8px 8px 0;}

        /* Footer */
        .footer {background-color: #0000cc;color: white;}
    </style>
</head>
<body>
    <!-- Header -->
    <header class="top-header py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="logo-icon me-3">S</div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Sports Club</h6>
                        <p class="mb-0 small">Sabaragamuwa University of Sri Lanka</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 fw-semibold"><?php echo $user['name']; ?></p>
                        <small><?php echo $user['coach_id']; ?></small>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom py-3">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#">Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#"><a href="../Dashboard/logout.php" class="btn btn-danger">Logout</a></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-4">
        <!-- Welcome Banner -->
        <div class="welcome-banner text-center py-5 mb-4">
            <h2 class="display-5 fw-semibold">Welcome back Coach <?php echo $user['name']; ?>!</h2>
            <p class="fs-5">Let's make today's training count!</p>
            <p class="fs-5"><?php echo $sportName; ?></p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon mb-3">👥</div>
                    <h3 class="display-6 fw-bold"><?= ($studentsResult->num_rows > 0) ? $studentsResult->num_rows : 0 ?></h3>
                    <p class="text-muted fw-medium">Students</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon mb-3">⚽</div>
                    <h3 class="display-6 fw-bold"> <?= $scheduleCount ?></h3>
                    <p class="text-muted fw-medium">Schedules</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon mb-3">⚽</div>
                    <h3 class="display-6 fw-bold">5</h3>
                    <p class="text-muted fw-medium">Bookings</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- My Schedule -->
            <div class="col-lg-8">
                <div class="schedule-section bg-white rounded-3 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">My Schedule</h4>
                        <button class="add-btn btn px-4 py-2" data-bs-toggle="modal" data-bs-target="#addScheduleModal">Add Item</button>
                    </div>

                    <?php while($row = $schedules->fetch_assoc()): ?>
                    <div class="schedule-item p-3 mb-3 d-flex justify-content-between align-items-center bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="schedule-date text-center me-3">
                                <span class="day"><?= date("d", strtotime($row['schedule_date'])) ?></span>
                                <span class="month d-block"><?= strtoupper(date("M", strtotime($row['schedule_date']))) ?></span>
                            </div>
                            <div>
                                <div class="schedule-title fw-semibold fs-5"><?= $row['title'] ?></div>
                                <small class="text-muted"><?= date("h:i A", strtotime($row['schedule_time'])) ?></small>
                            </div>
                        </div>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                    <?php endwhile; ?>     
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="quick-actions bg-white rounded-3 p-4 h-100">
                    <h4 class="fw-bold mb-4">Quick Actions</h4>
                    <button class="action-btn btn w-100 py-3" onclick="gotoBookings()">Book Facility</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Players Section -->
<section class="players-section py-5 mt-5">
    <div class="container">
        <h3 class="text-center fw-bold mb-4">Players</h3>
        <div class="players-table-container bg-white rounded-3 p-4">
            <div class="table-responsive">
                <?php if($studentsResult->num_rows > 0): ?>
                <table class="players-table table table-borderless">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Student_ID</th>
                            <th>NIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        while($row = $studentsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nic']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="text-muted">No students registered to this sport yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

    
    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
                        <input type="date" name="schedule_date" class="form-control mb-2" required>
                        <input type="time" name="schedule_time" class="form-control mb-2">
                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_schedule" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="footer py-3 text-center">
        <div class="container">
            <p class="mb-0">© 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent Back Button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
        alert("Logout first!");
        window.history.pushState(null, "", window.location.href);
        };
        
        // Add interactivity for Quick Action buttons
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function() {
                alert(this.textContent + ' clicked!');
            });
        });

        function gotoBookings(){
            window.location.href="../booking/booking.php";
        }
    </script>
</body>
</html>
