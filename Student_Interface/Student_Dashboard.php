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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Club Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #d9d9d9;
        }

        /* Header */
        .top-header { background-color:  #b3e5fc; box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
        .logo-icon { width: 40px;height: 40px;background: linear-gradient(135deg, #ff6b9d 0%, #ffa07a 100%);border-radius: 50%;display: flex;align-items: center;justify-content: center;color: white;font-weight: bold;}

        /* Navigation */
        .navbar-custom {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);}
        .navbar-custom .nav-link {color: rgba(255, 255, 255, 0.85);font-weight: 500;transition: all 0.3s;}
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {color: white;background-color: rgba(255, 255, 255, 0.1);border-radius: 5px;}

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
        .my-sports-section {background: linear-gradient(135deg, #7e8ef5 0%, #9b9ef5 100%);}
        .sport-card {box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
        .leave-btn {background-color: #ff3333;color: white;border: none;border-radius: 20px;font-weight: 600;}
        .leave-btn:hover {background-color: #e62e2e;}

        .achievements-section {background: #f5e6d3;padding: 40px 0;}
        .section-header {display: flex;justify-content: space-between;align-items: center;margin-bottom: 20px;}
        .section-header h3 {font-size: 28px;font-weight: 600;margin: 0;color: #333;}
        .add-btn {background-color: #ff3333;color: white;border: none;padding: 8px 20px;border-radius: 20px;font-size: 12px;font-weight: 600;cursor: pointer;transition: background-color 0.3s;}
        .add-btn:hover {background-color: #e62e2e;}
        .achievement-card {background: white;padding: 20px;border-radius: 10px;box-shadow: 0 2px 8px rgba(0,0,0,0.1);height: 100%;}
        .achievement-card h6 {font-size: 16px;font-weight: 600;margin-bottom: 10px;color: #333;}
        .achievement-card p {font-size: 13px;color: #666;margin: 5px 0;}

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
                        <small><?php echo $user['student_id']; ?></small>
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
                        <a class="nav-link px-3" href="#">My Sports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#">My Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#">Achievements</a>
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
            <h2 class="display-5 fw-semibold">Welcome back <?php echo $user['name']; ?>!</h2>
            <p class="fs-5">Ready to achieve your sports goals today?</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon icon-blue mb-3">⚽</div>
                    <h3 class="display-6 fw-bold">3</h3>
                    <p class="text-muted">Sports Enrolled</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon icon-orange mb-3">📅</div>
                    <h3 class="display-6 fw-bold">5</h3>
                    <p class="text-muted">Events Registered</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white rounded-3 p-4 h-100">
                    <div class="stat-icon icon-gold mb-3">🏆</div>
                    <h3 class="display-6 fw-bold">3</h3>
                    <p class="text-muted">Achievements Earned</p>
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
                    <h4 class="fw-semibold mb-4">Quick Actions</h4>
                    <button class="action-btn btn w-100 py-3 mb-3">Register for Sport</button>
                </div>
            </div>
        </div>
    </main>

    <!-- My Sports Section -->
    <section class="my-sports-section py-5 mt-5">
        <div class="container">
            <h3 class="text-center fw-semibold text-white mb-4">My Sports</h3>

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
<section class="achievements-section py-5">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-center fw-semibold mb-0">Achievements</h3>
            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addAchModal">Add Achievement</button>
        </div>
        <div class="row g-4">
            <?php while($ach = $achievements->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-3">
                <div class="achievement-card bg-white rounded-3 p-4 h-100 position-relative">
                    <h6 class="fw-semibold"><?= $ach['title'] ?></h6>
                    <p class="mb-1"><strong>Date:</strong> <?= date("F Y", strtotime($ach['date'])) ?></p>
                    <p class="mb-4"><strong>Event:</strong> <?= $ach['event_name'] ?></p>

                    <!-- Delete Button -->
                    <a href="?delete_achievement=<?= $ach['id'] ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">X</a>
                </div>
            </div>
            <?php endwhile; ?>
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


    <!-- Footer -->
    <footer class="footer py-3 text-center">
        <div class="container">
            <p class="mb-0">© 2025 Group2. All rights reserved.</p>
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
    </script>
</body>
</html>