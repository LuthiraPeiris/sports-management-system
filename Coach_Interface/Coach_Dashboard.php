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

    $insert = $conn->prepare("INSERT INTO schedules (user_id, title, schedule_date, schedule_time, description) VALUES (?, ?, ?, ?, ?)");
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

/* ---------------- Fetch Students Registered For This Sport ---------------- */
$students = $conn->prepare("
    SELECT users.name, users.nic, student.student_id
    FROM student_sport_registration ssr
    JOIN users ON ssr.user_id = users.id
    JOIN student ON student.user_id = users.id
    WHERE ssr.sport_id = ?
");
$students->bind_param("i", $sportID);
$students->execute();
$studentsResult = $students->get_result();

/* ---------------- Fetch Coach's Schedule ---------------- */
$scheduleQuery = $conn->prepare("SELECT * FROM schedules WHERE user_id = ? ORDER BY schedule_date ASC");
$scheduleQuery->bind_param("i", $user_id);
$scheduleQuery->execute();
$schedules = $scheduleQuery->get_result();

// Schedule number counting
$scheduleCount = $schedules->num_rows;
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
        .bg-info-bar {background-color: #a8e6ff;}
        .bg-nav-bar {background-color: #8b7bd8;}
        .bg-nav-hover:hover {background-color: #7a6bc7 !important;}
        .bg-nav-active {background-color: #6a5bb7 !important;}
        .bg-profile {background-color: #ff9999;}
        .bg-custom-blue {background-color: #7B9FDB;}
        .bg-custom-dark-blue {background-color: #0000CC;}
    </style>
</head>

<body class="bg-secondary bg-opacity-25">

    <!-- Header Section -->
    <div class="bg-info-bar py-3 px-3 px-md-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0"
                    style="width: 60px; height: 60px;">
                    <img src="../images/Favicon.png" alt="Sports Club Logo" class="w-100 h-100 object-fit-cover">
                </div>
                <div class="d-none d-md-block">
                    <div class="fw-bold small text-dark">Sports Club</div>
                    <div class="fw-semibold small text-dark">Sabaragamuwa University Of Sri Lanka</div>
                </div>
            </div>

            <div class="d-none d-md-flex align-items-center gap-3">
                <div class="text-end">
                    <p class="fw-bold mb-0 text-dark"><?php echo $user['name']; ?></p>
                    <p class="mb-0 small text-secondary"><?php echo $user['coach_id']; ?></p>
                </div>
            </div>

            <!-- Mobile nav button -->
            <div class="d-md-none">
                <button class="btn btn-link text-dark fs-3 p-0 text-decoration-none" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    ☰
                </button>
                <ul class="dropdown-menu dropdown-menu-end bg-nav-bar border-0 w-100 m-0">
                    <li class="px-3 py-2 border-bottom border-light border-opacity-25">
                        <div class="d-flex align-items-center gap-2">
                            <div class="fs-1">👤</div>
                            <div>
                                <div class="fw-bold text-white small"><?php echo $user['name']; ?></div>
                                <div class="text-white small"><?php echo $user['coach_id']; ?></div>
                            </div>
                        </div>
                    </li>
                    <li><a class="dropdown-item text-white border-bottom border-light border-opacity-10 bg-nav-active"
                            href="#">Home</a></li>
                    <li><a class="dropdown-item text-white border-bottom border-light border-opacity-10 bg-nav-hover"
                            href="#">Schedules</a></li>
                    <li><a class="dropdown-item text-white border-bottom border-light border-opacity-10 bg-nav-hover"
                            href="#">Players</a></li>
                    <li><a class="dropdown-item text-white border-bottom border-light border-opacity-10 bg-nav-hover"
                            href="#">Players</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Desktop Navigation Bar -->
    <nav class="bg-nav-bar d-none d-md-block">
        <ul class="nav nav-fill">
            <li class="nav-item">
                <a class="nav-link text-white py-3 bg-nav-active" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-3 bg-nav-hover" href="#">Schedules</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-3 bg-nav-hover" href="#">Players</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white py-3 bg-nav-hover btn btn-danger" href="../Dashboard/logout.php">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="container py-4">
        <!-- Welcome Card -->
        <div class="card bg-primary text-white text-center shadow mb-4 rounded-4 border-0">
            <div class="card-body py-4">
                <h1 class="h2 mb-2">Welcome back Coach <?php echo $user['name']; ?>!</h1>
                <p class="mb-1">Let's make today's training count!</p>
                <p class="mb-0 fw-bold"><?php echo $sportName; ?></p>
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
                            <h2 class="display-6 fw-bold mb-0"><?= ($studentsResult->num_rows > 0) ? $studentsResult->num_rows : 0 ?></h2>
                        </div>
                        <div class="d-md-none text-center">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 50px; height: 50px; font-size: 24px;">
                                👥
                            </div>
                            <h2 class="display-6 fw-bold mb-1"><?= ($studentsResult->num_rows > 0) ? $studentsResult->num_rows : 0 ?></h2>
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
                                <p class="text-muted mb-0 small">Schedules</p>
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
                            <h2 class="display-6 fw-bold mb-0">5</h2>
                        </div>
                        <div class="d-md-none text-center">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 50px; height: 50px; font-size: 24px;">
                                ⚽
                            </div>
                            <h2 class="display-6 fw-bold mb-1">5</h2>
                            <p class="text-muted mb-0 small">Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule and Quick Actions -->
        <div class="row g-3">
            <!-- My Schedule -->
            <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0">My Schedule</h2>
                <button class="btn btn-danger btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">Add Items</button>
            </div>

            <div class="vstack gap-2">

                <?php while($row = $schedules->fetch_assoc()): ?>
                <div class="bg-primary text-white rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">

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
                                <?= $row['title'] ?>
                            </div>
                        </div>

                        <!-- Title for Desktop -->
                        <div class="d-none d-md-block fw-semibold fs-5">
                            <?= $row['title'] ?>
                        </div>

                        <!-- Delete Button -->
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-light btn-sm">Delete</a>
                    </div>
                </div>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
</div>


            <!-- Quick Actions -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h4 fw-bold mb-4">Quick Actions</h2>
                        <button class="btn btn-primary w-100 py-3 fs-5 fw-semibold">Book Facility</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Section -->
    <div class="container-fluid p-0 mt-5">
        <div class="bg-custom-blue p-4" style="min-height: 400px;">
            <div class="row justify-content-center">
                <div class="col-10 col-md-8 col-lg-6">
                <!-- Title -->
                    <div class="bg-primary text-white text-center fw-bold py-2 mb-3 rounded-top">
                        Players
                    </div>

                <!-- Table Container -->
                <div class="bg-white border border-white border-3 rounded p-3">
                    <!-- Table Header -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="bg-primary text-white text-center py-2 fw-semibold rounded">Name</div>
                        </div>
                         <div class="col-4">
                             <div class="bg-primary text-white text-center py-2 fw-semibold rounded">Student_ID</div>
                        </div>
                        <div class="col-4">
                            <div class="bg-primary text-white text-center py-2 fw-semibold rounded">NIC</div>
                        </div>
                    </div>

                    <!-- PHP Dynamic Data -->
                    <div class="vstack gap-2">

                    <?php if($studentsResult->num_rows > 0): ?>
                        <?php while($row = $studentsResult->fetch_assoc()): ?>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="bg-light rounded shadow-sm p-2 text-center">
                                    <small class="fw-medium text-dark">
                                        <?= htmlspecialchars($row['name']); ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded shadow-sm p-2 text-center">
                                    <small class="fw-medium text-dark">
                                        <?= htmlspecialchars($row['student_id']); ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light rounded shadow-sm p-2 text-center">
                                    <small class="fw-medium text-dark">
                                        <?= htmlspecialchars($row['nic']); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <p class="text-muted text-center">No students registered to this sport yet.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Footer Section -->
        <div class="bg-custom-dark-blue text-white text-center py-2 small">
            © 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.
        </div>
    
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>