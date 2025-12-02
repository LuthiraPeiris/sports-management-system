<?php
session_start();
include '../Dashboard/db.php';

// Block access if not student
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../Dashboard/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

$scheduleQuery = $conn->prepare("SELECT * FROM schedules WHERE user_id = ? ORDER BY schedule_date ASC");
$scheduleQuery->bind_param("i", $user_id);
$scheduleQuery->execute();
$schedules = $scheduleQuery->get_result();

$sports = $conn->query("
    SELECT * FROM student_sport
    WHERE user_id = $user_id
");

if(isset($_POST['add_schedule'])){
    $title = $_POST['title'];
    $date = $_POST['schedule_date'];
    $time = $_POST['schedule_time'];
    $description = $_POST['description'];

    $insert = $conn->prepare("INSERT INTO schedules (user_id, title, schedule_date, schedule_time, description) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("issss", $user_id, $title, $date, $time, $description);
    $insert->execute();

    header("Location: Student_Dashboard.php"); 
    exit();
}

if(isset($_GET['delete'])){
    $schedule_id = $_GET['delete'];
    $delete = $conn->prepare("DELETE FROM schedules WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $schedule_id, $user_id);
    $delete->execute();
    header("Location: Student_Dashboard.php");
    exit();
}

// ADD Achievement
if(isset($_POST['add_achievement'])){
    $title = $_POST['title'];
    $date = $_POST['date'];
    $event = $_POST['event_name'];

    $insert = $conn->prepare("INSERT INTO achievements (user_id, title, date, event_name) VALUES (?, ?, ?, ?)");
    $insert->bind_param("isss", $user_id, $title, $date, $event);
    $insert->execute();

    header("Location: Student_Dashboard.php");
    exit();
}

// DELETE Achievement
if(isset($_GET['delete_achievement'])){
    $ach_id = $_GET['delete_achievement'];

    $delete = $conn->prepare("DELETE FROM achievements WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $ach_id, $user_id);
    $delete->execute();

    header("Location: Student_Dashboard.php");
    exit();
}

// FETCH Achievements
$ach_query = $conn->prepare("SELECT * FROM achievements WHERE user_id = ? ORDER BY date DESC");
$ach_query->bind_param("i", $user_id);
$ach_query->execute();
$achievements = $ach_query->get_result();

// Add sports
if(isset($_POST['add_sport'])){
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $coach_name = $_POST['coach_name'];
    $date_time = $_POST['date_time'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO student_sport (user_id, title, coach_name, date_time, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $coach_name, $date_time, $location);
    $stmt->execute();

    header("Location: Student_Dashboard.php");
    exit();
}

// Delete sport
if(isset($_GET['delete_sport'])){
    $id = intval($_GET['delete_sport']); 
    $user_id = intval($_SESSION['user_id']);

    $stmt = $conn->prepare("DELETE FROM student_sport WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();

    // Redirect to avoid resubmission
    header("Location: Student_Dashboard.php");
    exit();
}

if(isset($_POST['register_sport'])){
    $user_id = $_SESSION['user_id'];
    $sport_id = intval($_POST['sport_id']);

    // CHECK IF STUDENT IS ALREADY REGISTERED FOR THIS SPORT
    $checkDuplicate = $conn->prepare("SELECT id FROM student_sport_registration WHERE user_id = ? AND sport_id = ?");
    $checkDuplicate->bind_param("ii", $user_id, $sport_id);
    $checkDuplicate->execute();
    $checkDuplicate->store_result();
    
    if($checkDuplicate->num_rows > 0) {
        // Student is already registered for this sport
        $_SESSION['error_message'] = "You are already registered for this sport!";
        $checkDuplicate->close();
        header("Location: Student_Dashboard.php");
        exit();
    }
    $checkDuplicate->close();

    // Fetch coach for this sport - CHECK IF COACH EXISTS
    $coachData = $conn->prepare("SELECT user_id AS coach_id FROM coach WHERE sport_id = ?");
    $coachData->bind_param("i", $sport_id);
    $coachData->execute();
    $coachResult = $coachData->get_result()->fetch_assoc();
    
    if(!$coachResult || $coachResult['coach_id'] === null) {
        // No coach assigned to this sport
        $_SESSION['error_message'] = "This sport currently has no coach assigned. Please try again later or contact administration.";
        $coachData->close();
        header("Location: Student_Dashboard.php");
        exit();
    }
    
    $coach_id = $coachResult['coach_id'];
    $coachData->close();

    // Insert into student_sport_registration
    $insert = $conn->prepare("INSERT INTO student_sport_registration (user_id, sport_id, coach_id) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $sport_id, $coach_id);
    
    if($insert->execute()){
        $_SESSION['success_message'] = "Sport Registered Successfully!";
    } else {
        $_SESSION['error_message'] = "Error: Could not register sport.";
    }
    $insert->close();
    
    header("Location: Student_Dashboard.php");
    exit();
}

// Schedule number counting
$scheduleCount = $schedules->num_rows;
// Count achievements
$achievementCount = $achievements->num_rows;

// Fetch the total number of sports already registered
$countQuery = $conn->prepare("SELECT COUNT(*) AS total FROM student_sport_registration WHERE user_id = ?");
$countQuery->bind_param("i", $user_id);
$countQuery->execute();
$result = $countQuery->get_result()->fetch_assoc();
$totalSports = $result['total'];

// The next number to display should be total + 1
$nextNumber = $totalSports + 1;

// Fetch registered sports for the current student to display in dropdown
$registeredSportsQuery = $conn->prepare("
    SELECT sport_id 
    FROM student_sport_registration 
    WHERE user_id = ?
");
$registeredSportsQuery->bind_param("i", $user_id);
$registeredSportsQuery->execute();
$registeredSportsResult = $registeredSportsQuery->get_result();

$registeredSportIds = [];
while($row = $registeredSportsResult->fetch_assoc()) {
    $registeredSportIds[] = $row['sport_id'];
}
$registeredSportsQuery->close();

// Fetch sports with coach information to display in dropdown
$sportsWithCoaches = $conn->query("
    SELECT s.sport_id, s.name, c.user_id AS coach_id 
    FROM sports s 
    LEFT JOIN coach c ON s.sport_id = c.sport_id
");

$sportsData = [];
while($row = $sportsWithCoaches->fetch_assoc()) {
    $sportsData[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Club Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/Favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color: #d9d9d9;}

        /* Header */
        .logo-icon { width: 40px;height: 40px;background: linear-gradient(135deg, #ff6b9d 0%, #ffa07a 100%);border-radius: 50%;display: flex;align-items: center;justify-content: center;color: white;font-weight: bold;}
        .top-header { background-color: rgba(62, 105, 145, 0.95); box-shadow: 0 2px 4px rgba(0,0,0,0.1);backdrop-filter: blur(8px);}

        /* Navigation */
        nav.navbar.navbar-custom {background: rgba(40, 70, 100, 0.95) !important;backdrop-filter: blur(8px) !important;}
        .navbar-custom .navbar-toggler-icon {background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;}
        .navbar-custom .navbar-toggler {border-color: rgba(255, 255, 255, 0.3) !important;}

        /* Navigation links */
        .navbar-custom .nav-link {color: rgba(255, 255, 255, 0.85) !important;font-weight: 500; transition: all 0.3s;border-radius: 4px;padding: 0.5rem 1rem !important;margin: 0 0.25rem;}

        /* Hover state */
        .navbar-custom .nav-link:hover {color: white !important;background-color: rgba(255, 255, 255, 0.1) !important;}
        .navbar-custom .nav-link.active {color: white !important;background-color: rgba(255, 255, 255, 0.15) !important;font-weight: 600;}

        /* Logout button */
        .logout-btn {background-color: rgba(220, 53, 69, 0.2) !important;color: white !important;border: 1px solid rgba(255, 255, 255, 0.3) !important;padding: 0.5rem 1.5rem !important;border-radius: 4px;font-weight: 500;transition: all 0.2s;}
        .logout-btn:hover {background-color: rgba(220, 53, 69, 0.3) !important;border-color: rgba(255, 255, 255, 0.4) !important;color: white !important;}

        /* Welcome Banner */
        .welcome-banner {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);border-radius: 15px;color: white;box-shadow: 0 4px 12px rgba(0,0,0,0.15);}

        /* Stats Cards */
        .stat-card {box-shadow: 0 3px 10px rgba(0,0,0,0.12);}
        .stat-icon {width: 55px;height: 55px;border-radius: 50%;display: flex;align-items: center;justify-content: center;font-size: 26px;background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);color: white;
        }

        .icon-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-orange { background: linear-gradient(135deg, #ff6b9d 0%, #ffa07a 100%); }
        .icon-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-gold { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

        /* Schedule Section */
        .schedule-section {box-shadow: 0 3px 10px rgba(0,0,0,0.12);}

        .add-btn {background-color: #ff3333;color: white;border: none;border-radius: 20px;font-weight: 600;transition: background-color 0.3s;}
        .add-btn:hover {background-color: #e62e2e;}
        .schedule-item {background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius: 10px;color: white;}
        .schedule-date .day {font-size: 26px;font-weight: 700;line-height: 1;}
        .schedule-date .month {font-size: 12px;text-transform: uppercase;font-weight: 600;}

        /* Quick Actions */
        .quick-actions {box-shadow: 0 3px 10px rgba(0,0,0,0.12);}
        .action-btn {background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;border: none;border-radius: 10px;font-weight: 600;transition: transform 0.2s;}
        .action-btn:hover {transform: translateY(-2px);}

        /* My Sports Section */
        .my-sports-section {background: rgba(223, 211, 211, 1);}
        .sport-card {box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
        .leave-btn {background-color: #ff3333;color: white;border: none;border-radius: 20px;font-weight: 600;}
        .leave-btn:hover {background-color: #e62e2e;}

        .achievements-section {background: rgb(217, 217, 217);padding: 40px 0;}
        .section-header {display: flex;justify-content: space-between;align-items: center;margin-bottom: 20px;}
        .section-header h3 {font-size: 28px;font-weight: 600;margin: 0;color: #333;}
        .add-btn {background-color: #ff3333;color: white;border: none;padding: 8px 20px;border-radius: 20px;font-size: 12px;font-weight: 600;cursor: pointer;transition: background-color 0.3s;}
        .add-btn:hover {background-color: #e62e2e;}
        .achievement-card {background: white;padding: 20px;border-radius: 10px;box-shadow: 0 2px 8px rgba(0,0,0,0.1);height: 100%;}
        .achievement-card h6 {font-size: 16px;font-weight: 600;margin-bottom: 10px;color: #333;}
        .achievement-card p {font-size: 13px;color: #666;margin: 5px 0;}

        /* Footer */
        .footer {background-color: rgb(30, 58, 85);color: white;}
    </style>
</head>
<body>
    <!-- Header -->
<header class="py-3" style="background-color: rgba(62, 105, 145, 0.95); backdrop-filter: blur(8px);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Logo & Brand -->
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 shadow-sm"
                    style="width: 56px; height: 56px; border: 1.5px solid rgba(255, 255, 255, 0.25);">
                    <img src="../images/Favicon.png" alt="Sports Club Logo" class="w-100 h-100 object-fit-cover p-1">
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold text-white">Sports Club</h6>
                    <p class="mb-0 small text-white opacity-85">Sabaragamuwa University of Sri Lanka</p>
                </div>
            </div>
            
            <!-- Student Info -->
            <div class="d-flex align-items-center">
    <div class="text-end ps-3">
        <p class="mb-0 fw-semibold text-white" style="font-size: 0.95rem;">
            <?php echo htmlspecialchars($user['name']); ?>
        </p>
        <small class="text-white opacity-85"><?php echo htmlspecialchars($user['student_id']); ?></small>
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
            <!-- Center Nav Items -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link px-3" 
                       href="../Homepage.php#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" 
                       href="#sports">My Sports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" 
                       href="#schedules">My Schedules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" 
                       href="#achievements">Achievements</a>
                </li>
            </ul>
            
            <!-- Logout Button Right-Aligned -->
            <a href="../Dashboard/logout.php" 
               class="btn ms-lg-3 logout-btn">Logout</a>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <main class="container my-4">
        <!-- Welcome Banner -->
        <div class="card bg-primary text-white text-center shadow mb-4 rounded-4 border-0">
            <div class="card-body py-4">
                <h1 class="h2 mb-2">Welcome back <?php echo $user['name']; ?>!</h1>
                <p class="mb-0">Ready to achieve your sports goals today?</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon icon-blue mb-3">⚽</div>
                    <h3 id="sportCount" class="display-6 fw-bold"><?= $nextNumber ?></h3>
                    <p class="text-muted">Sports Enrolled</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon icon-orange mb-3">📅</div>
                    <h3><span class="display-6 fw-bold"><?= $scheduleCount ?></span></h3>
                    <p class="text-muted">Schedules</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon icon-gold mb-3">🏆</div>
                    <h3><span class="display-6 fw-bold"><?= $achievementCount ?></span></h3>
                    <p class="text-muted">Achievements Earned</p>
                </div>
            </div>
        </div>

        <div class="row g-4" >
            <!-- My Schedule -->
            <div class="col-lg-8" id="schedules">
    <div class="schedule-section bg-white rounded-3 p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">My Schedule</h4>
            <button class="add-btn btn px-4 py-2" data-bs-toggle="modal" data-bs-target="#addScheduleModal">Add Item</button>
        </div>

        <?php if($scheduleCount > 0): ?>
    <?php while($row = $schedules->fetch_assoc()): ?>
    <div class="schedule-item p-3 mb-3 d-flex justify-content-between align-items-center bg-light rounded">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="schedule-date text-center me-3">
                <span class="day"><?= date("d", strtotime($row['schedule_date'])) ?></span>
                <span class="month d-block"><?= strtoupper(date("M", strtotime($row['schedule_date']))) ?></span>
            </div>
            <div class="flex-grow-1">
                <div class="schedule-title fw-semibold fs-5 mb-1"><?= htmlspecialchars($row['title']) ?></div>
                <small class="text-muted"><?= date("h:i A", strtotime($row['schedule_time'])) ?></small>
                <?php if(!empty($row['description'])): ?>
                    <div class="schedule-description text-muted mb-1" style="font-size: 1.2rem;">
                        <?= htmlspecialchars($row['description']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm ms-3">Delete</a>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="text-muted">No schedules added yet.</p>
<?php endif; ?>
    </div>
</div>
            
            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="quick-actions bg-white rounded-3 p-4 h-100">
                    <h4 class="fw-semibold mb-4">Quick Actions</h4>
                    <button class="action-btn btn btn-primary w-100 py-3 mb-3" data-bs-toggle="modal" data-bs-target="#registerSportModal">
      Register for Sport
    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- My Sports Section -->
    <section class="my-sports-section py-5 mt-5" id="sports">
        <div class="container">
            <h3 class="text-center fw-semibold text-black mb-4">My Sports</h3>

            <div class="text-center mb-4">
                <button class="btn btn-light px-4 py-2" data-bs-toggle="modal" data-bs-target="#addSportModal">+ Add Sport</button>
            </div>

            <div class="row g-4">
                <?php while($row = $sports->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="sport-card bg-white rounded-3 p-4 h-100">
                        <h5 class="fw-bold"><?php echo $row['title']; ?></h5>
                        <p class="coach text-muted">Coach: <?php echo $row['coach_name']; ?></p>

                        <div class="sport-details">
                            <p class="mb-2"><strong>Next Practice:</strong> <?php echo date("d M, h:i A", strtotime($row['date_time'])); ?></p>
                            <p class="mb-2"><strong>Location:</strong> <?php echo $row['location']; ?></p>
                        </div>

                        <a href="Student_Dashboard.php?delete_sport=<?= $row['id'] ?>" onclick="return confirm('Remove this sport?')" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </div>
                <?php endwhile; ?>

                <?php if($sports->num_rows == 0): ?>
                    <p class="text-center text-light">Add your sports and schedule time date in here</p>
                <?php endif; ?>
            </div>
        </div>
    </section>


<!-- Achievements Section -->
<section class="achievements-section py-5" id="achievements">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-center fw-semibold mb-0">Achievements</h3>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addAchModal">Add Achievement</button>
        </div>
        <div class="row g-4">
            <?php if($achievementCount > 0): ?>
                <?php while($ach = $achievements->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="achievement-card bg-white rounded-3 p-4 h-100 position-relative">
                        <h6 class="fw-semibold"><?= htmlspecialchars($ach['title']) ?></h6>
                        <p class="mb-1"><strong>Date:</strong> <?= date("F Y", strtotime($ach['date'])) ?></p>
                        <p class="mb-4"><strong>Event:</strong> <?= htmlspecialchars($ach['event_name']) ?></p>

                        <!-- Delete Button -->
                        <a href="?delete_achievement=<?= $ach['id'] ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">X</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No achievements added yet.</p>
            <?php endif; ?>
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

    

<div class="modal fade" id="addAchModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
            <h5 class="modal-title">Add Achievement</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="title" class="form-control mb-2" placeholder="Achievement Title" required>
          <input type="date" name="date" class="form-control mb-2" required>
          <input type="text" name="event_name" class="form-control mb-2" placeholder="Event Name" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_achievement" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addSportModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Sport</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <label class="form-label">Sport Title</label>
        <input type="text" name="title" class="form-control" required>

        <label class="form-label mt-3">Coach Name</label>
        <input type="text" name="coach_name" class="form-control" required>

        <label class="form-label mt-3">Date & Time</label>
        <input type="datetime-local" name="date_time" class="form-control" required>

        <label class="form-label mt-3">Location</label>
        <input type="text" name="location" class="form-control" required>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="add_sport">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- Register Sport Modal -->
<div class="modal fade" id="registerSportModal" tabindex="-1" aria-labelledby="registerSportLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-semibold" id="registerSportLabel">Register for a Sport</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="" method="POST" id="sportRegistrationForm">
        <div class="modal-body">
          <!-- Display error/success messages -->
          <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <?php echo $_SESSION['error_message']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
          <?php endif; ?>
          
          <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i>
              <?php echo $_SESSION['success_message']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
          <?php endif; ?>
          
          <div class="mb-3">
            <label class="form-label">Select Sport</label>
            <select name="sport_id" class="form-select" required>
              <option value="">-- Choose Sport --</option>
              <?php
              foreach($sportsData as $sport) {
                  $isRegistered = in_array($sport['sport_id'], $registeredSportIds);
                  $hasCoach = !empty($sport['coach_id']);
                  $disabled = $isRegistered || !$hasCoach;
                  
                  $registeredText = $isRegistered ? ' (Already Registered)' : '';
                  $noCoachText = !$hasCoach ? ' (No Coach Available)' : '';
                  
                  echo "<option value='{$sport['sport_id']}' {$disabled}>{$sport['name']}{$registeredText}{$noCoachText}</option>";
              }
              ?>
            </select>
            <div class="form-text text-muted">
              <ul class="small mb-0">
                <li>Sports marked with "(Already Registered)" are ones you're already enrolled in.</li>
                <li>Sports marked with "(No Coach Available)" cannot be registered at this time.</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="register_sport" class="btn btn-primary px-4">Register Sport</button>
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
        // Add interactivity for Add Items button
        document.querySelector('.add-btn').addEventListener('click', function() {
            alert('Add new schedule item functionality');
        });

        // Add interactivity for Quick Action buttons
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function() {
                alert(this.textContent + ' clicked!');
            });
        });

        // Add interactivity for Leave Sport buttons
        document.querySelectorAll('.leave-btn').forEach(button => {
            button.addEventListener('click', function() {
                const sportName = this.closest('.sport-card').querySelector('h5').textContent;
                if(confirm(`Are you sure you want to leave ${sportName}?`)) {
                    alert(`You have left ${sportName}`);
                }
            });
        });

        // Add Achievement button functionality
document.querySelector('.add-btn').addEventListener('click', function() {
    alert('Add Achievement functionality');
    // You can replace this with a modal or form to add new achievements
});

document.addEventListener('DOMContentLoaded', function() {
    const sportSelect = document.querySelector('select[name="sport_id"]');
    const registerButton = document.querySelector('button[name="register_sport"]');
    const form = document.getElementById('sportRegistrationForm');
    
    // Check if selected sport is already registered or has no coach
    sportSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const isDisabled = selectedOption.disabled;
        const optionText = selectedOption.textContent;
        const hasNoCoach = optionText.includes('(No Coach Available)');
        
        if (isDisabled) {
            registerButton.disabled = true;
            registerButton.classList.add('btn-secondary');
            registerButton.classList.remove('btn-primary');
            
            if (hasNoCoach) {
                registerButton.title = "This sport has no coach assigned";
            } else {
                registerButton.title = "You are already registered for this sport";
            }
        } else {
            registerButton.disabled = false;
            registerButton.classList.remove('btn-secondary');
            registerButton.classList.add('btn-primary');
            registerButton.title = "Register for this sport";
        }
    });
    
    // Prevent form submission for disabled options
    form.addEventListener('submit', function(e) {
        const selectedOption = sportSelect.options[sportSelect.selectedIndex];
        const isDisabled = selectedOption.disabled;
        const optionText = selectedOption.textContent;
        const hasNoCoach = optionText.includes('(No Coach Available)');
        
        if (isDisabled) {
            e.preventDefault();
            if (hasNoCoach) {
                alert('This sport currently has no coach assigned. Please choose a different sport or contact administration.');
            } else {
                alert('You are already registered for this sport! Please choose a different sport.');
            }
            return false;
        }
        
        return true;
    });
});

    </script>
</body>
</html>