<?php
include 'db.php';

if (isset($_POST['register'])) {

    // Get form data safely
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $contact    = trim($_POST['contact']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role       = $_POST['role'];
    $sport_id   = intval($_POST['sport_id']);

    // Role-based fields
    if ($role == 'student') {
        $student_id = trim($_POST['studentID']);
        $nic        = trim($_POST['studentNIC']);
        $coach_id   = NULL;
    } else { // coach
        $coach_id   = intval($_POST['coachID']);
        $nic        = trim($_POST['coachNIC']);
        $student_id = NULL;
    }

    // -------------------------
    // ❗ VALIDATIONS
    // -------------------------

    // Check empty fields
    if (empty($name) || empty($email) || empty($contact) || empty($nic)) {
        echo "<script>alert('Please fill all required fields'); window.history.back();</script>";
        exit;
    }

    // Check duplicate email
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
        exit;
    }
    $checkEmail->close();

    // Check duplicate NIC
    $checkNIC = $conn->prepare("SELECT id FROM users WHERE nic = ?");
    $checkNIC->bind_param("s", $nic);
    $checkNIC->execute();
    $checkNIC->store_result();

    if ($checkNIC->num_rows > 0) {
        echo "<script>alert('NIC already exists!'); window.history.back();</script>";
        exit;
    }
    $checkNIC->close();


    // INSERT into users table

    $stmt = $conn->prepare("
        INSERT INTO users 
        (name, email, contact, password, role, nic, sport_id, student_id, coach_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssssiss", 
        $name, 
        $email, 
        $contact, 
        $password, 
        $role, 
        $nic, 
        $sport_id, 
        $student_id, 
        $coach_id
    );

    if ($stmt->execute()) {

        $user_id = $conn->insert_id; // user's ID

        // STUDENT REGISTRATION
        if ($role == 'student') {
            $stmt2 = $conn->prepare("
                INSERT INTO student (user_id, name, contact, nic, sport_id, student_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt2->bind_param("isssis", 
                $user_id, 
                $name, 
                $contact, 
                $nic, 
                $sport_id, 
                $student_id
            );

            $stmt2->execute();
            $stmt2->close();

        } 
        // COACH REGISTRATION
        else {

            $stmt3 = $conn->prepare("
                INSERT INTO coach (user_id, name, nic, sport_id, coach_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt3->bind_param("issii", 
                $user_id, 
                $name, 
                $nic, 
                $sport_id, 
                $coach_id
            );
            $stmt3->execute();
            $stmt3->close();

            // Avoid duplicate assignment
            $checkAssign = $conn->prepare("
                SELECT id FROM sport_coach WHERE sport_id = ? AND coach_id = ?
            ");
            $checkAssign->bind_param("ii", $sport_id, $user_id);
            $checkAssign->execute();
            $checkAssign->store_result();

            if ($checkAssign->num_rows == 0) {
                $assign = $conn->prepare("
                    INSERT INTO sport_coach (sport_id, coach_id)
                    VALUES (?, ?)
                ");
                $assign->bind_param("ii", $sport_id, $user_id);
                $assign->execute();
                $assign->close();
            }
            $checkAssign->close();
        }

        echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";

    } else {
        echo "<script>alert('Registration Failed: " . addslashes($stmt->error) . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>
    <link rel="icon" type="image/x-icon" href="../images/Favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("../images/register.png");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .role-btn {
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.25s ease;
            background-color: #ffffff88;
            backdrop-filter: blur(6px);
        }

        /* Hover effect */
        .role-btn:hover {
            background-color: #e6e9ff;
            border-color: #2937a5;
            color: #2937a5;
        }

        /* Active (Selected) Button */
        .role-btn.active {
            background-color: #2937a5 !important;
            border-color: #2937a5 !important;
            color: white !important;
            box-shadow: 0 0 12px rgba(41, 55, 165, 0.4);
        }

        .home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: rgba(198, 220, 228, 0.95);
            color: #333;
            border: 2px solid rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.15);
            transition: 0.3s;
        }

        .home-btn:hover {
            background-color: white;
            color: rgb(70, 140, 252);
            border-color: rgba(28, 52, 139, 0.97);
            box-shadow: 0px 4px 15px rgba(143, 70, 252, 0.85);
            transform: translateY(-2px);
        }

        .login-link {
            color: #1a1a1a;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .login-link:hover {
            color: rgb(70, 140, 252);
            text-decoration: underline;
        }

        .btn {
            transition: 0.7s;
        }

        .btn:hover {
            box-shadow: 1px 2px 6px rgb(78, 78, 78);
        }
    </style>
</head>

<body class="bg-light">
    <!-- Back to Homepage Button -->
    <a href="../Homepage.php" class="btn home-btn">
        ← Back to Home
    </a>

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh; padding: 20px; overflow-y: auto;">
        <div class="card shadow-lg p-4 w-100 rounded-4" style="max-width: 650px; background:   rgba(198, 220, 228, 0.95);box-shadow: 0px 2px 8px rgb(70, 140, 252);backdrop-filter: blur(10px); ">
            <h2 class="text-center mb-4 fw-bold" style="color:#1a1a1a;" id="formTitle"> Registration Form</h2>

            <!-- Role Selection -->
            <div class="mb-4">
                <label class="form-label fw-semibold" style="color: #333;">Select Role:</label>
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-outline-primary role-btn active" id="studentBtn">Student</button>
                    <button type="button" class="btn btn-outline-primary role-btn" id="coachBtn">Coach</button>
                </div>
            </div>

            <form action="" method="post">
                <input type="hidden" name="role" id="selectedRole" value="student">

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Your Name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Email:</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Password:</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter Your Password" required>
                        </div>

                        <div class="mb-3">
                            <label for="sport_id" class="form-label">Select Sport</label>
                            <select name="sport_id" id="sport_id" class="form-select" required>
                            <option value="">-- Choose a Sport --</option>
                            <?php
                            include 'db.php';
                            // Fetch all sports
                            $sports = $conn->query("SELECT sport_id, name FROM sports");

                            // Fetch sports that already have a coach
                            $assigned = [];
                            $result = $conn->query("SELECT sport_id FROM sport_coach");
                            while ($row = $result->fetch_assoc()) {
                                $assigned[] = $row['sport_id'];
                            }

                            while ($row = $sports->fetch_assoc()) {
                                $data_assigned = in_array($row['sport_id'], $assigned) ? "data-assigned='1'" : "";
                                $text = $row['name'] . (in_array($row['sport_id'], $assigned) ? " (Coach Assigned)" : "");
                                echo "<option value='{$row['sport_id']}' $data_assigned>$text</option>";
                            }
                            ?>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Student Fields -->
                <div id="studentFields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333;">Student ID:</label>
                                <input type="text" name="studentID" id="studentID" class="form-control" placeholder="Enter Student ID" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333;">NIC:</label>
                                <input type="text" name="studentNIC" id="studentNIC" class="form-control" placeholder="Enter NIC" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coach Fields -->
                <div id="coachFields" style="display:none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333">Coach ID:</label>
                                <input type="text" name="coachID" id="coachID" class="form-control" placeholder="Enter Coach ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333">NIC:</label>
                                <input type="text" name="coachNIC" id="coachNIC" class="form-control" placeholder="Enter NIC">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Contact:</label>
                            <input type="contact" name="contact" class="form-control" placeholder="Enter Your Contact" required>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="register" class="btn btn-primary px-4">Register</button>
                </div>
            </form>
            <!-- Login Link -->
            <div class="mt-3 text-center">
                <a href="Login.php" class="login-link">Already have an account? Login here</a>
            </div>
        </div>
    </div>

    <script>
        const studentBtn = document.getElementById('studentBtn');
        const coachBtn = document.getElementById('coachBtn');
        const studentFields = document.getElementById('studentFields');
        const coachFields = document.getElementById('coachFields');
        const selectedRole = document.getElementById('selectedRole');
        const sportSelect = document.getElementById('sport_id');

        studentBtn.onclick = () => {
            selectedRole.value = "student";
            studentBtn.classList.add('active');
            coachBtn.classList.remove('active');

            studentFields.style.display = 'block';
            coachFields.style.display = 'none';

            document.getElementById('studentID').required = true;
            document.getElementById('studentNIC').required = true;
            document.getElementById('coachID').required = false;
            document.getElementById('coachNIC').required = false;

            // Enable all sports for students
            for (let option of sportSelect.options) {
                option.disabled = false;
            }
        };

        coachBtn.onclick = () => {
            selectedRole.value = "coach";
            coachBtn.classList.add('active');
            studentBtn.classList.remove('active');

            coachFields.style.display = 'block';
            studentFields.style.display = 'none';

            document.getElementById('coachID').required = true;
            document.getElementById('coachNIC').required = true;
            document.getElementById('studentID').required = false;
            document.getElementById('studentNIC').required = false;

            // Disable sports that already have a coach
            for (let option of sportSelect.options) {
                option.disabled = option.dataset.assigned === '1';
            }
        };

    </script>


</body>

</html>