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

$sportID = $coachDataResult['sport_id'] ?? null;
$sportName = $coachDataResult['sport_name'] ?? 'No Sport Assigned';

/* ---------------- Handle Student Registration Approvals/Rejections ---------------- */
if(isset($_GET['approve'])){
    $registration_id = $_GET['approve'];
    
    if($sportID) {
        // Update registration status to Approved
        $update = $conn->prepare("UPDATE student_sport_registration SET status = 'Approved' WHERE id = ? AND sport_id = ? AND coach_id = ?");
        $update->bind_param("iii", $registration_id, $sportID, $user_id);
        $update->execute();
    }
    
    header("Location: Coach_Dashboard.php");
    exit();
}

if(isset($_GET['approve_initial'])){
    $student_user_id = $_GET['approve_initial'];
    
    if($sportID) {
        // Create new registration record for initial registration
        $insert = $conn->prepare("INSERT INTO student_sport_registration (user_id, sport_id, coach_id, status) VALUES (?, ?, ?, 'Approved')");
        $insert->bind_param("iii", $student_user_id, $sportID, $user_id);
        $insert->execute();
    }
    
    header("Location: Coach_Dashboard.php");
    exit();
}

if(isset($_GET['reject'])){
    $registration_id = $_GET['reject'];
    
    if($sportID) {
        // Delete from registration table
        $delete = $conn->prepare("DELETE FROM student_sport_registration WHERE id = ? AND sport_id = ? AND coach_id = ?");
        $delete->bind_param("iii", $registration_id, $sportID, $user_id);
        $delete->execute();
    }
    
    header("Location: Coach_Dashboard.php");
    exit();
}

if(isset($_GET['reject_initial'])){
    $student_user_id = $_GET['reject_initial'];
    
    if($sportID) {
        // Remove sport_id from user for initial registration rejection
        $update = $conn->prepare("UPDATE users SET sport_id = NULL WHERE id = ? AND sport_id = ?");
        $update->bind_param("ii", $student_user_id, $sportID);
        $update->execute();
    }
    
    header("Location: Coach_Dashboard.php");
    exit();
}

/* ---------------- Fetch Pending Student Registrations ---------------- */
$pendingStudentsResult = null;
if($sportID) {
    // Get pending registrations from BOTH systems:
    // 1. From student_sport_registration table (status = 'Pending')
    // 2. From users table (initial registration) that don't have approved registration
    $pendingStudents = $conn->prepare("
        (SELECT 
            ssr.id AS registration_id,
            u.id AS user_id,
            u.name,
            u.nic,
            u.student_id,
            'extra' AS registration_type
        FROM student_sport_registration ssr
        JOIN users u ON ssr.user_id = u.id
        WHERE ssr.sport_id = ?
        AND ssr.coach_id = ?
        AND ssr.status = 'Pending')
        
        UNION
        
        (SELECT 
            NULL AS registration_id,
            u.id AS user_id,
            u.name,
            u.nic,
            u.student_id,
            'initial' AS registration_type
        FROM users u
        WHERE u.sport_id = ? 
        AND u.role = 'student'
        AND u.id NOT IN (
            SELECT ssr.user_id 
            FROM student_sport_registration ssr 
            WHERE ssr.sport_id = ? AND ssr.status = 'Approved'
        ))
    ");
    $pendingStudents->bind_param("iiii", $sportID, $user_id, $sportID, $sportID);
    $pendingStudents->execute();
    $pendingStudentsResult = $pendingStudents->get_result();
}

/* ---------------- Fetch Approved Players ---------------- */
$approvedPlayersResult = null;
if($sportID) {
    // Get approved players from student_sport_registration table
    $approvedPlayers = $conn->prepare("
        SELECT 
            u.id AS user_id,
            u.name,
            u.nic,
            u.student_id,
            ssr.id AS registration_id
        FROM users u
        JOIN student_sport_registration ssr ON u.id = ssr.user_id
        WHERE ssr.sport_id = ? 
        AND ssr.coach_id = ?
        AND ssr.status = 'Approved'
        AND u.role = 'student'
    ");
    $approvedPlayers->bind_param("ii", $sportID, $user_id);
    $approvedPlayers->execute();
    $approvedPlayersResult = $approvedPlayers->get_result();
}

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

/* ---------------- Fetch Booking Count ---------------- */
$bookingCount = 0;
$bookingQuery = $conn->prepare("
    SELECT COUNT(*) as booking_count 
    FROM bookings 
    WHERE user_id = ?
");
$bookingQuery->bind_param("i", $user_id);
$bookingQuery->execute();
$bookingResult = $bookingQuery->get_result()->fetch_assoc();
$bookingCount = $bookingResult['booking_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Interface</title>
    <link rel="icon" href="../images/Favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color: #d9d9d9;}

        /* Header */
        .top-header { background-color:  #b3e5fc; box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
        .logo-icon { width: 40px;height: 40px;background: linear-gradient(135deg, #ff6b9d 0%, #ffa07a 100%);border-radius: 50%;display: flex;align-items: center;justify-content: center;color: white;font-weight: bold;}

        /* Navigation */
        .navbar-custom {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);}
        .navbar-custom .nav-link {color: rgba(255, 255, 255, 0.85);font-weight: 500; transition: all 0.3s;}
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {color: white;background-color: rgba(255, 255, 255, 0.1);border-radius: 5px;}

        /* Logout button */
        .logout-btn {color: white;font-weight: 500;padding: 6px 15px;}
        .logout-btn:hover {background-color: #fd0000ff;}

        .bg-info-bar {background-color: #a8e6ff;}
        .bg-nav-bar {background-color: #8b7bd8;}
        .bg-nav-hover:hover {background-color: #7a6bc7 !important;}
        .bg-nav-active {background-color: #6a5bb7 !important;}
        .bg-profile {background-color: #ff9999;}
        .bg-custom-blue {background-color: #7B9FDB;}
        .bg-custom-dark-blue {background-color: #0000CC;}
        .schedule-item {transition: transform 0.2s ease;}
        .schedule-item:hover {transform: translateY(-2px);}
        .registration-badge {font-size: 0.7rem;padding: 2px 6px;}

        /* Table styling enhancements */
.table-primary {
    background-color: rgba(62, 105, 145, 0.9) !important;
    color: white !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(62, 105, 145, 0.05) !important;
    transition: background-color 0.2s ease;
}

.border-light {
    border-color: rgba(0, 0, 0, 0.08) !important;
}

.badge {
    font-size: 0.75rem !important;
    font-weight: 500 !important;
    padding: 0.4rem 0.8rem !important;
    border-radius: 20px !important;
}

.btn-success {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
}

.btn-outline-danger {
    color: #dc3545 !important;
    border-color: #dc3545 !important;
}

.btn-outline-danger:hover {
    background-color: #dc3545 !important;
    color: white !important;
}

.bg-custom-blue {
    background-color: rgba(62, 105, 145, 0.05) !important;
}

.rounded {
    border-radius: 10px !important;
}


        /* Footer */
        .footer {background-color: rgb(30, 58, 85);color: white;}

    </style>
</head>

<body class="bg-secondary bg-opacity-25">

    <!-- Header Section -->
    <div class="py-3 px-3 px-md-4" style="background-color: rgba(62, 105, 145, 0.95); backdrop-filter: blur(8px);">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 shadow-sm"
                style="width: 60px; height: 60px; border: 2px solid rgba(255, 255, 255, 0.25);">
                <img src="../images/Favicon.png" alt="Sports Club Logo" class="w-100 h-100 object-fit-cover p-1">
            </div>
            <div class="d-none d-md-block">
                <div class="fw-semibold text-white" style="font-size: 0.95rem;">Sports Club</div>
                <div class="text-white opacity-90" style="font-size: 0.75rem;">
                    Sabaragamuwa University Of Sri Lanka
                </div>
            </div>
        </div>

        <div class="d-none d-md-flex align-items-center gap-3">
            <div class="bg-white/20 border border-white/30 rounded-circle d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <div class="fs-5 text-white">👤</div>
            </div>
            <div class="text-end">
                <p class="fw-semibold mb-0 text-white opacity-95" style="font-size: 0.9rem;">
                    Coach <?php echo htmlspecialchars($user['name']); ?>
                </p>
                <p class="mb-0 text-white opacity-85" style="font-size: 0.8rem;">
                    Sport: <?php echo htmlspecialchars($sportName); ?>
                </p>
            </div>
        </div>

        <!-- Mobile nav button -->
        <div class="d-md-none">
            <button class="btn btn-link text-white fs-3 p-0 text-decoration-none" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                ☰
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 w-100 m-0" 
                style="background-color: rgba(40, 70, 100, 0.98); backdrop-filter: blur(10px);">
                <li class="px-3 py-2 border-bottom border-white border-opacity-25">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-white/20 border border-white/30 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <div class="fs-6 text-white">👤</div>
                        </div>
                        <div>
                            <div class="fw-semibold text-white small">Coach <?php echo htmlspecialchars($user['name']); ?></div>
                            <div class="text-white small opacity-85">Sport: <?php echo htmlspecialchars($sportName); ?></div>
                        </div>
                    </div>
                </li>
                <li><a class="dropdown-item text-white border-bottom border-white border-opacity-10 py-3"
                        href="#schedule" 
                        style="background-color: transparent; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)';"
                        onmouseout="this.style.backgroundColor='transparent';">Schedules</a></li>
                <li><a class="dropdown-item text-white border-bottom border-white border-opacity-10 py-3"
                        href="#players" 
                        style="background-color: transparent; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)';"
                        onmouseout="this.style.backgroundColor='transparent';">Players</a></li>
                <li><a class="dropdown-item text-white border-bottom border-white border-opacity-10 py-3"
                        href="#requests" 
                        style="background-color: transparent; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)';"
                        onmouseout="this.style.backgroundColor='transparent';">Requests</a></li>
                <li><a class="dropdown-item text-white py-3"
                        href="../Dashboard/logout.php" 
                        style="background-color: rgba(220, 53, 69, 0.2); transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.3)';"
                        onmouseout="this.style.backgroundColor='rgba(220, 53, 69, 0.2)';">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg py-3" style="background: rgba(40, 70, 100, 0.95); backdrop-filter: blur(8px);">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Center Nav Items -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link px-3 text-white opacity-85" 
                       href="../Homepage.php#home"
                       style="transition: all 0.2s;"
                       onmouseover="this.style.opacity='1'; this.style.backgroundColor='rgba(255,255,255,0.1)';"
                       onmouseout="this.style.opacity='0.85'; this.style.backgroundColor='transparent';">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 text-white opacity-85" 
                       href="#schedules"
                       style="transition: all 0.2s;"
                       onmouseover="this.style.opacity='1'; this.style.backgroundColor='rgba(255,255,255,0.1)';"
                       onmouseout="this.style.opacity='0.85'; this.style.backgroundColor='transparent';">Schedules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 text-white opacity-85" 
                       href="#players"
                       style="transition: all 0.2s;"
                       onmouseover="this.style.opacity='1'; this.style.backgroundColor='rgba(255,255,255,0.1)';"
                       onmouseout="this.style.opacity='0.85'; this.style.backgroundColor='transparent';">Players</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 text-white opacity-85" 
                       href="#requests"
                       style="transition: all 0.2s;"
                       onmouseover="this.style.opacity='1'; this.style.backgroundColor='rgba(255,255,255,0.1)';"
                       onmouseout="this.style.opacity='0.85'; this.style.backgroundColor='transparent';">Requests</a>
                </li>
            </ul>
            
            <!-- Logout Button Right-Aligned -->
            <a href="../Dashboard/logout.php" 
               class="btn ms-lg-3" 
               style="background-color: rgba(220, 53, 69, 0.2); 
                      color: white; 
                      border: 1px solid rgba(255, 255, 255, 0.3);
                      transition: all 0.2s;"
               onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.3)'; this.style.borderColor='rgba(255, 255, 255, 0.4)';"
               onmouseout="this.style.backgroundColor='rgba(220, 53, 69, 0.2)'; this.style.borderColor='rgba(255, 255, 255, 0.3)';">Logout</a>
        </div>
    </div>
</nav>

    <div class="container py-4">
        <!-- Welcome Card -->
        <div class="card bg-primary text-white text-center shadow mb-4 rounded-4 border-0">
            <div class="card-body py-4">
                <h1 class="h2 mb-2">Welcome back Coach <?php echo htmlspecialchars($user['name']); ?>!</h1>
                <p class="mb-1">Let's make today's training count!</p>
                <p class="mb-0 fw-bold" style="font-size: 27px;"><?php echo htmlspecialchars($sportName); ?></p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4 justify-content-center">
            <div class="col-6 col-sm-4 col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body py-3">
                        <div class="d-none d-md-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width: 45px; height: 45px; font-size: 20px;">
                                    👥
                                </div>
                                <p class="text-muted mb-0 small">Students</p>
                            </div>
                            <h2 class="display-6 fw-bold mb-0"><?= ($approvedPlayersResult && $approvedPlayersResult->num_rows > 0) ? $approvedPlayersResult->num_rows : 0 ?></h2>
                        </div>
                        <div class="d-md-none text-center">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 50px; height: 50px; font-size: 24px;">
                                👥
                            </div>
                            <h2 class="display-6 fw-bold mb-1"><?= ($approvedPlayersResult && $approvedPlayersResult->num_rows > 0) ? $approvedPlayersResult->num_rows : 0 ?></h2>
                            <p class="text-muted mb-0 small">Students</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body py-3">
                        <div class="d-none d-md-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width: 45px; height: 45px; font-size: 20px;">
                                    📅
                                </div>
                                <p class="text-muted mb-0 small" id="schedules">Schedules</p>
                            </div>
                            <h2 class="display-6 fw-bold mb-0"><?= $scheduleCount ?></h2>
                        </div>
                        <div class="d-md-none text-center">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 50px; height: 50px; font-size: 24px;">
                                📅
                            </div>
                            <h2 class="display-6 fw-bold mb-1"><?= $scheduleCount ?></h2>
                            <p class="text-muted mb-0 small">Schedules</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-sm-4 col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body py-3">
                        <div class="d-none d-md-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width: 45px; height: 45px; font-size: 20px;">
                                    ⚽
                                </div>
                                <p class="text-muted mb-0 small">Bookings</p>
                            </div>
                            <h2 class="display-6 fw-bold mb-0"><?= $bookingCount ?></h2>
                        </div>
                        <div class="d-md-none text-center">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 50px; height: 50px; font-size: 24px;">
                                ⚽
                            </div>
                            <h2 class="display-6 fw-bold mb-1"><?= $bookingCount ?></h2>
                            <p class="text-muted mb-0 small">Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule and Quick Actions -->
        <div class="row g-3" id="schedule">
            <!-- My Schedule -->
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h4 fw-bold mb-0">My Schedule</h2>
                            <button class="btn btn-danger btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                                Add Items
                            </button>
                        </div>

                        <!-- Schedule List -->
                        <div class="vstack gap-2">
                            <?php if($schedules->num_rows > 0): ?>
                                <?php while($row = $schedules->fetch_assoc()): ?>
                                    <div class="bg-primary text-white rounded-3 p-3 schedule-item">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <!-- Date + Title -->
                                            <div class="d-flex align-items-center">
                                                <div class="bg-white bg-opacity-25 rounded text-center px-3 py-2 me-3">
                                                    <div class="fw-bold fs-4">
                                                        <?= date("d", strtotime($row['schedule_date'])) ?>
                                                    </div>
                                                    <div class="small">
                                                        <?= strtoupper(date("M", strtotime($row['schedule_date']))) ?>
                                                    </div>
                                                </div>

                                                <!-- Title for Mobile -->
                                                <div class="d-md-none fw-semibold fs-5">
                                                    <?= htmlspecialchars($row['title']) ?>
                                                    <div class="small text-white-50">
                                                        <?= date("h:i A", strtotime($row['schedule_time'])) ?>
                                                    </div>
                                                    <?php if(!empty($row['description'])): ?>
                                                        <div class="small text-white-75 mt-1">
                                                            <?= htmlspecialchars($row['description']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Title for Desktop -->
                                            <div class="d-none d-md-block fw-semibold fs-5">
                                                <?= htmlspecialchars($row['title']) ?>
                                                <div class="small text-white-50">
                                                    <?= date("h:i A", strtotime($row['schedule_time'])) ?>
                                                </div>
                                                <?php if(!empty($row['description'])): ?>
                                                    <div class="small text-white-75 mt-1">
                                                        <?= htmlspecialchars($row['description']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Delete Button -->
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm ms-3" 
                                               onclick="return confirm('Are you sure you want to delete this schedule?')">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <p class="text-muted">No schedules found. Add your first schedule!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h4 fw-bold mb-4">Quick Actions</h2>
                        <button class="btn btn-primary w-100 py-3 fs-5 fw-semibold mb-3" onclick="gotoBookings()">Book Facility</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Pending Registrations Section -->
<div class="container-fluid p-0 mt-5" id="requests">
    <div class="bg-custom-blue p-4" style="min-height: 400px;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <!-- Header -->
                <div class="bg-primary text-white text-center fw-bold py-3 mb-4 rounded-top shadow-sm">
                    <h5 class="mb-0">Pending Student Registrations - <?php echo htmlspecialchars($sportName); ?></h5>
                </div>

                <div class="bg-white rounded shadow-sm border-0 overflow-hidden">
                    <?php if(!$sportID): ?>
                        <div class="alert alert-warning text-center m-3">
                            No sport assigned to you. Please contact administrator.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle" style="border-collapse: separate; border-spacing: 0;">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="border-0 py-3 text-start ps-4" style="border-top-left-radius: 8px;">Name</th>
                                        <th class="border-0 py-3">NIC</th>
                                        <th class="border-0 py-3">Student ID</th>
                                        <th class="border-0 py-3">Type</th>
                                        <th class="border-0 py-3" style="border-top-right-radius: 8px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($pendingStudentsResult && $pendingStudentsResult->num_rows > 0): ?>
                                        <?php while($row = $pendingStudentsResult->fetch_assoc()): ?>
                                        <tr class="border-bottom border-light">
                                            <td class="py-3 ps-4 fw-medium"><?= htmlspecialchars($row['name']) ?></td>
                                            <td class="py-3"><?= htmlspecialchars($row['nic']) ?></td>
                                            <td class="py-3"><?= htmlspecialchars($row['student_id'] ?? 'N/A') ?></td>
                                            <td class="py-3">
                                                <?php if($row['registration_type'] == 'extra'): ?>
                                                    <span class="badge bg-warning text-dark px-3 py-1 registration-badge">Extra</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info text-white px-3 py-1 registration-badge">Initial</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                    <?php if($row['registration_type'] == 'extra'): ?>
                                                        <a href="Coach_Dashboard.php?approve=<?= $row['registration_id'] ?>" 
                                                           class="btn btn-success btn-sm px-3"
                                                           onclick="return confirm('Approve <?= htmlspecialchars($row['name']) ?> for <?= htmlspecialchars($sportName) ?>?')">
                                                           Approve
                                                        </a>
                                                        <a href="Coach_Dashboard.php?reject=<?= $row['registration_id'] ?>" 
                                                           class="btn btn-outline-danger btn-sm px-3"
                                                           onclick="return confirm('Reject <?= htmlspecialchars($row['name']) ?> from <?= htmlspecialchars($sportName) ?>?')">
                                                           Reject
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="Coach_Dashboard.php?approve_initial=<?= $row['user_id'] ?>" 
                                                           class="btn btn-success btn-sm px-3"
                                                           onclick="return confirm('Approve <?= htmlspecialchars($row['name']) ?> for <?= htmlspecialchars($sportName) ?>?')">
                                                           Approve
                                                        </a>
                                                        <a href="Coach_Dashboard.php?reject_initial=<?= $row['user_id'] ?>" 
                                                           class="btn btn-outline-danger btn-sm px-3"
                                                           onclick="return confirm('Reject <?= htmlspecialchars($row['name']) ?> from <?= htmlspecialchars($sportName) ?>?')">
                                                           Reject
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-muted py-5 text-center">
                                                <i class="bi bi-people fs-4 d-block mb-2"></i>
                                                No pending registrations found.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Players Section -->
<div class="container-fluid p-0 mt-5" id="players">
    <div class="bg-custom-blue p-4" style="min-height: 400px;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <!-- Header -->
                <div class="bg-primary text-white text-center fw-bold py-3 mb-4 rounded-top shadow-sm">
                    <h5 class="mb-0">Approved Players - <?php echo htmlspecialchars($sportName); ?></h5>
                </div>

                <div class="bg-white rounded shadow-sm border-0 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle" style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr class="table-primary">
                                    <th class="border-0 py-3 text-start ps-4" style="border-top-left-radius: 8px;">Name</th>
                                    <th class="border-0 py-3">Student ID</th>
                                    <th class="border-0 py-3">NIC</th>
                                    <th class="border-0 py-3" style="border-top-right-radius: 8px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($approvedPlayersResult && $approvedPlayersResult->num_rows > 0): ?>
                                    <?php while($row = $approvedPlayersResult->fetch_assoc()): ?>
                                    <tr class="border-bottom border-light">
                                        <td class="py-3 ps-4 fw-medium"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="py-3"><?= htmlspecialchars($row['student_id']) ?></td>
                                        <td class="py-3"><?= htmlspecialchars($row['nic']) ?></td>
                                        <td class="py-3">
                                            <div class="d-flex justify-content-center">
                                                <a href="Coach_Dashboard.php?reject=<?= $row['registration_id'] ?>" 
                                                   class="btn btn-outline-danger btn-sm px-3"
                                                   onclick="return confirm('Remove <?= htmlspecialchars($row['name']) ?> from <?= htmlspecialchars($sportName) ?>?')">
                                                   Remove
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-muted py-5 text-center">
                                            <i class="bi bi-person-check fs-4 d-block mb-2"></i>
                                            No approved players yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="footer py-3 text-center">
        <div class="container">
            <p class="mb-0">© 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.</p>
        </div>
    </footer>

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
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter schedule title" required>
                        </div>
                        <div class="mb-3">
                            <label for="schedule_date" class="form-label">Date</label>
                            <input type="date" name="schedule_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="schedule_time" class="form-label">Time</label>
                            <input type="time" name="schedule_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" placeholder="Enter schedule description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_schedule" class="btn btn-primary">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>  
        function gotoBookings(){
            window.location.href="../booking/booking.php";
        }
        
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        // Add confirmation for delete actions
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('a[href*="delete"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if(!confirm('Are you sure you want to delete this schedule?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>